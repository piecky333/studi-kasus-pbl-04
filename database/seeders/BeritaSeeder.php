<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Import File facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Import Hash if needed (though we might just look up existing)

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Matikan foreign key check untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Berita::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- Logika untuk menyalin gambar asset ke storage ---
        $sourceDir = database_path('seeders/assets/berita');
        $destinationDir = storage_path('app/public/berita'); // Sesuaikan dengan config filesystems default (biasanya public disk rootnya storage/app/public)

        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        if (File::exists($sourceDir)) {
            $files = File::files($sourceDir);
            foreach ($files as $file) {
                File::copy($file->getPathname(), $destinationDir . '/' . $file->getFilename());
            }
            $this->command->info('Gambar berita berhasil disalin dari assets seeder.');
        } else {
            $this->command->warn('Direktori assets berita tidak ditemukan: ' . $sourceDir);
        }
        // -----------------------------------------------------

        // Ambil user admin yang sudah dibuat di UserSeeder (atau buat jika belum ada)
        $admin = User::where('username', 'admin')->first();

        if (!$admin) {
             $admin = User::create([
                'nama' => 'Admin Sistem',
                'username' => 'admin',
                'email' => 'admin@politala.ac.id',
                'password' => 'password_admin', 
                'role' => 'admin',
            ]);
        }

        // Ambil user pengurus (atau buat jika belum ada)
        $pengurus = User::where('username', 'pengurus')->first();
        if (!$pengurus) {
            $pengurus = User::create([
                'nama' => 'Pengurus Ormawa',
                'username' => 'pengurus',
                'email' => 'pengurus@politala.ac.id',
                'password' => 'password_pengurus', 
                'role' => 'pengurus',
            ]);
        }

        // Membuat 3 data berita kategori 'kegiatan'
        $this->command->info('Membuat 3 data kegiatan...');
        Berita::create([
            'id_user' => $pengurus->id_user, // Created by Pengurus
            'judul_berita' => 'Politala Bersama Pemkab Tanah Laut Luncurkan Aplikasi Amang Tani dan Acil Puskes pada Rapat Paripurna DPRD dalam rangka Hari Jadi ke-60 Tanah Laut.',
            'kategori' => 'kegiatan',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user, // Verified by Admin
            'isi_berita' => '<p><strong>Tanah Laut</strong> – Pada Rapat Paripurna DPRD Kabupaten Tanah Laut dalam rangka Hari Jadi Kabupaten Tanah Laut ke-60, Direktur Politeknik Negeri Tanah Laut (Politala) Dr. Ir. Meldayanoor, S.Hut., M.S bersama Sukma Firdaus, S.Si., M.T menghadiri undangan resmi pemerintah daerah sekaligus memperkenalkan dua inovasi digital berbasis kecerdasan buatan (AI) hasil kerja sama Politala dan Pemkab Tanah Laut: <strong>Amang Tani</strong> dan <strong>Acil Puskes</strong>.</p><br><p>Rapat paripurna ini berlangsung khidmat dengan kehadiran Bupati Tanah Laut H. Rahmat Trianto dan Wakil Bupati Tanah Laut H. M. Zazuli, Ketua dan Wakil Ketua DPRD, serta seluruh anggota DPRD Kabupaten Tanah Laut. Selain itu, jajaran kepala SKPD di lingkungan Pemerintah Kabupaten Tanah Laut turut hadir bersama para pimpinan Kepolisian dan TNI, serta sejumlah tokoh-tokoh penting Tanah Laut, mencerminkan dukungan luas dari berbagai unsur pemerintahan, keamanan, dan masyarakat terhadap agenda pembangunan daerah.</p><br><p>Peluncuran dua aplikasi ini menjadi salah satu momen penting dalam perayaan HUT ke-60, menegaskan bahwa Tanah Laut tidak hanya merayakan perjalanan sejarahnya, tetapi juga menapaki langkah baru menuju transformasi digital. Aplikasi ini lahir dari persoalan nyata yang dihadapi masyarakat—pertanian dengan tantangan kompleksitasnya, serta kesehatan yang menyangkut kualitas hidup setiap warga.</p><br><p><strong>Amang Tani</strong> berangkat dari urgensi memperkuat sektor pertanian yang menjadi tulang punggung perekonomian daerah. Pertanian menyentuh ketahanan pangan dan masa depan ekonomi, sehingga diperlukan “jembatan teknologi” yang membuat petani mampu mengakses informasi cepat, tepat, dan berbasis kondisi lokal. Visi agrotechnology Politala berpadu dengan komitmen Pemkab Tanah Laut untuk melahirkan asisten digital yang dekat dengan keseharian petani.</p><br><p>Sementara itu <strong>Acil Puskes</strong> muncul dari kerisauan banyaknya masalah kesehatan masyarakat yang bermula dari minimnya pemahaman dan akses informasi yang ramah. Terinspirasi dari karakter keibuan dalam budaya Banjar, “Acil Puskes” dirancang sebagai teman bicara digital yang mampu menyampaikan informasi kesehatan secara sederhana, tenang, dan terpercaya.</p><br><p>Di akhir agenda peluncuran aplikasi, Direktur Politala menyampaikan bahwa kehadiran Amang Tani dan Acil Puskes merupakan komitmen nyata Politala dalam mendukung pembangunan daerah melalui inovasi yang relevan dan berdampak langsung bagi masyarakat Tanah Laut. <em>“Kami berharap inovasi ini menjadi pemicu akselerasi digital dan membawa peningkatan nyata bagi kesejahteraan masyarakat Tanah Laut.”</em> ujar Direktur Politala.</p>',
            'gambar_berita' => 'berita/1.jpeg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Berita::create([
            'id_user' => $pengurus->id_user,
            'judul_berita' => 'Politala dan BNN Kabupaten Tanah Laut Resmi Tandatangani Kerja Sama Pencegahan Penyalahgunaan Narkotika',
            'kategori' => 'kegiatan',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Politala</strong> – Politeknik Negeri Tanah Laut (Politala) bersama Badan Narkotika Nasional (BNN) Kabupaten Tanah Laut resmi menandatangani Perjanjian Kerja Sama terkait Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika melalui Tridharma Perguruan Tinggi Tahun 2025–2028. Penandatanganan berlangsung di Aula Gedung Kuliah Terpadu Politala pada Jumat (28/11).</p><br><p>Kegiatan ini dihadiri oleh Direktur Politala Dr. Ir. Meldayanoor, S.Hut., M.S, beserta seluruh jajaran manajemen Politala. Hadir pula pimpinan BNN Kabupaten Tanah Laut Ferey Hidayat, R.SiK., M.H, serta pimpinan Badan Kesatuan Bangsa dan Politik (Kesbangpol) Kabupaten Tanah Laut.</p><br><p>Sambutan pertama disampaikan oleh Kepala BNN Kabupaten Tanah Laut, Ferey Hidayat, R.SiK., M.H, yang menyampaikan apresiasi tinggi kepada Politala atas terjalinnya kerja sama strategis ini. Ia menegaskan bahwa kolaborasi ini menjadi bukti nyata bahwa Politala turut mendukung upaya pemberantasan penyalahgunaan narkotika, khususnya di lingkungan kampus. <em>“Kerja sama ini sangat penting dalam menjalankan tugas pokok BNN, yaitu melakukan pencegahan, pemberantasan, dan rehabilitasi penyalahgunaan narkotika. Kami mengapresiasi komitmen Politala yang secara aktif mengambil bagian dalam memerangi bahaya narkotika,”</em> ujarnya.</p><br><p>Dalam sambutan berikutnya, Direktur Politala Dr. Ir. Meldayanoor, S.Hut., M.S menyampaikan bahwa Politala sangat mendukung seluruh upaya pencegahan dan pemberantasan narkotika. Beliau menegaskan bahwa dampak penyalahgunaan narkotika tidak hanya membahayakan kesehatan, tetapi juga merusak kehidupan sosial, masa depan, dan karakter generasi muda.</p><br><p><em>“Politala akan terus memperkuat edukasi dan sosialisasi kepada seluruh sivitas akademika. Kami berharap kerja sama ini menciptakan lingkungan kampus yang aman, sehat, dan bebas narkotika. Ini adalah komitmen kami untuk menjaga kualitas sumber daya manusia yang unggul dan berkarakter,”</em> tegas beliau.</p><br><p>Sebagai bentuk komitmen nyata, kegiatan dilanjutkan dengan pelaksanaan tes urine kepada seluruh manajemen Politala. Langkah ini menjadi bagian dari upaya pencegahan penyalahgunaan narkotika sekaligus contoh bahwa pimpinan dan manajemen Politala siap menjadi teladan dalam membangun lingkungan kampus yang bersih dari narkoba.</p>',
            'gambar_berita' => 'berita/2.jpeg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Berita::create([
            'id_user' => $pengurus->id_user,
            'judul_berita' => 'Politala Laksanakan Program Desa Binaan 2025 di Kantor Desa Panggung Baru, Dorong Kemandirian dan Peningkatan Pendidikan Tinggi',
            'kategori' => 'kegiatan',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Desa Panggung Baru</strong> – Politeknik Negeri Tanah Laut (Politala) kembali mengadakan Program Desa Binaan 2025 yang bertempat di Kantor Desa Panggung Baru, 18 November 2025. Kegiatan ini merupakan bentuk nyata pelaksanaan Tri Dharma Perguruan Tinggi, khususnya dalam bidang pengabdian kepada masyarakat melalui penerapan teknologi terapan, inovasi, dan pendampingan kepada masyarakat desa.</p><br><p>Kegiatan ini mengusung tema <em>“Bersinergi dalam Inovasi dan Teknologi Berdampak untuk Mewujudkan Desa yang Mandiri, Tangguh, dan Berdaya Saing.”</em> Acara dihadiri oleh Ketua Pelaksana, perangkat desa, Direktur Politala, dosen, mahasiswa, dan warga setempat.</p><br><p>Ketua Pelaksana Program Desa Binaan, Ahmad Rusyadi Arrahimi, S.Kom., M.Kom, dalam sambutannya menekankan bahwa program ini merupakan wujud pelaksanaan salah satu dari Tri Dharma Perguruan Tinggi, yaitu pengabdian kepada masyarakat. <em>“Kegiatan ini merupakan implementasi Tri Dharma Perguruan Tinggi, khususnya pengabdian kepada masyarakat. Kami berharap rangkaian program yang kami bawa hari ini dapat memberikan manfaat nyata bagi warga Desa Panggung Baru,”</em> ujarnya.</p><br><p>Sambutan selanjutnya disampaikan oleh Muhammad Arifin, Kepala Desa Panggung Baru. Ia menyampaikan apresiasi tinggi atas terpilihnya desanya sebagai lokasi program desa binaan Politala tahun ini. <em>“Kami sangat bersyukur dan berterima kasih kepada Politala yang telah memilih Desa Panggung Baru sebagai desa binaan. Kami berharap kegiatan ini dapat memberikan manfaat maksimal bagi masyarakat,”</em> ungkapnya.</p><br><p>Direktur Politala, Dr. Ir. Meldayanoor, S.Hut., M.S, kemudian hadir memberikan sambutan sekaligus meresmikan kegiatan. Ia menegaskan pentingnya kolaborasi antara kampus dan masyarakat desa dalam mencapai kemandirian dan daya saing. <em>“Politeknik Negeri Tanah Laut hadir untuk menjembatani kebutuhan masyarakat akan teknologi, ilmu pengetahuan, dan pendampingan. Melalui program desa binaan ini, kami ingin berkontribusi langsung dalam meningkatkan kapasitas dan kemandirian masyarakat desa,”</em> terang Direktur Meldayanoor.</p><br><p>Program Desa Binaan 2025 menjadi langkah nyata Politala dalam mendukung program pembangunan desa berbasis teknologi dan partisipasi masyarakat, sekaligus memperluas akses pendidikan vokasi berkualitas bagi seluruh lapisan masyarakat desa.</p>',
            'gambar_berita' => 'berita/3.jpeg',
            'created_at' => now(),  
            'updated_at' => now(),
        ]);

        // Membuat 3 data berita kategori 'prestasi'
        $this->command->info('Membuat 3 data prestasi...');
        Berita::create([
            'id_user' => $pengurus->id_user,
            'judul_berita' => 'Srikandi Politala! Feby Widia Mecca Raih Juara 1 Putri Anggrek Tanah Laut 2025',   
            'kategori' => 'prestasi',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Prestasi Membanggakan</strong> – Selamat kepada <strong>Feby Widia Mecca</strong>, mahasiswi Program Studi D4 Teknologi Rekayasa Komputer Jaringan (TRKJJ) Politeknik Negeri Tanah Laut yang telah berhasil meraih gelar <strong>Juara 1 Putri Anggrek Tanah Laut Tahun 2025</strong>.</p><br><p>Pencapaian luar biasa ini tidak hanya menjadi kebanggaan bagi program studi, tetapi juga mengharumkan nama besar kampus Politala di kancah daerah. Prestasi ini membuktikan bahwa mahasiswa Politala tidak hanya unggul di bidang akademik dan teknologi, tetapi juga memiliki talenta luar biasa di bidang seni dan kebudayaan.</p><br><p>Direktur Politala menyampaikan apresiasi mendalam atas dedikasi dan kerja keras Feby. <em>“Semoga pencapaian ini menjadi inspirasi bagi mahasiswa lainnya untuk terus berprestasi, mengembangkan potensi diri, dan mengangkat nama baik kampus dalam setiap langkah. Teruslah bersinar dan berkarya untuk Tanah Laut dan Indonesia!”</em> ujar beliau.</p>',
            'gambar_berita' => 'berita/4.jpeg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Berita::create([
            'id_user' => $pengurus->id_user,
            'judul_berita' => 'Borong Medali! Alfin Sukses Raih 2 Emas dan 2 Perunggu di Cabor Bridge PORPROV XII Kalsel 2025',   
            'kategori' => 'prestasi',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Juara Sejati</strong> – Selamat dan sukses kepada <strong>Alfin</strong>, mahasiswa Program Studi D3 Teknologi Informasi Politala, atas pencapaian gemilang di ajang <strong>Pekan Olahraga Provinsi (PORPROV) XII Kalimantan Selatan 2025</strong> yang diselenggarakan di Kabupaten Tanah Laut.</p><br><p>Pada Cabang Olahraga Bridge, Alfin menunjukkan dominasinya dengan meraih total <strong>4 Medali</strong> sekaligus, yang terdiri dari:</p><br><p>🥇 <strong>2 MEDALI EMAS (JUARA 1)</strong><br>- Kategori Bridge Team Super Mix U-26<br>- Kategori Bridge Team Butler Putra</p><br><p>🥉 <strong>2 MEDALI PERUNGGU (JUARA 3)</strong><br>- Kategori Bridge Pasangan Super Mix U-26<br>- Kategori Bridge Team Putra Open</p><br><p>Prestasi ini merupakan hasil dari ketekunan, strategi yang matang, dan mental juara yang dimiliki Alfin. Politala sangat bangga memiliki mahasiswa yang mampu bersaing dan berprestasi tinggi di tingkat provinsi.</p>',
            'gambar_berita' => 'berita/5.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Berita::create([
            'id_user' => $pengurus->id_user,
            'judul_berita' => 'Emas untuk Tanah Laut! Muhammad Alfa Rizi Juara Hapkido di PORPROV XII Kalsel 2025',   
            'kategori' => 'prestasi',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Kabar Kebanggaan</strong> – Selamat kepada <strong>Muhammad Alfa Rizi</strong>, mahasiswa Program Studi D3 Teknologi Otomotif Politeknik Negeri Tanah Laut, yang berhasil mempersembahkan <strong>Medali Emas</strong> pada ajang bergengsi <strong>PORPROV XII Kalimantan Selatan 2025</strong>.</p><br><p>Bertanding di Cabang Olahraga Hapkido kelas <strong>Under 78 kg Putra</strong>, Alfa Rizi tampil impresif dan berhasil mengalahkan lawan-lawannya hingga menaiki podium tertinggi. Kemenangan ini adalah buah dari latihan keras, disiplin, dan semangat pantang menyerah.</p><br><p><em>“Dedikasi, kerja keras, dan semangat juara telah membuahkan hasil terbaik. Terima kasih Alfa Rizi, karena telah membawa nama baik kampus Politala dan daerah Tanah Laut di kancah olahraga provinsi,”</em> ungkap perwakilan kemahasiswaan Politala.</p>',
            'gambar_berita' => 'berita/6.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('BeritaSeeder selesai.');
    }
}