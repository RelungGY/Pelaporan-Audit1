 <!-- Begin Page Content -->
 <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pelaporan Data</h1>
    <!-- Date Range and Export Options -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <button class="btn btn-primary" id="export_data">Export Data</button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
 
        <div class="card-header py-3">
            <ul class="nav nav-pills card-header-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Belum Dibayar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Perlu Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Selesai Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Status Pengembalian</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>