<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Storage;     
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

        // Ambil user admin yang sudah dibuat di UserSeeder (atau buat jika belum ada)
        $admin = User::where('username', 'admin')->first();

        if (!$admin) {
             $admin = User::create([
                'nama' => 'Admin Sistem',
                'username' => 'admin',
                'email' => 'admin@politala.ac.id',
                'password' => Hash::make('password_admin'), 
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
                'password' => Hash::make('password_pengurus'), 
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
            'judul_berita' => 'Prestasi',   
            'kategori' => 'prestasi',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Prestasi Membanggakan</strong> – Mahasiswa Politeknik Negeri Tanah Laut kembali menorehkan prestasi gemilang di kancah nasional. Dalam ajang kompetisi bergengsi tahun ini, tim perwakilan Politala berhasil membawa pulang gelar juara, membuktikan kualitas dan daya saing mahasiswa vokasi.</p><br><p>Prestasi ini tidak lepas dari kerja keras mahasiswa serta bimbingan intensif dari para dosen pendamping. Kompetisi yang diikuti oleh puluhan perguruan tinggi dari seluruh Indonesia ini menjadi ajang pembuktian kemampuan teknis dan soft skill mahasiswa.</p><br><p>Direktur Politala memberikan apresiasi setinggi-tingginya atas pencapaian ini. <em>“Kami sangat bangga dengan capaian mahasiswa kami. Ini adalah bukti bahwa pendidikan vokasi di Politala mampu menghasilkan SDM yang kompeten dan siap bersaing,”</em> ungkap beliau.</p><br><p>Semoga prestasi ini dapat menjadi motivasi bagi mahasiswa lain untuk terus berkarya dan berinovasi demi kemajuan bangsa dan almamater tercinta.</p>',
            'gambar_berita' => 'berita/4.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Berita::create([
            'id_user' => $pengurus->id_user,
            'judul_berita' => 'Prestasi',   
            'kategori' => 'prestasi',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Prestasi Membanggakan</strong> – Mahasiswa Politeknik Negeri Tanah Laut kembali menorehkan prestasi gemilang di kancah nasional. Dalam ajang kompetisi bergengsi tahun ini, tim perwakilan Politala berhasil membawa pulang gelar juara, membuktikan kualitas dan daya saing mahasiswa vokasi.</p><br><p>Prestasi ini tidak lepas dari kerja keras mahasiswa serta bimbingan intensif dari para dosen pendamping. Kompetisi yang diikuti oleh puluhan perguruan tinggi dari seluruh Indonesia ini menjadi ajang pembuktian kemampuan teknis dan soft skill mahasiswa.</p><br><p>Direktur Politala memberikan apresiasi setinggi-tingginya atas pencapaian ini. <em>“Kami sangat bangga dengan capaian mahasiswa kami. Ini adalah bukti bahwa pendidikan vokasi di Politala mampu menghasilkan SDM yang kompeten dan siap bersaing,”</em> ungkap beliau.</p><br><p>Semoga prestasi ini dapat menjadi motivasi bagi mahasiswa lain untuk terus berkarya dan berinovasi demi kemajuan bangsa dan almamater tercinta.</p>',
            'gambar_berita' => 'berita/5.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Berita::create([
            'id_user' => $pengurus->id_user,
            'judul_berita' => 'Prestasi',   
            'kategori' => 'prestasi',
            'status' => 'verified',
            'id_verifikator' => $admin->id_user,
            'isi_berita' => '<p><strong>Prestasi Membanggakan</strong> – Mahasiswa Politeknik Negeri Tanah Laut kembali menorehkan prestasi gemilang di kancah nasional. Dalam ajang kompetisi bergengsi tahun ini, tim perwakilan Politala berhasil membawa pulang gelar juara, membuktikan kualitas dan daya saing mahasiswa vokasi.</p><br><p>Prestasi ini tidak lepas dari kerja keras mahasiswa serta bimbingan intensif dari para dosen pendamping. Kompetisi yang diikuti oleh puluhan perguruan tinggi dari seluruh Indonesia ini menjadi ajang pembuktian kemampuan teknis dan soft skill mahasiswa.</p><br><p>Direktur Politala memberikan apresiasi setinggi-tingginya atas pencapaian ini. <em>“Kami sangat bangga dengan capaian mahasiswa kami. Ini adalah bukti bahwa pendidikan vokasi di Politala mampu menghasilkan SDM yang kompeten dan siap bersaing,”</em> ungkap beliau.</p><br><p>Semoga prestasi ini dapat menjadi motivasi bagi mahasiswa lain untuk terus berkarya dan berinovasi demi kemajuan bangsa dan almamater tercinta.</p>',
            'gambar_berita' => 'berita/6.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('BeritaSeeder selesai.');
    }
}