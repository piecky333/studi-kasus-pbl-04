<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    {{-- Detail Berita --}}
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">Judul Berita Contoh</h2>
            <p class="text-muted">Diposting pada 30 September 2025</p>
            <p class="card-text">
                Ini adalah isi berita contoh yang ditampilkan di halaman konten.
                Kamu bisa ganti teks ini dengan data berita dari database nanti.
            </p>
        </div>
    </div>

    {{-- Daftar Komentar --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5>Komentar</h5>
        </div>
        <div class="card-body">
            <div class="mb-3 border-bottom pb-2">
                <strong>Upik</strong>
                <small class="text-muted">(30 Sep 2025 10:00)</small>
                <p>Keren banget beritanya 👍</p>
            </div>

            <div class="mb-3 border-bottom pb-2">
                <strong>Rifki</strong>
                <small class="text-muted">(30 Sep 2025 11:15)</small>
                <p>Terima kasih infonya!</p>
            </div>
        </div>
    </div>

    {{-- Form Tambah Komentar --}}
    <div class="card">
        <div class="card-header">
            <h5>Tulis Komentar</h5>
        </div>
        <div class="card-body">
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="isi" class="form-label">Komentar</label>
                    <textarea name="isi" id="isi" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>

</div>
</body>
</html>
