<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\AuditLog;
use App\Repositories\GuruRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    protected $param;
    protected $param2;

    public function __construct(GuruRepository $guru, UserRepository $userRepository)
    {
        $this->param = $guru;
        $this->param2 = $userRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $guru = $this->param->getData($search, $limit);
        return view("pages.role_admin.guru.index", compact("guru"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.role_admin.guru.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $dataUser = $request->validate([
                'email' => 'required',
                'role' => 'required',
            ]);

            $data = $request->validate([
                'nip' => 'required|string|size:18|unique:guru,nip',
                'nama' => 'required|string',
                'jk' => 'required',
            ]);

            if (Guru::where('nip', $data['nip'])->exists()) {
                Alert::error("Terjadi Kesalahan", "NIP sudah terdaftar.");
                return back()->withInput();
            }

            $dataUser['pass'] = $request->nip;
            $user = $this->param2->store($dataUser);

            $data["user_id"] = $user->id;
            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di simpan.");
            return redirect()->route("guru");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        } catch (QueryException $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guru = $this->param->find($id);
        return view("pages.role_admin.guru.edit", compact("guru"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $dataUser = $request->validate([
                'email' => 'required',
            ]);

            $data = $request->validate([
                'nip' => 'required|string|size:18',
                'nama' => 'required|string',
                'jk' => 'required',
            ]);

            $this->param2->update($dataUser, $request->user_id);
            $this->param->update($data, $id);
            Alert::success("Berhasil", "Data Berhasil di ubah.");
            return redirect()->route("guru");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        } catch (QueryException $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $guru = Guru::find($request->formid);
            if (!$guru) {
                Alert::error('Gagal', 'Guru tidak ditemukan.');
                return back();
            }
            $nip = trim(strtoupper($guru->nip));
            // Cek apakah guru masih menjadi wali kelas (robust, ignore case & space)
            $isWali = Kelas::whereRaw('TRIM(UPPER(nip_wali)) = ?', [$nip])->exists();
            if ($isWali) {
                Alert::error('Gagal', 'Tidak dapat menghapus guru karena masih menjadi wali kelas. Silakan ganti wali kelas terlebih dahulu.');
                return back();
            }
            $this->param->destroy($request->formid);
            $this->param2->destroy($request->user_id);
            // Audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_guru',
                'description' => 'Menghapus guru: ' . $guru->nama . ' (' . $guru->nip . ')',
            ]);
            Alert::success("Berhasil", "Data Berhasil di hapus data.");
            return redirect()->route("guru");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back();
        }
    }
}
