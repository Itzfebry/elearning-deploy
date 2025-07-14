<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Tugas;
use App\Models\Quizzes;
use App\Models\AuditLog;

class AdminController extends Controller
{
    protected $param;
    protected $paramUser;

    public function __construct(AdminRepository $admin, UserRepository $user)
    {
        $this->param = $admin;
        $this->paramUser = $user;
    }
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $admin = $this->param->getData($search, $limit);
        return view("pages.role_admin.admin.index", compact("admin"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.role_admin.admin.create");
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

            if (Admin::where('nip', $data['nip'])->exists()) {
                Alert::error("Terjadi Kesalahan", "NIP sudah terdaftar.");
                return back()->withInput();
            }

            $dataUser['pass'] = $request->nip;
            $user = $this->paramUser->store($dataUser);

            $data["user_id"] = $user->id;
            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di simpan.");
            return redirect()->route("admin");
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
        $admin = $this->param->find($id);
        return view("pages.role_admin.admin.edit", compact("admin"));
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

            $this->paramUser->update($dataUser, $request->user_id);
            $this->param->update($data, $id);
            Alert::success("Berhasil", "Data Berhasil di ubah.");
            return redirect()->route("admin");
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
            $this->param->destroy($request->formid);
            $this->paramUser->destroy($request->user_id);
            Alert::success("Berhasil", "Data Berhasil di Hapus.");
            return redirect()->route("admin");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back();
        }
    }

    // Menampilkan halaman form pemindahan data
    public function pindahDataPage()
    {
        $mataPelajaran = MataPelajaran::all();
        $guru = Guru::all();
        return view('pages.role_admin.pindah_data', compact('mataPelajaran', 'guru'));
    }

    // Proses pemindahan data guru
    public function pindahGuruMatpel(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:matapelajaran,id',
            'guru_lama_nip' => 'required|exists:guru,nip',
            'guru_baru_nip' => 'required|exists:guru,nip',
        ]);

        if ($request->guru_lama_nip == $request->guru_baru_nip) {
            Alert::error('Gagal', 'Guru lama dan guru baru tidak boleh sama.');
            return back()->withInput();
        }

        $mapel = MataPelajaran::where('id', $request->mata_pelajaran_id)
            ->where('guru_nip', $request->guru_lama_nip)
            ->first();

        if (!$mapel) {
            Alert::error('Gagal', 'Guru lama bukan pengampu mata pelajaran yang dipilih.');
            return back()->withInput();
        }

        // Update tugas
        Tugas::where('guru_nip', $request->guru_lama_nip)
            ->where('matapelajaran_id', $request->mata_pelajaran_id)
            ->update(['guru_nip' => $request->guru_baru_nip]);

        // Update guru pengampu pada tabel matapelajaran
        MataPelajaran::where('id', $request->mata_pelajaran_id)
            ->where('guru_nip', $request->guru_lama_nip)
            ->update(['guru_nip' => $request->guru_baru_nip]);

        Alert::success('Berhasil', 'Data guru berhasil dipindahkan.');
        return redirect()->route('admin.pindah-data');
    }

    public function auditLogPage()
    {
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(30);
        return view('pages.role_admin.audit_log', compact('logs'));
    }
}
