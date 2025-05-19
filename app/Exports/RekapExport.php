<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapExport implements FromCollection, WithHeadings
{
    protected $rekap;

    public function __construct($rekap)
    {
        $this->rekap = $rekap;
    }

    public function collection()
    {
        return collect($this->rekap)->map(function ($item) {
            return [
                $item['matapelajaran'],
                $item['judul_quiz'],
                $item['nama_siswa'],
                $item['tahun_ajaran'],
                $item['kelas'],
                $item['total_skor'],
                $item['kkm'],
                $item['persentase'],
                $item['persentase'] >= $item['kkm'] ? 'Lulus' : 'Tidak Lulus',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Mata Pelajaran',
            'Judul Quiz',
            'Nama Siswa',
            'Tahun Ajaran',
            'Kelas',
            'Total Skor',
            'KKM',
            'Nilai (%)',
            'Status',
        ];
    }
}
