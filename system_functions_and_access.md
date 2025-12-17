# Dokumentasi Fungsi Sistem & Hak Akses

Berikut adalah daftar fitur dan fungsi sistem berdasarkan pembagian hak akses (Role), dikelompokkan agar lebih rapi.

---

## 📂 1. Free User (Publik / Tanpa Login)
*Akses terbuka untuk masyarakat umum atau pengunjung website.*

### 📁 Informasi & Berita
- **Beranda**: Halaman utama profil website.
- **Berita**: 
  - Melihat daftar berita terkini.
  - Membaca detail berita.
  - Mengirim komentar pada berita.
- **Prestasi**: 
  - Melihat daftar prestasi mahasiswa.
  - Melihat detail prestasi.
- **Divisi**: 
  - Melihat profil divisi organisasi.
  - Melihat detail divisi.

---

## 📂 2. Admin (Administrator)
*Akses penuh untuk pengelolaan sistem dan data.*

### 📁 Dashboard & Profil
- **Dashboard Admin**: Ringkasan statistik sistem.
- **Profil Saya**: Mengubah data diri admin.

### 📁 Manajemen Organisasi
- **Data Mahasiswa**: 
  - Import data mahasiswa (Excel).
  - Tambah, Edit, Hapus data mahasiswa.
- **Pengurus**: 
  - Kelola data pengurus himpunan.
- **Divisi**: 
  - Lihat daftar divisi (Read Only / Dikelola Pengurus).

### 📁 Manajemen Konten & Informasi
- **Berita**: 
  - Verifikasi berita (Terima/Tolak) dari User/Pengurus.
  - CRUD Berita (Buat, Edit, Hapus berita admin).
- **Prestasi**: 
  - Validasi prestasi mahasiswa.
  - Tambah prestasi manual.

### 📁 Akademik & Pelanggaran
- **Sanksi**: 
  - Kelola data pelanggaran dan sanksi mahasiswa.

### 📁 Layanan Pengaduan
- **Pengaduan Masuk**: 
  - Verifikasi pengaduan.
  - Balas/Beri Tanggapan terhadap pengaduan.
  - Hapus pengaduan tidak valid.

### 📁 SPK (Sistem Pendukung Keputusan) - Metode SAW
- **Keputusan**: Buat/Kelola sesi keputusan (mis: Pemilihan Mahasiswa Berprestasi 2024).
- **Kriteria**: Atur kriteria penilaian dan bobotnya (Normalisasi AHP).
- **Sub-Kriteria**: Atur opsi penilaian (mis: Sangat Baik, Baik, Cukup).
- **Alternatif**: Tentukan kandidat mahasiswa yang akan dinilai.
- **Penilaian**: Input nilai kandidat berdasarkan kriteria.
- **Hasil Akhir**: Lihat ranking otomatis metode SAW.

---

## 📂 3. Pengurus (BEM/HIMA)
*Akses khusus untuk anggota kepengurusan organisasi.*

### 📁 Organisasi Internal
- **Dashboard Pengurus**: Ringkasan internal.
- **Profil Divisi**: Mengelola halaman profil divisi masing-masing.
- **Jabatan**: Mengelola data jabatan internal.
- **Anggota Pengurus**: Mengelola data rekan pengurus.

### 📁 Publikasi
- **Berita**: Membuat dan mempublikasikan berita organisasi (Perlu verifikasi Admin).

---

## 📂 4. Mahasiswa
*Akses khusus untuk user dengan status mahasiswa aktif.*

### 📁 Akademik
- **Dashboard Mahasiswa**: Ringkasan data akademik pribadi.
- **Sertifikat**: 
  - Mengajukan sertifikat kegiatan.
  - Melihat status pengajuan sertifikat.

### 📁 Layanan
- **Pengaduan**: 
  - Membuat pengaduan baru.
  - Melihat riwayat & balasan pengaduan sendiri.
- **Prestasi**: (terintegrasi dengan pengajuan sertifikat/prestasi).

---

## 📂 5. User (Umum)
*Akses untuk pengguna terdaftar non-mahasiswa (mis: Alumni, Dosen Tamu).*

### 📁 Aktivitas
- **Berita**: Menulis berita (UGC - User Generated Content, perlu verifikasi).
- **Pengaduan**: Melaporkan kejadian atau saran kepada sistem.
- **Dashboard User**: Ringkasan aktivitas pribadi.
