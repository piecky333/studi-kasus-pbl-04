# Database Schema Design (ERD)

## 1. Actor & Authentication
### **User** (`user`)
*Store all login credentials and role information.*
- `id_user` (Primary Key, FK)
- `nama`
- `username`
- `email`
- `password`
- `role` (Enum: admin, pengurus, user, mahasiswa)
- `avatar`
- `no_telpon`
- `profile_photo_path`

### **Admin** (`admin`)
*Admins who manage the system.*
- `id_admin` (Primary Key)
- `id_user` (FK -> User.id_user)
- `nama_admin`
- `jabatan`

### **Pengurus** (`pengurus`)
*Organization board members (BEM/HIMA).*
- `id_pengurus` (Primary Key)
- `id_user` (FK -> User.id_user)
- `id_divisi` (FK -> Divisi.id_divisi)
- `id_jabatan` (FK -> Jabatan.id_jabatan)

---

## 2. Organization Structure
### **Divisi** (`divisi`)
- `id_divisi` (Primary Key)
- `nama_divisi`
- `isi_divisi`
- `foto_divisi`

### **Jabatan** (`jabatan`)
- `id_jabatan` (Primary Key)
- `id_divisi` (FK -> Divisi.id_divisi)
- `nama_jabatan`

---

## 3. Student & Academic Data
### **Datamahasiswa** (`mahasiswa`)
*Core student profile linked to a user account.*
- `id_mahasiswa` (Primary Key)
- `id_user` (FK -> User.id_user)
- `id_admin` (FK -> Admin.id_admin) *Verifikator*
- `nim`
- `nama`
- `email`
- `semester`
- `ipk`

### **Prestasi** (`prestasi`)
- `id_prestasi` (Primary Key)
- `id_mahasiswa` (FK -> Datamahasiswa.id_mahasiswa)
- `id_admin` (FK -> Admin.id_admin)
- `nama_kegiatan`
- `jenis_prestasi`
- `tingkat_prestasi`
- `juara`
- `tahun`
- `status_validasi`
- `deskripsi`
- `bukti_path`

### **Sanksi** (`sanksi`)
- `id_sanksi` (Primary Key)
- `id_mahasiswa` (FK -> Datamahasiswa.id_mahasiswa)
- `tanggal_sanksi`
- `jenis_sanksi`
- `jenis_hukuman`
- `keterangan`
- `file_pendukung`

---

## 4. Complaints System (Laporan)
### **Pengaduan** (`pengaduan`)
- `id_pengaduan` (Primary Key)
- `id_user` (FK -> User.id_user) *Pelapor*
- `judul`
- `tanggal_pengaduan`
- `jenis_kasus`
- `deskripsi`
- `status`
- `gambar_bukti`
- `no_telpon_dihubungi`

### **Tanggapan** (`tanggapan`)
- `id_tanggapan` (Primary Key)
- `id_pengaduan` (FK -> Pengaduan.id_pengaduan)
- `id_admin` (FK -> Admin.id_admin)
- `id_user` (FK -> User.id_user)
- `isi_tanggapan`
- `tanggal_tanggapan`

### **Terlapor** (`terlapor`)
- `id_terlapor` (Primary Key)
- `id_pengaduan` (FK -> Pengaduan.id_pengaduan)
- `nama_terlapor`
- `no_hp_terlapor`
- `status_terlapor`
- `keterangan`

---

## 5. News & Information
### **Berita** (`berita`)
- `id_berita` (Primary Key)
- `id_user` (FK -> User.id_user) *Penulis*
- `judul_berita`
- `isi_berita`
- `gambar_berita`
- `kategori`
- `status`
- `id_verifikator`
- `id_penolak`

---

## 6. Decision Support System (SPK)
### **Spkkeputusan** (`spkkeputusan`)
- `id_keputusan` (Primary Key)
- `nama_keputusan`
- `tanggal_dibuat`
- `status`

### **Kriteria** (`kriteria`)
- `id_kriteria` (Primary Key)
- `id_keputusan` (FK -> Spkkeputusan.id_keputusan)
- `nama_kriteria`
- `kode_kriteria`
- `jenis_kriteria`
- `bobot_kriteria`
- `sumber_data`
- `atribut_sumber`

### **Alternatif** (`alternatif`)
- `id_alternatif` (Primary Key)
- `id_keputusan` (FK -> Spkkeputusan.id_keputusan)
- `id_mahasiswa` (FK -> Datamahasiswa.id_mahasiswa)
- `nama_alternatif`
- `keterangan`

### **Penilaian** (`penilaian`)
- `id_penilaian` (Primary Key)
- `id_kriteria` (FK -> Kriteria.id_kriteria)
- `id_alternatif` (FK -> Alternatif.id_alternatif)
- `nilai`

---

## Relationship Summary
1. **User** is the generic parent for **Admin**, **Pengurus**, and **Datamahasiswa**.
2. **Datamahasiswa** holds the academic profile and is linked to **Prestasi** and **Sanksi**.
3. **Pengaduan** is submitted by a **User** and responded to by an **Admin** via **Tanggapan**.
4. **SPK** connects **Datamahasiswa** (as candidates/alternatives) to a decision-making process involving **Kriteria** and **Penilaian**.
