@extends('layouts.pengurus')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3">Edit Data Keuangan</h4>

    <form action="{{ route('pengurus.keuangan.update', $keuangan->id_keuangan) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Jumlah Iuran (Rp)</label>
            <input type="number" name="jumlah_iuran" class="form-control" value="{{ $keuangan->jumlah_iuran }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status Pembayaran</label>
            <select name="status_pembayaran" class="form-select">
                <option value="belum" {{ $keuangan->status_pembayaran == 'belum' ? 'selected' : '' }}>Belum</option>
                <option value="proses" {{ $keuangan->status_pembayaran == 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="lunas" {{ $keuangan->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('pengurus.keuangan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
