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
                'nama_kegiatan' => 'Nama Kegiatan',
                'judul_prestasi' => 'Judul Prestasi',
                'jenis_prestasi' => 'Jenis Prestasi',
                'tingkat_prestasi' => 'Tingkat Prestasi',
                'tanggal' => 'Tanggal Prestasi',
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
