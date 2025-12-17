<?php

namespace App\Traits;

trait HasCriteriaLabels
{
    /**
     * Helper: Get human-readable labels for dynamic columns.
     */
    public function getReadableLabels()
    {
        return [
            'Prestasi' => [
                'juara' => 'Juara',
                'jumlah_prestasi' => 'Jumlah Prestasi', // Count logic
                'jenis_prestasi' => 'Jenis Prestasi',
                'tingkat_prestasi' => 'Tingkat Prestasi',
                'tahun' => 'Tahun',
                'status_validasi' => 'Status Validasi',
            ],
            'Sanksi' => [
                'jenis_sanksi' => 'Jenis Sanksi',
                'jenis_hukuman' => 'Jenis Hukuman',
                'tanggal_sanksi' => 'Tanggal Sanksi',
                'keterangan' => 'Keterangan Pelanggaran',
            ],
            'Pengaduan' => [
                'judul' => 'Judul Pengaduan',
                'jenis_kasus' => 'Jenis Kasus',
                'tanggal_pengaduan' => 'Tanggal Pengaduan',
                'status' => 'Status Pengaduan',
            ],
            'Mahasiswa' => [
                'ipk' => 'IPK',
                'semester' => 'Semester',
                'tahun_masuk' => 'Angkatan (Tahun Masuk)',
            ],
        ];
    }
}
