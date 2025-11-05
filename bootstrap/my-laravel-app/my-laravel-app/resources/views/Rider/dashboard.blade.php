<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rider Dashboard</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">



  <style>
/* === GLOBAL STYLES === */
body {
  background-color: #0e0e0e;
  font-family: "Poppins", sans-serif;
  color: #fff;
  overflow-x: hidden;
  margin: 0;
  padding: 0;
}

h3, h5, h6 {
  color: #dc3545;
  font-weight: 600;
}

/* === SCROLLBAR === */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-thumb { background: #dc3545; border-radius: 5px; }
::-webkit-scrollbar-track { background: #1a1a1a; }

/* === SIDEBAR === */
.sidebar {
  background-color: #121212;
  min-height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  width: 250px;
  padding: 30px 20px;
  border-right: 1px solid #222;
  transition: all 0.4s ease-in-out;
  z-index: 200;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-shadow: 3px 0 15px rgba(220, 53, 69, 0.2);
}

.profile-section {
  text-align: center;
  margin-bottom: 40px;
  animation: fadeIn 0.6s ease;
}

.profile-pic {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  border: 3px solid #dc3545;
  object-fit: cover;
  margin-bottom: 10px;
  transition: transform 0.3s ease;
  cursor: pointer;
}

.profile-pic:hover { transform: scale(1.1); }

.sidebar h5 { color: #fff; margin: 5px 0 0; font-size: 1rem; }
.sidebar h6 { color: #dc3545; font-weight: 600; }

.nav-link {
  color: #ddd;
  font-weight: 500;
  border-radius: 8px;
  padding: 10px 15px;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  text-decoration: none;
}

.nav-link:hover,
.nav-link.active {
  background-color: #dc3545;
  color: #fff;
  transform: translateX(5px);
}

/* === MAIN CONTENT === */
.main-content {
  margin-left: 250px;
  padding: 2rem;
  transition: margin-left 0.4s ease;
  position: relative;
  z-index: 1;
}

/* === DASHBOARD HEADER === */
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #141414;
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 30px;
  box-shadow: 0 0 15px rgba(220, 53, 69, 0.3);
  gap: 20px;
  flex-wrap: wrap;
  animation: fadeInUp 0.6s ease;
}

.dashboard-header img {
  width: 100%;
  max-width: 400px;
  height: auto;
  border-radius: 10px;
  object-fit: cover;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.dashboard-header img:hover {
  transform: scale(1.05);
  box-shadow: 0 0 20px rgba(220, 53, 69, 0.5);
}

/* === CARDS === */
.content-card {
  background-color: #1a1a1a;
  border-radius: 12px;
  border-left: 5px solid #dc3545;
  padding: 1.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  animation: fadeInUp 0.8s ease;
}

.content-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
}

/* === MAP === */
#map {
  height: 350px;
  border-radius: 10px;
  margin-top: 10px;
  animation: fadeIn 0.8s ease;
}

/* === BUTTONS === */
.btn-danger {
  background-color: #dc3545 !important;
  border: none;
  border-radius: 8px;
  padding: 10px 15px;
  font-weight: 500;
  transition: all 0.3s ease;
  color: #fff !important;
}

.btn-danger:hover {
  background-color: #b52d3a !important;
  transform: scale(1.05);
}

/* === DROPDOWN MENU (Profile) === */
.dropdown-menu {
  border: 1px solid #dc3545;
  background-color: #1a1a1a;
  border-radius: 8px;
  margin-top: 10px;
  padding: 5px 0;
  box-shadow: 0 0 15px rgba(220, 53, 69, 0.2);
}

.dropdown-item {
  color: #fff;
  font-weight: 500;
  transition: background-color 0.3s ease;
}

.dropdown-item:hover {
  background-color: #dc3545;
  color: #fff;
}

/* === ANIMATIONS === */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

/* === RESPONSIVE STYLES === */

/* Tablets */
@media (max-width: 992px) {
  .sidebar { width: 220px; }
  .main-content { margin-left: 220px; }
}

/* === MOBILE VIEW (SIDEBAR ON TOP HORIZONTAL) === */
@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    height: auto;
    min-height: unset;
    position: fixed;
    top: 0;
    left: 0;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    border-right: none;
    border-bottom: 1px solid #222;
    box-shadow: 0 3px 10px rgba(220, 53, 69, 0.2);
  }

  .nav-links {
    flex: 1;
    display: flex;
    justify-content: center;
    gap: 8px;
  }

  .nav-link {
    font-size: 0.8rem;
    padding: 6px 10px;
  }

  .profile-section {
    position: absolute;
    right: 15px;
    top: 8px;
    margin: 0;
  }

  .profile-pic {
    width: 45px;
    height: 45px;
    border: 2px solid #dc3545;
  }

  .main-content {
    margin: 80px 0 0 0;
    padding: 1.2rem;
  }

  .dashboard-header {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .dashboard-header img {
    max-width: 90%;
  }

  /* Hide desktop logout button, show dropdown */
  .profile-section .d-none.d-md-block {
    display: none !important;
  }

  .dropdown-menu {
    right: 0;
    left: auto;
  }
}

/* Small phones */
@media (max-width: 576px) {
  .nav-link {
    font-size: 0.75rem;
    padding: 5px 7px;
  }

  #map { height: 250px; }
  .content-card { padding: 1rem; }
}

/* === DESKTOP VIEW: SHOW LOGOUT BELOW PROFILE === */
@media (min-width: 769px) {
  .profile-section .dropdown-menu {
    display: none !important;
  }

  .logout-desktop {
    margin-top: 10px;
  }
}

/* === UNIFORM SIDEBAR BUTTONS === */
.nav-pills {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.nav-pills .nav-item {
  width: 100%;
}

.nav-pills .nav-link {
  width: 100%;
  text-align: center;
  border: 1px solid #dc3545; /* red outline */
  border-radius: 10px;
  color: #fff;
  background: transparent;
  padding: 10px 0;
  transition: all 0.3s ease;
}

.nav-pills .nav-link:hover {
  background: rgba(220, 53, 69, 0.2);
  transform: translateY(-2px);
}

.nav-pills .nav-link.active {
  background: #dc3545 !important;
  color: #fff !important;
  border-color: #dc3545;
  box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
}

/* 🌞 Light Mode (Default) */
body.light-mode {
  background-color: #f8f9fa;
  color: #212529;
}

.light-mode .dashboard-header {
  background-color: #fff;
  color: #000;
  border: 1px solid #ddd;
}

.light-mode .sidebar {
  background-color: #fff;
  color: #000;
  border-right: 1px solid #ddd;
}

/* 🌙 Dark Mode */
body.dark-mode {
  background-color: #121212;
  color: #f1f1f1;
}

.dark-mode .dashboard-header {
  background-color: #000;
  color: #fff;
}

.dark-mode .sidebar {
  background-color: #1e1e1e;
  color: #fff;
}

.dark-mode .card {
  background-color: #2a2a2a;
  color: #fff;
}



  </style>
</head>

<body>
  <!-- === SIDEBAR (turns horizontal on mobile) === -->
  <div class="sidebar">
    <!-- NAV LINKS -->

    <!-- 🌗 Theme Toggle Button -->
<button id="themeToggle" class="btn btn-outline-light ms-2">
  <i class="bi bi-sun-fill" id="themeIcon"></i>
</button>

<ul class="nav nav-pills flex-md-column flex-row align-items-center w-100" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#dashboard" type="button" role="tab">
      📊 Dashboard
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#history" type="button" role="tab">
      📜 History
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#transaction" type="button" role="tab">
      💳 Transactions
    </button>
  </li>
</ul>


    <!-- PROFILE SECTION -->
<div class="profile-section dropdown">
  <img 
    src="{{ asset('images/profile.jpg') }}" 
    alt="Profile Picture" 
    class="profile-pic dropdown-toggle" 
    id="profileDropdown"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >

  <!-- Desktop View -->
  <div class="d-none d-md-block text-center mt-2">
    <h5>Welcome Back</h5>
    <h6>{{ $rider->name ?? 'Rider' }}</h6>

    <!-- Proper Logout Form -->
    <form id="logoutForm" method="POST" action="{{ route('rider.logout') }}">
  @csrf
  <button type="button" class="btn btn-danger w-100 mt-2" id="logoutBtn">🚪 Logout</button>
</form>

  </div>

  <!-- Mobile Dropdown -->
  <ul class="dropdown-menu dropdown-menu-end bg-dark text-light" aria-labelledby="profileDropdown">
    <li>
      <form method="POST" action="{{ route('rider.logout') }}" class="px-3">
        @csrf
        <button type="submit" class="dropdown-item text-danger w-100 text-center">🚪 Logout</button>
      </form>
    </li>
  </ul>
</div>


  </div>

  <!-- === MAIN CONTENT === -->
  <div class="main-content">
    <div class="tab-content">

      <!-- DASHBOARD -->
      <div class="tab-pane fade show active" id="dashboard">
        <div class="dashboard-header">
          <img src="{{ asset('images/header-left.jpg') }}" alt="Header Left">
          <img src="{{ asset('images/header-right.jpg') }}" alt="Header Right">
        </div>

        <div class="content-card">
          <h3>📍 Shipment Tracker</h3>
          <p class="text-light">Monitor your delivery routes in real time.</p>
          <div id="map"></div>

          <div class="mt-4">
            <h5>Set Delivery Route</h5>
            <div class="row g-3 mt-2">
              <div class="col-md-6">
                <label for="fromPlace" class="form-label">From:</label>
                <input type="text" id="fromPlace" class="form-control" placeholder="Enter starting location">
              </div>
              <div class="col-md-6">
                <label for="toPlace" class="form-label">To:</label>
                <input type="text" id="toPlace" class="form-control" placeholder="Enter destination">
              </div>
            </div>

            <button class="btn btn-danger w-100 mt-3" id="routeBtn" onclick="calculateRoute()">🚴 Set Route</button>
            <div id="loadingSpinner" class="text-center mt-2" style="display:none;">
              <div class="spinner-border spinner-border-sm text-light"></div>
              <p class="text-light">Calculating route...</p>
            </div>
            <div id="routeInfo" class="alert alert-dark d-none mt-3"></div>
          </div>
        </div>
      </div>

      <!-- HISTORY -->
      <div class="tab-pane fade" id="history">
        <div class="content-card">
          <h3>📜 Delivery History</h3>
          <table class="table table-dark table-striped table-hover mt-3">
            <thead>
              <tr><th>#</th><th>Date</th><th>Customer</th><th>Status</th></tr>
            </thead>
            <tbody>
              @forelse ($history as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->customer }}</td>
                <td>
                  <span class="badge {{ $item->status == 'Delivered' ? 'bg-success' : 'bg-danger' }}">
                    {{ $item->status }}
                  </span>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted">No delivery history yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- TRANSACTIONS -->
      <div class="tab-pane fade" id="transaction">
        <div class="content-card">
          <h3>💳 Transactions</h3>
          <table class="table table-dark table-bordered mt-3">
            <thead>
              <tr><th>Transaction ID</th><th>Amount</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- === SCRIPTS === -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script src="{{ asset('js/rider-dashboard.js') }}"></script>
  
</body>
</html>
