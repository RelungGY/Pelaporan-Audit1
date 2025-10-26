<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Online Orders Section -->
        <div class="col-xl-6 col-lg-6">
            <h2 class="h4 mb-4 text-gray-800">Pesanan Online</h2>
            
            <a href="{{ route('pdf.online') }}" class="btn btn-sm btn-primary mb-4">Download PDF</a>
            <div class="row">
                <!-- Online Orders Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                       Pesanan saya</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $transactions->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-people-carry-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Online Orders to be Shipped Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                       Perlu dikirim</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions->whereIn('status', ['perlu dikirim', 'dikirim'])->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-user-group"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Online Earnings Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Earnings (Annual)
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">Rp. {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }} </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Online Completed Orders Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Selesai</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions->where('status', 'selesai dikirim')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offline Orders Section -->
        <div class="col-xl-6 col-lg-6">
            <h2 class="h4 mb-4 text-gray-800">Pesanan Offline</h2>
            <a href="{{ route('pdf.offline') }}" class="btn btn-sm btn-primary mb-4">Download PDF</a>
            <div class="row">
                <!-- Offline Orders Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                       Pesanan saya</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $service->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-people-carry-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offline Orders to be Shipped Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                       Pelayanan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $service->sum('jumlah_pesanan')}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-user-group"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offline Earnings Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Earnings (Annual)
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">Rp. {{ number_format($service->sum('total_harga'), 0, ',', '.') }} </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offline Completed Orders Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Selesai</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $service->where('status_pengembalian', 'dikembalikan')->sum() ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
