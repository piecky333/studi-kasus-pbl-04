@extends('layouts.pengurus')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3">Tambah Data Keuangan</h4>

    <form action="{{ route('pengurus.keuangan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Divisi</label>
            <select name="id_divisi" class="form-select" required>
                <option value="">-- Pilih Divisi --</option>
                @foreach($divisi as $d)
                    <option value="{{ $d->id_divisi }}">{{ $d->nama_divisi }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pengurus</label>
            <select name="id_pengurus" class="form-select" required>
                <option value="">-- Pilih Pengurus --</option>
                @foreach($pengurus as $p)
                    <option value="{{ $p->id_pengurus }}">{{ $p->user->nama ?? 'Tanpa Nama' }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah Iuran (Rp)</label>
            <input type="number" name="jumlah_iuran" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-select" required>
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
                <option value="qris">QRIS</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pengurus.keuangan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
