<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel pengaduan
        Schema::disableForeignKeyConstraints();
        DB::table('pengaduan')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil User Umum
        $user = User::where('username', 'user')->first();

        if ($user) {
            $pengaduans = [
                [
                    'id_user' => $user->id_user,
                    'judul' => 'Laporan Kerusakan AC di Ruang Kelas A201',
                    'jenis_kasus' => 'Fasilitas',
                    'deskripsi' => 'Selamat pagi, saya ingin melaporkan bahwa AC di ruang kelas A201 tidak berfungsi dengan baik sejak hari Senin kemarin. Udara di dalam kelas menjadi sangat panas dan pengap, sehingga mengganggu konsentrasi belajar mahasiswa saat perkuliahan berlangsung. Mohon kiranya dapat segera diperbaiki atau diservis. Terima kasih.',
                    'status' => 'Terkirim',
                    'created_at' => Carbon::now()->subDays(2),
                    'updated_at' => Carbon::now()->subDays(2),
                ],
                [
                    'id_user' => $user->id_user,
                    'judul' => 'Kehilangan Helm di Parkiran Gedung B',
                    'jenis_kasus' => 'Keamanan',
                    'deskripsi' => 'Saya ingin melaporkan kehilangan helm merk KYT warna hitam doff di area parkiran motor Gedung B pada hari Selasa, sekitar pukul 14.00 WITA. Saat saya hendak pulang kuliah, helm saya sudah tidak ada di spion motor. Saya sudah coba cari di sekitar tapi tidak ketemu. Mohon bantuan pihak keamanan untuk mengecek CCTV pada jam tersebut.',
                    'status' => 'Diproses',
                    'created_at' => Carbon::now()->subDays(5),
                    'updated_at' => Carbon::now()->subDays(4),
                ],
                [
                    'id_user' => $user->id_user,
                    'judul' => 'Keluhan Wi-Fi Wifi.id Sering Putus Nyambung',
                    'jenis_kasus' => 'Jaringan Internet',
                    'deskripsi' => 'Mohon maaf mengganggu, saya ingin menyampaikan keluhan mengenai koneksi Wi-Fi Wifi.id di area perpustakaan yang sangat tidak stabil dalam 3 hari terakhir. Sering putus nyambung (RTO) saat digunakan untuk mencari referensi jurnal. Padahal sinyalnya penuh. Mohon tim TI bisa mengecek access point di sana. Terima kasih.',
                    'status' => 'Selesai',
                    'created_at' => Carbon::now()->subWeek(),
                    'updated_at' => Carbon::now()->subDays(1),
                ],
                [
                    'id_user' => $user->id_user,
                    'judul' => 'Indikasi Pungutan Liar oleh Oknum',
                    'jenis_kasus' => 'Pelanggaran Etik',
                    'deskripsi' => 'Saya mendapatkan laporan dari beberapa teman mahasiswa baru bahwa ada oknum senior yang meminta uang iuran tidak resmi dengan dalih "uang kebersamaan" sebesar Rp 50.000 per orang. Kejadian ini terjadi di area kantin lama pada jam istirahat. Saya harap pihak kemahasiswaan bisa menelusuri hal ini agar tidak menjadi budaya buruk.',
                    'status' => 'Ditolak',
                    'created_at' => Carbon::now()->subDays(10),
                    'updated_at' => Carbon::now()->subDays(9),
                ],
                 [
                    'id_user' => $user->id_user,
                    'judul' => 'Lampu Penerangan Jalan Utama Mati',
                    'jenis_kasus' => 'Fasilitas',
                    'deskripsi' => 'Lampu penerangan jalan utama menuju gerbang keluar kampus mati total tadi malam. Area tersebut menjadi sangat gelap dan cukup rawan, terutama bagi mahasiswa yang pulang kuliah malam atau kegiatan organisasi. Mohon segera diganti bohlamnya demi keamanan bersama.',
                    'status' => 'Terkirim',
                    'created_at' => Carbon::now()->subHours(12),
                    'updated_at' => Carbon::now()->subHours(12),
                ],
            ];

            DB::table('pengaduan')->insert($pengaduans);
        }

        // Ambil Mahasiswa
        $mahasiswa = User::where('username', 'mahasiswa')->first();

        if ($mahasiswa) {
            $pengaduansMahasiswa = [
                [
                    'id_user' => $mahasiswa->id_user,
                    'judul' => 'Nilai Mata Kuliah Pemrograman Web Belum Keluar',
                    'jenis_kasus' => 'Akademik',
                    'deskripsi' => 'Selamat siang, saya ingin menanyakan perihal nilai mata kuliah Pemrograman Web (TI-202) semester ini. Padahal ujian akhir sudah dilaksanakan 2 minggu yang lalu dan teman-teman kelas lain sudah keluar nilainya. Mohon informasinya apakah ada kendala atau persyaratan yang belum saya penuhi. Terima kasih.',
                    'status' => 'Diproses',
                    'created_at' => Carbon::now()->subDays(3),
                    'updated_at' => Carbon::now()->subDays(2),
                ],
                [
                    'id_user' => $mahasiswa->id_user,
                    'judul' => 'Kran Air di Musholla Lantai 2 Bocor',
                    'jenis_kasus' => 'Fasilitas',
                    'deskripsi' => 'Melaporkan bahwa kran air untuk wudhu di Musholla laki-laki lantai 2 mengalami kebocoran yang cukup deras. Air terbuang percuma dan lantai menjadi licin sehingga membahayakan pengguna musholla. Mohon segera diperbaiki agar tidak boros air.',
                    'status' => 'Terkirim',
                    'created_at' => Carbon::now()->subHours(5),
                    'updated_at' => Carbon::now()->subHours(5),
                ],
                [
                    'id_user' => $mahasiswa->id_user,
                    'judul' => 'Bentrokan Jadwal Kuliah dan Praktikum',
                    'jenis_kasus' => 'Akademik',
                    'deskripsi' => 'Saya mahasiswa semester 3, ingin melaporkan adanya bentrokan jadwal antara MK Jaringan Komputer (Teori) dengan Praktikum Basis Data pada hari Kamis pukul 10.00. Hal ini membuat saya tidak bisa mengikuti salah satunya. Mohon solusi dari bagian akademik apakah bisa pindah kelas atau ada kebijakan lain.',
                    'status' => 'Selesai',
                    'created_at' => Carbon::now()->subWeeks(2),
                    'updated_at' => Carbon::now()->subDays(10),
                ],
                [
                    'id_user' => $mahasiswa->id_user,
                    'judul' => 'Kehilangan Flashdisk di Lab Komputer 1',
                    'jenis_kasus' => 'Keamanan',
                    'deskripsi' => 'Telah hilang Flashdisk Sandisk 32GB warna merah hitam di Lab Komputer 1 pada saat praktikum Algoritma kemarin sore. Di dalamnya terdapat file-file tugas penting. Bagi yang menemukan mohon hubungi saya atau serahkan ke laboran. Terima kasih.',
                    'status' => 'Ditolak',
                    'created_at' => Carbon::now()->subDays(1),
                    'updated_at' => Carbon::now()->subDays(1),
                ],
            ];

            DB::table('pengaduan')->insert($pengaduansMahasiswa);
        }
    }
}
