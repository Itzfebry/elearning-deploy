<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MataPelajaranRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MataPelajaranController extends Controller
{
    protected $param;

    public function __construct(MataPelajaranRepository $mataPelajaran)
    {
        $this->param = $mataPelajaran;
    }
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $mataPelajaran = $this->param->getData($search, $limit);
        return view("pages.role_admin.mata_pelajaran.index", compact("mataPelajaran"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = Guru::all();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        return view("pages.role_admin.mata_pelajaran.create", compact("guru", "kelas", "tahunAjaran"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nama' => 'required',
                'guru_nip' => 'required',
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
            ]);

            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di Tambahkan.");
            return redirect()->route("mata-pelajaran");
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
        $mataPelajaran = $this->param->find($id);
        $guru = Guru::all();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        return view("pages.role_admin.mata_pelajaran.edit", compact(["mataPelajaran", "guru", "kelas", "tahunAjaran"]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'nama' => 'required',
                'guru_nip' => 'required',
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
            ]);

            $old = $this->param->find($id);
            $isManual = $old->guru_nip !== $data['guru_nip'];
            $this->param->update($data, $id);
            // Audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_pengampu_mapel',
                'description' => 'Update pengampu mapel: ' . $old->nama . ' (' . $old->id . ') dari ' . $old->guru_nip . ' ke ' . $data['guru_nip'],
            ]);
            if ($isManual) {
                Alert::warning('Perhatian', 'Anda mengganti pengampu mata pelajaran tanpa fitur pemindahan data. Data tugas dan submit tugas lama tetap milik guru sebelumnya.');
            } else {
            Alert::success("Berhasil", "Data Berhasil di Ubah.");
            }
            return redirect()->route("mata-pelajaran");
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
    public function destroy(string $id)
    {
        //
    }
}
