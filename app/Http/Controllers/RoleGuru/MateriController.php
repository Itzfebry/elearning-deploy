<?php

namespace App\Http\Controllers\RoleGuru;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Materi;
use App\Models\TahunAjaran;
use App\Repositories\MateriRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    protected $param;

    public function __construct(MateriRepository $materi)
    {
        $this->param = $materi;
    }
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $materi = $this->param->getData($search, $limit);
        return view("pages.role_guru.materi.index", compact("materi"));
    }

    public function detail($id)
    {
        $materiDetail = Materi::find($id);
        return view("pages.role_guru.materi.detail", compact("materiDetail"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        $matpel = MataPelajaran::where('guru_nip', Auth::user()->guru->nip)->get();
        return view("pages.role_guru.materi.create", compact('matpel', 'tahunAjaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $tanggal = $request->input('tanggal');
            $guruNip = $request->user()->guru->nip;

            // dd($request->file('path'));

            if ($request->type == "buku") {
                // Validasi untuk buku (file PDF)
                $validated = $request->validate([
                    'matapelajaran_id' => 'required',
                    'semester' => 'required',
                    'type' => 'required',
                    'judul_materi' => 'required',
                    'deskripsi' => 'required',
                    'path' => 'required|file|mimes:pdf|max:5120',
                    'tahun_ajaran' => 'required',
                ]);

                $judul = Str::slug(Str::limit($request->judul_materi, 50));
                $ext = $request->file('path')->getClientOriginalExtension();
                $namaFile = "{$guruNip}_{$tanggal}_{$judul}.{$ext}";

                $path = $request->file('path')->storeAs('materi', $namaFile, 'public');
                $validated['path'] = $path;

            } else {
                // Validasi untuk video (link video)
                $validated = $request->validate([
                    'matapelajaran_id' => 'required',
                    'semester' => 'required',
                    'type' => 'required',
                    'judul_materi' => 'required',
                    'deskripsi' => 'required',
                    'path' => 'required|string', // path link video
                    'tahun_ajaran' => 'required',
                ]);
            }

            $validated['tanggal'] = $tanggal;

            $this->param->store($validated);

            Alert::success("Berhasil", "Data Berhasil di simpan.");
            return redirect()->route("materi");

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errors = $e->validator->errors()->all();
                Alert::error("Validasi Gagal", implode("\n", $errors));
            } else {
                Alert::error("Terjadi Kesalahan", $e->getMessage());
            }
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
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        $matpel = MataPelajaran::where('guru_nip', Auth::user()->guru->nip)->get();
        $materi = $this->param->find($id);
        return view("pages.role_guru.materi.edit", compact(["tahunAjaran", "matpel", "materi"]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $tanggal = $request->input('tanggal');
            $guruNip = $request->user()->guru->nip;

            $materi = $this->param->find($id);

            if (!$materi) {
                Alert::error("Tidak ditemukan", "Data materi tidak ditemukan.");
                return redirect()->route("materi");
            }

            if ($request->type == "buku") {
                $validated = $request->validate([
                    'matapelajaran_id' => 'required',
                    'semester' => 'required',
                    'type' => 'required',
                    'judul_materi' => 'required',
                    'deskripsi' => 'required',
                    'path' => 'nullable|file|mimes:pdf|max:5120', // Boleh kosong saat update
                    'tahun_ajaran' => 'required',
                ]);

                // Jika ada file baru diunggah
                if ($request->hasFile('path')) {
                    // Hapus file lama jika ada
                    if ($materi->path && Storage::disk('public')->exists($materi->path)) {
                        Storage::disk('public')->delete($materi->path);
                    }

                    $judul = Str::slug(Str::limit($request->judul_materi, 50));
                    $ext = $request->file('path')->getClientOriginalExtension();
                    $namaFile = "{$guruNip}_{$tanggal}_{$judul}.{$ext}";

                    $path = $request->file('path')->storeAs('materi', $namaFile, 'public');
                    $validated['path'] = $path;
                } else {
                    $validated['path'] = $materi->path;
                }

            } else {
                $validated = $request->validate([
                    'matapelajaran_id' => 'required',
                    'semester' => 'required',
                    'type' => 'required',
                    'judul_materi' => 'required',
                    'deskripsi' => 'required',
                    'path' => 'required|string', // Link video
                    'tahun_ajaran' => 'required',
                ]);
            }

            $validated['tanggal'] = $tanggal;

            $this->param->update($validated, $id);

            Alert::success("Berhasil", "Data berhasil diperbarui.");
            return redirect()->route("materi");

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errors = $e->validator->errors()->all();
                Alert::error("Validasi Gagal", implode("\n", $errors));
            } else {
                Alert::error("Terjadi Kesalahan", $e->getMessage());
            }
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
            $materi = $this->param->find($request->formid);

            if ($materi->type === 'buku' && $materi->path) {
                // Hapus file PDF dari storage/public/materi
                Storage::disk('public')->delete($materi->path);
            }

            $this->param->destroy($materi->id);
            Alert::success("Berhasil", "Data berhasil dihapus.");
            return redirect()->route("materi"); // sesuaikan dengan route kamu
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back();
        }
    }

}
