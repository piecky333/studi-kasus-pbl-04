@extends('layouts.pengurus')

@section('content')
<style>
    /* Hover effect untuk card keuangan */
    .keuangan-card {
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
        cursor: pointer;
    }

    .keuangan-front, .keuangan-back {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        transition: transform 0.6s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .keuangan-front {
        transform: rotateY(0deg);
    }

    .keuangan-back {
        transform: rotateY(180deg);
    }

    .keuangan-card:hover .keuangan-front {
        transform: rotateY(180deg);
    }

    .keuangan-card:hover .keuangan-back {
        transform: rotateY(0deg);
    }

    .keuangan-card {
        perspective: 1000px;
        height: 180px;
    }
</style>

<div class="container-fluid py-4">
    <h4 class="fw-bold mb-4 text-primary"><i class="bi bi-speedometer2 me-2"></i>Dashboard Pengurus</h4>

    <div class="row g-4">
        <!-- Total Divisi -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 bg-gradient bg-primary text-white rounded-4">
                <i class="bi bi-diagram-3 fs-1 mb-2"></i>
                <h6>Total Divisi</h6>
                <h3 class="fw-bold">{{ $totalDivisi }}</h3>
            </div>
        </div>

        <!-- Total Jabatan -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 bg-gradient bg-info text-white rounded-4">
                <i class="bi bi-person-badge fs-1 mb-2"></i>
                <h6>Total Jabatan</h6>
                <h3 class="fw-bold">{{ $totalJabatan }}</h3>
            </div>
        </div>

        <!-- Total Pengurus -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 bg-gradient bg-success text-white rounded-4">
                <i class="bi bi-people-fill fs-1 mb-2"></i>
                <h6>Total Pengurus</h6>
                <h3 class="fw-bold">{{ $totalPengurus }}</h3>
            </div>
        </div>

        <!-- Total Keuangan dengan Hover Flip -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0 bg-gradient bg-warning text-dark rounded-4 keuangan-card">
                <!-- Tampilan Depan -->
                <div class="keuangan-front">
                    <i class="bi bi-cash-stack fs-1 mb-2"></i>
                    <h6>Total Keuangan</h6>
                    <h4 class="fw-bold">Rp {{ number_format($totalKeuangan, 0, ',', '.') }}</h4>
                </div>

                <!-- Tampilan Belakang -->
                <div class="keuangan-back bg-light rounded-4">
                    <div class="text-center">
                        <h6 class="fw-semibold mb-3">Status Pembayaran</h6>
                        <div class="d-flex justify-content-around">
                            <div>
                                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                                <p class="mb-0 small">Belum</p>
                                <strong>{{ $belum ?? 0 }}</strong>
                            </div>
                            <div>
                                <i class="bi bi-hourglass-split text-warning fs-5"></i>
                                <p class="mb-0 small">Proses</p>
                                <strong>{{ $proses ?? 0 }}</strong>
                            </div>
                            <div>
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <p class="mb-0 small">Lunas</p>
                                <strong>{{ $lunas ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
