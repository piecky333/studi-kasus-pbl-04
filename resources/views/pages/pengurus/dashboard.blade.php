@extends('layouts.pengurus')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Dashboard Pengurus</h3>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h6>Total Divisi</h6>
                <h3>{{ $totalDivisi }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h6>Total Jabatan</h6>
                <h3>{{ $totalJabatan }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h6>Total Pengurus</h6>
                <h3>{{ $totalPengurus }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h6>Total Keuangan</h6>
                <h3>Rp {{ number_format($totalKeuangan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
