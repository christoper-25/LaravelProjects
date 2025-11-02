<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.css" />

    <style>
        body {
            background-color: #a85555;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            background-color: #000;
            min-height: 100vh;
            color: #fff;
            position: fixed;
        }

        .sidebar .nav-link {
            color: #fff;
            font-weight: 500;
            transition: 0.3s;
            position: relative;
            padding-left: 2.5rem;
        }

        .sidebar .nav-link::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background-color: #dc3545;
            transition: width 0.3s ease;
            border-radius: 0 5px 5px 0;
        }

        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            width: 8px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #111;
            color: #fff;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            background-color: #ccc;
            border: 3px solid #dc3545;
            object-fit: cover;
        }

        .content-card {
            background-color: #191616;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #dc3545;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        h3 {
            color: #dc3545;
            font-weight: bold;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #map {
            height: 300px;
            border-radius: 8px;
            width: 100%;
        }

        .dashboard-header {
            background-color: #000;
            position: fixed;
            z-index: 1000;
            width: 72%;
            padding: 5px;
            border-radius: 10px;
            margin-top: -170px;
            margin-left: -70px;
        }

        .header-img {
            height: 133px;
            object-fit: cover;
            margin: 0 1px;
            border-radius: 1px;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner-border-sm {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-md-3 p-4 sidebar">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture" class="img-fluid rounded-circle profile-pic mb-3">
                    <h5>Welcome Back</h5>
                    <h6 class="text-danger fw-bold">{{ $rider->name ?? 'Rider' }}!</h6>
                </div>

                <nav class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                    <a class="nav-link active mb-2" id="dashboard-tab" data-bs-toggle="pill" href="#dashboard" role="tab">📊 Dashboard</a>
                    <a class="nav-link mb-2" id="history-tab" data-bs-toggle="pill" href="#history" role="tab">📜 History</a>
                    <a class="nav-link mb-2" id="transaction-tab" data-bs-toggle="pill" href="#transaction" role="tab">💳 Transaction</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 p-4" style="margin-left: 353px;">
                <div class="tab-content" style="margin-top: 150px;" id="v-pills-tabContent">

                    <!-- Dashboard -->
                    <div class="dashboard-header d-flex align-items-center justify-content-center mb-3">
                        <img src="{{ asset('images/header-left.jpg') }}" alt="Header Left" class="header-img">
                        <img src="{{ asset('images/header-right.jpg') }}" alt="Header Right" class="header-img">
                    </div>

                    <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                        <div class="content-card p-4 mb-4">
                            <h3>📊 Shipment Track</h3>
                            <p style="color: #fff;">Overview of your deliveries and current status.</p>

                            <div class="row text-center mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-danger text-white mb-3">
                                        <div class="card-body">
                                            <h5>In Transit</h5>
                                            <p class="display-10">{{ $inTransit ?? 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-dark text-white mb-3">
                                        <div class="card-body">
                                            <h5>Delivered</h5>
                                            <p class="display-10">{{ $delivered ?? 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light text-dark border-danger mb-3">
                                        <div class="card-body">
                                            <h5>Completed</h5>
                                            <p class="display-10">{{ $completed ?? 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-body" style="background-color: #a85555;">
                                    <h5 class="mb-3" style="color:white;">Delivery Map</h5>
                                    <div id="map"></div>

                                    <div class="card border-0 shadow-sm mt-4">
                                        <div class="card-body">
                                            <h5 class="mb-3">Set Delivery Route</h5>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="fromPlace" class="form-label">From:</label>
                                                    <input type="text" id="fromPlace" class="form-control" placeholder="Enter starting location">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="toPlace" class="form-label">To:</label>
                                                    <input type="text" id="toPlace" class="form-control" placeholder="Enter destination">
                                                </div>
                                            </div>

                                            <button class="btn btn-danger w-100 mb-3" id="routeBtn" onclick="calculateRoute()">🚴 Set Route</button>
                                            <div class="loading" id="loadingSpinner">
                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                Calculating route...
                                            </div>
                                            <div id="routeInfo" class="alert alert-dark d-none"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- History -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <div class="content-card p-4 mb-4">
                            <h3>📜 Delivery History</h3>
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($history as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->customer }}</td>
                                        <td><span class="badge {{ $item->status == 'Delivered' ? 'bg-success' : 'bg-danger' }}">{{ $item->status }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No delivery history yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Transactions -->
                    <div class="tab-pane fade" id="transaction" role="tabpanel">
                        <div class="content-card p-4 mb-4">
                            <h3>💳 Transactions</h3>
                            <table class="table table-bordered">
                                <thead class="table-danger">
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>
    <script src="{{ asset('js/rider-dashboard.js') }}"></script>
</body>

</html>