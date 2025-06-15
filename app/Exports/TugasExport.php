<?php

namespace App\Exports;

use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TugasExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $selectedKelas;
    protected $selectedMatpel;

    public function __construct($selectedKelas = null, $selectedMatpel = null)
    {
        $this->selectedKelas = $selectedKelas;
        $this->selectedMatpel = $selectedMatpel;
    }

    public function collection()
    {
        $guruNip = Auth::user()->guru->nip;
        
        $query = Tugas::with(['mataPelajaran', 'submitTugas.siswa'])
            ->where('guru_nip', $guruNip);
        
        if ($this->selectedKelas) {
            $query->where('kelas', $this->selectedKelas);
        }
        
        if ($this->selectedMatpel) {
            $query->where('matapelajaran_id', $this->selectedMatpel);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Tugas',
            'Mata Pelajaran',
            'Kelas',
            'Tanggal Dibuat',
            'Tenggat Waktu',
            'Jumlah Submit',
            'Sudah Dinilai',
            'Belum Dinilai',
            'Rata-rata Nilai',
            'Status Tenggat'
        ];
    }

    public function map($tugas): array
    {
        static $no = 1;
        
        $submitTugas = $tugas->submitTugas ?: collect();
        $sudahDinilai = $submitTugas->whereNotNull('nilai')->count();
        $totalSubmit = $submitTugas->count();
        $belumDinilai = $totalSubmit - $sudahDinilai;
        $rataRataNilai = $submitTugas->whereNotNull('nilai')->count() > 0 
            ? number_format($submitTugas->whereNotNull('nilai')->avg('nilai'), 1) 
            : '-';
        
        $statusTenggat = \Carbon\Carbon::parse($tugas->tenggat)->isPast() ? 'Lewat Tenggat' : 'Masih Aktif';
        
        return [
            $no++,
            $tugas->nama,
            $tugas->mataPelajaran->nama ?? '-',
            $tugas->kelas,
            \Carbon\Carbon::parse($tugas->tanggal)->format('d/m/Y'),
            \Carbon\Carbon::parse($tugas->tenggat)->format('d/m/Y'),
            $totalSubmit,
            $sudahDinilai,
            $belumDinilai,
            $rataRataNilai,
            $statusTenggat
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ]
        ];
    }
}
