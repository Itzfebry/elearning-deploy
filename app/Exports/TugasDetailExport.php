<?php

namespace App\Exports;

use App\Models\SubmitTugas;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TugasDetailExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $selectedKelas;
    protected $selectedMatpel;

    public function __construct($selectedKelas = null, $selectedMatpel = null)
    {
        $this->selectedKelas = $selectedKelas;
        $this->selectedMatpel = $selectedMatpel;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $guruNip = Auth::user()->guru->nip;
        
        $query = SubmitTugas::with(['siswa', 'tugas.mataPelajaran'])
            ->whereHas('tugas', function ($q) use ($guruNip) {
                $q->where('guru_nip', $guruNip);
            });
        
        if ($this->selectedKelas) {
            $query->whereHas('tugas', function ($q) {
                $q->where('kelas', $this->selectedKelas);
            });
        }
        
        if ($this->selectedMatpel) {
            $query->whereHas('tugas', function ($q) {
                $q->where('matapelajaran_id', $this->selectedMatpel);
            });
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
            'Nama Siswa',
            'NISN',
            'Tanggal Submit',
            'Nilai',
            'Status Nilai',
            'Tenggat Waktu',
            'Status Tenggat',
            'Deskripsi Tugas'
        ];
    }

    public function map($submitTugas): array
    {
        static $no = 1;
        
        $tugas = $submitTugas->tugas;
        $siswa = $submitTugas->siswa;
        
        $statusNilai = $submitTugas->nilai !== null ? 'Sudah Dinilai' : 'Belum Dinilai';
        $statusTenggat = \Carbon\Carbon::parse($tugas->tenggat)->isPast() ? 'Lewat Tenggat' : 'Masih Aktif';
        
        return [
            $no++,
            $tugas->nama,
            $tugas->mataPelajaran->nama ?? '-',
            $tugas->kelas,
            $siswa->nama ?? 'Siswa tidak ditemukan',
            $submitTugas->nisn,
            \Carbon\Carbon::parse($submitTugas->tanggal)->format('d/m/Y H:i'),
            $submitTugas->nilai ?? '-',
            $statusNilai,
            \Carbon\Carbon::parse($tugas->tenggat)->format('d/m/Y'),
            $statusTenggat,
            $tugas->deskripsi ?? '-'
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
