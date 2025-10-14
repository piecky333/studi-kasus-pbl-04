@extends('layouts.pengurus')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">{{ $divisi->nama_divisi }}</h2>

    <div class="card p-4 shadow-sm rounded-4">
        @if($divisi->foto_divisi)
            <img src="{{ asset('storage/'.$divisi->foto_divisi) }}" class="rounded mb-3" style="max-width:300px;">
        @endif

        <p class="fs-5">{{ $divisi->isi_divisi }}</p>

        <a href="{{ route('admin.divisi.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection
 