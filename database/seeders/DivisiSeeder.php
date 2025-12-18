<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\admin\divisi;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        Schema::disableForeignKeyConstraints();
        divisi::truncate();
        Schema::enableForeignKeyConstraints();

    $divisiData = [
            [
                'nama_divisi' => 'DIVISI TI-COMP',
                'isi_divisi' => '
                    <p><strong>Fokus Utama:</strong> Pembinaan prestasi, kompetisi, minat–bakat, kreativitas, dan pengembangan potensi mahasiswa TI.</p>
                    <br>
                    <strong>Tugas Divisi:</strong>
                    <ul class="list-disc pl-5">
                        <li>Mengidentifikasi dan memetakan potensi mahasiswa.</li>
                        <li>Memantau dan menyebarkan informasi lomba/kompetisi.</li>
                        <li>Melaksanakan coaching dan pendampingan lomba.</li>
                        <li>Menyusun database prestasi dan sertifikat mahasiswa.</li>
                        <li>Menyelenggarakan kegiatan minat–bakat (seni, olahraga, e-sport).</li>
                        <li>Membuat event kreatif internal.</li>
                    </ul>
                    <br>
                    <strong>Sub-Bidang:</strong>
                    <ul class="list-disc pl-5">
                        <li>Prestasi & Kompetisi (SPK): Talent mapping, info lomba, pendampingan, database prestasi.</li>
                        <li>Minat, Bakat & Kreativitas (MBK): Kegiatan seni, olahraga, e-sport, hobi, fun event.</li>
                    </ul>
                    <br>
                    <strong>Tugas Koordinator:</strong>
                    <ul class="list-disc pl-5">
                        <li>Menyusun kalender kompetisi dan event minat–bakat.</li>
                        <li>Mengatur pelatih/mentor kompetisi.</li>
                        <li>Mengawasi SPK & MBK.</li>
                        <li>Memastikan database prestasi selalu update.</li>
                    </ul>
                    <br>
                    <strong>Tugas Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Mencari dan menginformasikan lomba terbaru.</li>
                        <li>Mendata peserta dan perkembangan latihan.</li>
                        <li>Menjadi panitia teknis kegiatan minat–bakat.</li>
                        <li>Mendokumentasikan prestasi mahasiswa.</li>
                    </ul>
                    <br>
                    <strong>Kriteria Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Aktif pada lomba/kompetisi TI atau bidang kreatif.</li>
                        <li>Terbiasa bekerja cepat dengan tenggat waktu ketat.</li>
                        <li>Memiliki ide segar dan inovatif untuk pengembangan bakat mahasiswa.</li>
                        <li>Mampu bekerja dalam tim kreatif dan menerima revisi.</li>
                    </ul>
                ',
            ],
            [
                'nama_divisi' => 'DIVISI MPR (Media & Public Relations)',
                'isi_divisi' => '
                    <p><strong>Fokus Utama:</strong> Branding, publikasi, media sosial, dokumentasi, dan event organizer HIMA TI.</p>
                    <br>
                    <strong>Tugas Divisi:</strong>
                    <ul class="list-disc pl-5">
                        <li>Mengelola seluruh media sosial HIMA TI.</li>
                        <li>Membuat desain konten (poster, reels, branding visual).</li>
                        <li>Menjadi dokumentasi foto/video semua kegiatan.</li>
                        <li>Menangani media partner dan publikasi resmi.</li>
                        <li>Menjadi panitia inti event HIMA TI (EO).</li>
                        <li>Menyusun proposal event bersama divisi lain.</li>
                    </ul>
                    <br>
                    <strong>Sub-Bidang:</strong>
                    <ul class="list-disc pl-5">
                        <li>Desain & Kreatif</li>
                        <li>Dokumentasi</li>
                        <li>Event Organizer (EO)</li>
                        <li>Social Media & Branding</li>
                    </ul>
                    <br>
                    <strong>Tugas Koordinator:</strong>
                    <ul class="list-disc pl-5">
                        <li>Menyusun branding guideline organisasi.</li>
                        <li>Mengatur jadwal posting dan kalender konten.</li>
                        <li>Menentukan PIC dokumentasi tiap event.</li>
                        <li>Memimpin rapat EO dan koordinasi dengan divisi lain.</li>
                    </ul>
                    <br>
                    <strong>Tugas Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Mendesain poster dan konten visual.</li>
                        <li>Mengambil foto dan video kegiatan.</li>
                        <li>Mengedit konten multimedia.</li>
                        <li>Menjadi panitia teknis event (MC, LO, front desk, dekorasi).</li>
                    </ul>
                    <br>
                    <strong>Kriteria Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Komunikatif dan percaya diri.</li>
                        <li>Kreatif dalam desain, seni, atau produksi konten.</li>
                        <li>Mampu mengoperasikan software kreatif (AI, PS, PR, Figma, Canva).</li>
                        <li>Familiar dengan media sosial dan teknik branding.</li>
                        <li>Punya kemampuan dokumentasi dan publikasi yang baik.</li>
                        <li>Terorganisir dalam mengelola rundown dan koordinasi acara.</li>
                    </ul>
                ',
            ],
            [
                'nama_divisi' => 'DIVISI E-COM (Ekonomi Kreatif & Kemitraan)',
                'isi_divisi' => '
                    <p><strong>Fokus Utama:</strong> Kewirausahaan HIMA, kerjasama industri/komunitas, alumni, dan sponsor.</p>
                    <br>
                    <strong>Tugas Divisi:</strong>
                    <ul class="list-disc pl-5">
                        <li>Mengembangkan produk KWU (merchandise).</li>
                        <li>Mengelola sistem penjualan dan keuangan KWU.</li>
                        <li>Mencari sponsorship untuk kegiatan HIMA TI.</li>
                        <li>Bekerjasama dengan perusahaan/instansi/komunitas TI.</li>
                        <li>Membina hubungan dengan alumni TI.</li>
                        <li>Membuat program mentoring karier bersama alumni.</li>
                    </ul>
                    <br>
                    <strong>Sub-Bidang:</strong>
                    <ul class="list-disc pl-5">
                        <li>Kewirausahaan & Bisnis</li>
                        <li>Sponsorship</li>
                        <li>Partnership (Industri & Komunitas)</li>
                        <li>Alumni Affairs</li>
                    </ul>
                    <br>
                    <strong>Tugas Koordinator:</strong>
                    <ul class="list-disc pl-5">
                        <li>Menentukan strategi bisnis dan produk KWU.</li>
                        <li>Menyusun proposal sponsorship & negosiasi mitra.</li>
                        <li>Mengatur program alumni dan database eksternal.</li>
                        <li>Memimpin koordinasi dengan industri/komunitas.</li>
                    </ul>
                    <br>
                    <strong>Tugas Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Mendesain dan mengelola merchandise KWU.</li>
                        <li>Melakukan follow-up sponsor dan mitra.</li>
                        <li>Mendata alumni dan perusahaan partner.</li>
                        <li>Membantu kegiatan kolaborasi dan kunjungan industri.</li>
                    </ul>
                    <br>
                    <strong>Kriteria Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Memiliki jiwa bisnis dan peka terhadap peluang pendanaan.</li>
                        <li>Komunikatif dan berani bernegosiasi.</li>
                        <li>Tertarik pada manajemen bisnis dan marketing.</li>
                        <li>Mampu menyusun anggaran dan laporan keuangan sederhana.</li>
                        <li>Profesional dalam menjaga hubungan dengan mitra.</li>
                    </ul>
                ',
            ],
            [
                'nama_divisi' => 'DIVISI HAM (Human Administration & Manajemen)',
                'isi_divisi' => '
                    <p><strong>Fokus Utama:</strong> Rekrutmen pengurus, kaderisasi, administrasi organisasi, arsip digital, dan manajemen SDM.</p>
                    <br>
                    <strong>Tugas Divisi:</strong>
                    <ul class="list-disc pl-5">
                        <li>Menyusun open recruitment HIMA TI.</li>
                        <li>Mengadakan Pelatihan Dasar Kepemimpinan.</li>
                        <li>Evaluasi kinerja pengurus.</li>
                        <li>Mengelola surat-menyurat, agenda, notulensi.</li>
                        <li>Mengatur arsip digital dan inventaris.</li>
                        <li>Membantu sekretaris dalam laporan resmi.</li>
                    </ul>
                    <br>
                    <strong>Sub-Bidang:</strong>
                    <ul class="list-disc pl-5">
                        <li>Rekrutmen & Kaderisasi</li>
                        <li>Administrasi & Arsip Digital</li>
                        <li>Manajemen SDM Internal</li>
                    </ul>
                    <br>
                    <strong>Tugas Koordinator:</strong>
                    <ul class="list-disc pl-5">
                        <li>Membuat kurikulum kaderisasi dan asesmen anggota.</li>
                        <li>Mengawasi SOP administrasi.</li>
                        <li>Memastikan arsip digital berjalan dengan baik.</li>
                        <li>Mengatur evaluasi dan pembinaan pengurus.</li>
                    </ul>
                    <br>
                    <strong>Tugas Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Membuat surat dan notulensi rapat.</li>
                        <li>Mengarsipkan dokumen dan inventaris.</li>
                        <li>Membantu teknis rekrutmen dan pelatihan kader.</li>
                        <li>Menyusun laporan administrasi berkala.</li>
                    </ul>
                    <br>
                    <strong>Kriteria Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Teliti dan rapi dalam administrasi.</li>
                        <li>Mampu membuat surat, notulensi, dan laporan.</li>
                        <li>Tertarik pada pengembangan SDM.</li>
                        <li>Terstruktur dalam arsip digital.</li>
                        <li>Menguasai Notion dan Google Drive.</li>
                    </ul>
                ',
            ],
            [
                'nama_divisi' => 'DIVISI AKRIDA (Akademik, Riset & Pengabdian)',
                'isi_divisi' => '
                    <p><strong>Fokus Utama:</strong> Pengembangan akademik mahasiswa, riset teknologi, dan program pengabdian masyarakat.</p>
                    <br>
                    <strong>Tugas Divisi:</strong>
                    <ul class="list-disc pl-5">
                        <li>Merancang program mentoring mahasiswa baru.</li>
                        <li>Menyusun modul akademik dan kurikulum pendampingan.</li>
                        <li>Mengelola kegiatan riset internal seperti workshop atau kompetisi ilmiah.</li>
                        <li>Mengadakan inovasi teknologi melalui proyek RnD.</li>
                        <li>Melaksanakan kegiatan pengabdian masyarakat berbasis teknologi.</li>
                        <li>Menyusun dokumentasi ilmiah dan laporan kegiatan.</li>
                    </ul>
                    <br>
                    <strong>Sub-Bidang:</strong>
                    <ul class="list-disc pl-5">
                        <li>Akademik & Mentoring</li>
                        <li>Riset & Inovasi Teknologi</li>
                        <li>Pengabdian Masyarakat</li>
                    </ul>
                    <br>
                    <strong>Tugas Koordinator:</strong>
                    <ul class="list-disc pl-5">
                        <li>Menyusun kurikulum mentoring dan jadwal pelaksanaan.</li>
                        <li>Mengatur roadmap riset dan inovasi teknologi.</li>
                        <li>Mengawasi kegiatan pengabdian masyarakat.</li>
                        <li>Memastikan kolaborasi dengan dosen dan mitra eksternal.</li>
                        <li>Melakukan evaluasi program akademik dan riset.</li>
                    </ul>
                    <br>
                    <strong>Tugas Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Melaksanakan program mentoring.</li>
                        <li>Membantu pengelolaan riset dan inovasi.</li>
                        <li>Menyiapkan kebutuhan teknis pengabdian masyarakat.</li>
                        <li>Menyusun dokumentasi dan laporan kegiatan.</li>
                        <li>Membantu pelaksanaan workshop dan kelas akademik.</li>
                    </ul>
                    <br>
                    <strong>Kriteria Anggota:</strong>
                    <ul class="list-disc pl-5">
                        <li>Minat kuat di bidang akademik dan riset.</li>
                        <li>Mampu mengajar atau mendampingi mahasiswa baru.</li>
                        <li>Terorganisir dalam menyusun modul dan program.</li>
                        <li>Tertarik pada penelitian mini dan penulisan ilmiah.</li>
                        <li>Peduli sosial dan siap ikut program pengabdian.</li>
                    </ul>
                ',
            ],
        ];

        // Ensure directory exists in storage/app/public/divisi
        $storagePath = storage_path('app/public/divisi');
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        foreach ($divisiData as $index => $data) {
            // Download dummy image using Http client
            $imageUrl = 'https://ui-avatars.com/api/?name=' . urlencode($data['nama_divisi']) . '&background=random&size=500&color=fff';
            $imageName = 'divisi-' . ($index + 1) . '.png';
            
            try {
                $response = \Illuminate\Support\Facades\Http::get($imageUrl);
                
                if ($response->successful()) {
                    File::put($storagePath . '/' . $imageName, $response->body());
                    $data['foto_divisi'] = 'divisi/' . $imageName; 
                } else {
                    $data['foto_divisi'] = null;
                    $this->command->warn("Gagal mendownload gambar untuk " . $data['nama_divisi']);
                }
            } catch (\Exception $e) {
                $data['foto_divisi'] = null;
                $this->command->error("Error saat mendownload gambar: " . $e->getMessage());
            }

            divisi::create($data);
        }
    }
}
