<?php

namespace App\Http\Controllers\RoleGuru;

use App\Exports\TugasDetailExport;
use App\Exports\TugasExport;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\SubmitTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class RekapTugasController extends Controller
{
    public function index(Request $request)
    {
        $guruNip = Auth::user()->guru->nip;
        
        // Get mata pelajaran yang diajar oleh guru
        $mataPelajaran = MataPelajaran::where('guru_nip', $guruNip)->get();
        
        // Get kelas yang diajar oleh guru
        $kelasList = Kelas::whereIn('nama', $mataPelajaran->pluck('kelas'))->get();
        
        // Filter parameters
        $selectedKelas = $request->get('kelas');
        $selectedMatpel = $request->get('matpel');
        $search = $request->get('search');
        
        // Query untuk mendapatkan tugas yang dibuat oleh guru
        $query = Tugas::with(['mataPelajaran', 'submitTugas.siswa'])
            ->where('guru_nip', $guruNip);
        
        // Apply filters
        if ($selectedKelas) {
            $query->where('kelas', $selectedKelas);
        }
        
        if ($selectedMatpel) {
            $query->where('matapelajaran_id', $selectedMatpel);
        }
        
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }
        
        $tugas = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Ensure submitTugas is always a collection
        $tugas->getCollection()->transform(function ($t) {
            if (!$t->submitTugas) {
                $t->setRelation('submitTugas', collect());
            }
            return $t;
        });
        
        return view('pages.role_guru.rekap_tugas.index', compact(
            'tugas', 
            'kelasList', 
            'mataPelajaran', 
            'selectedKelas', 
            'selectedMatpel', 
            'search'
        ));
    }
    
    public function detail($id)
    {
        $tugas = Tugas::with(['mataPelajaran', 'submitTugas.siswa'])
            ->where('guru_nip', Auth::user()->guru->nip)
            ->findOrFail($id);
            
        $submitTugas = SubmitTugas::with('siswa')
            ->where('tugas_id', $id)
            ->get();
            
        return view('pages.role_guru.rekap_tugas.detail', compact('tugas', 'submitTugas'));
    }
    
    public function updateNilai(Request $request, $id)
    {
        try {
            $request->validate([
                'nilai' => 'required|numeric|min:0|max:100'
            ]);
            
            $submitTugas = SubmitTugas::findOrFail($id);
            
            // Check if the task belongs to the teacher
            $tugas = Tugas::where('id', $submitTugas->tugas_id)
                ->where('guru_nip', Auth::user()->guru->nip)
                ->first();
                
            if (!$tugas) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk menilai tugas ini.'
                    ]);
                }
                Alert::error("Error", "Anda tidak memiliki akses untuk menilai tugas ini.");
                return back();
            }
            
            $submitTugas->update([
                'nilai' => $request->nilai
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nilai berhasil diperbarui.'
                ]);
            }
            
            Alert::success("Berhasil", "Nilai berhasil diperbarui.");
            return back();
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
            
            Alert::error("Error", "Terjadi kesalahan: " . $e->getMessage());
            return back();
        }
    }
    
    public function export(Request $request)
    {
        $selectedKelas = $request->get('kelas');
        $selectedMatpel = $request->get('matpel');
        
        $filename = 'rekap_tugas_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new TugasExport($selectedKelas, $selectedMatpel), 
            $filename
        );
    }
    
    public function exportDetail(Request $request)
    {
        $selectedKelas = $request->get('kelas');
        $selectedMatpel = $request->get('matpel');
        
        $filename = 'detail_tugas_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new TugasDetailExport($selectedKelas, $selectedMatpel), 
            $filename
        );
    }
} 