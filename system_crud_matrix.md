# Matriks Fungsi & Operasi Sistem (CRUD)

Dokumen ini menjelaskan secara detail operasi apa saja yang dapat dilakukan oleh setiap peran (Role) pada setiap modul sistem.

---

## 🟢 1. Role: ADMIN
*Pemegang kendali penuh atas data dan validasi sistem.*

### 🛠️ Modul: Data Mahasiswa
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Lihat Daftar** | Melihat seluruh data mahasiswa terdaftar. | `Read` |
| **Detail** | Melihat biodata lengkap mahasiswa. | `Read` |
| **Tambah Manual** | Menambahkan data mahasiswa satu per satu. | `Create` |
| **Import Excel** | Menambahkan banyak data mahasiswa sekaligus via file Excel. | `Create` |
| **Ubah/Edit** | Mengoreksi data mahasiswa yang salah. | `Update` |
| **Hapus** | Menghapus data mahasiswa dari sistem. | `Delete` |

### 🛠️ Modul: Berita & Informasi
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Kelola Berita** | Tambah, Edit, dan Hapus berita buatan Admin. | `Full CRUD` |
| **Verifikasi** | Menyetujui berita dari User/Pengurus agar terbit. | `Update/Approve` |
| **Tolak** | Menolak berita dari User/Pengurus dengan alasan. | `Update/Reject` |

### 🛠️ Modul: Pelanggaran (Sanksi)
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Lihat Riwayat** | Melihat daftar pelanggaran mahasiswa. | `Read` |
| **Input Sanksi** | Mencatat pelanggaran baru dan hukuman. | `Create` |
| **Edit data** | Mengoreksi data sanksi yang salah input. | `Update` |
| **Hapus** | Menghapus data sanksi. | `Delete` |

### 🛠️ Modul: Pengaduan (Laporan)
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Monitoring** | Melihat semua pengaduan masuk. | `Read` |
| **Verifikasi** | Memvalidasi pengaduan agar status menjadi 'Diproses'. | `Update` |
| **Tanggapi** | Memberikan balasan/jawaban resmi admin. | `Create` |
| **Hapus Laporan** | Menghapus pengaduan spam atau tidak valid. | `Delete` |

### 🛠️ Modul: Prestasi
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Validasi** | Memverifikasi prestasi yang diajukan mahasiswa. | `Update` |
| **Input Manual** | Menambahkan prestasi mahasiswa secara manual. | `Create` |
| **Edit/Hapus** | Mengelola data prestasi. | `Update/Delete` |

### 🛠️ Modul: SPK (Sistem Pendukung Keputusan) - SAW
| Fungsi | Deskripsi |
| :--- | :--- |
| **Kelola Keputusan** | Membuat event penilaian baru (Create, Edit, Delete). |
| **Kelola Bobot** | Mengatur kriteria dan nilai bobotnya (Normalisasi). |
| **Kelola Kriteria** | Menambah atau menghapus kriteria penilaian. |
| **Input Nilai** | Memasukkan nilai kandidat kedalam matriks. |
| **Hitung Ranking** | Menjalankan algoritma SAW untuk melihat hasil akhir. |

---

## 🟡 2. Role: PENGURUS (BEM/HIMA)
*Mengelola internal organisasi dan publikasi.*

### 🛠️ Modul: Organisasi & Internal
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Kelola Anggota** | Menambah/Mengeluarkan anggota pengurus. | `Full CRUD` |
| **Profil Divisi** | Mengupdate deskripsi dan foto divisi sendiri. | `Update` |
| **Jabatan** | Mengatur struktur jabatan internal. | `Full CRUD` |
| **Keuangan** | Mencatat pemasukan dan pengeluaran kas (Laporan). | `Full CRUD` |

### 🛠️ Modul: Publikasi
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Buat Berita** | Menulis berita kegiatan organisasi (Draft/Pending). | `Create` |
| **Edit Sendiri** | Mengubah berita buatan sendiri sebelum terbit. | `Update` |

---

## 🔵 3. Role: MAHASISWA
*Pengguna aktif dari kalangan mahasiswa.*

### 🛠️ Modul: Akademik & Layanan
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Ajukan Sertifikat** | Mengupload sertifikat untuk validasi poin/prestasi. | `Create` |
| **Pantau Status** | Melihat apakah pengajuan disetujui/ditolak. | `Read` |
| **Buat Pengaduan** | Melaporkan masalah layanan akademik/kampus. | `Create` |
| **Balas Tanggapan** | Merespons jawaban admin pada pengaduannya. | `Create` |

---

## ⚪ 4. Role: USER (Umum/Alumni)
*Pengguna terdaftar non-mahasiswa.*

### 🛠️ Modul: Partisipasi
| Fungsi | Deskripsi | Tipe Akses |
| :--- | :--- | :--- |
| **Tulis Berita** | Mengirim artikel atau berita (Perlu approval). | `Create` |
| **Lapor/Aduan** | Mengirim laporan pengaduan umum. | `Create` |
| **Komentar** | Mengomentari berita yang terbit. | `Create` |

---

## 🌐 5. Role: PUBLIC (Guest)
*Pengunjung tanpa login.*

### 🛠️ Modul: Informasi
| Fungsi | Deskripsi |
| :--- | :--- |
| **Baca Berita** | Akses penuh membaca artikel berita. |
| **Lihat Prestasi** | Melihat daftar mahasiswa berprestasi (Hall of Fame). |
| **Lihat Divisi** | Membaca profil organisasi/divisi. |
| **Komentar** | (Opsional) Mengirim komentar jika diizinkan. |
