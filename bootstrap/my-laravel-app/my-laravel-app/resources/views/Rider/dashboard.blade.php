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
  @vite(['resources/css/rider-dashboard.css', 'resources/js/rider-dashboard.js'])

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
    <div class="profile-section dropdown text-center mt-2">
      <img
        src="{{ asset('videos/A952D051-BA1D-47D0-B69D-08AC9EB2ADEE_1_201_a.jpeg') }}"
        alt="Profile Picture"
        class="profile-pic dropdown-toggle"
        id="profileDropdown"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        style="width: 70px; height: 70px; border-radius: 50%;">

      <h5 class="mt-2">Welcome Back</h5>
      <h6>{{ $rider->name ?? 'Rider' }}</h6>

      <!-- SINGLE LOGOUT FORM -->
      <form id="logoutForm" method="POST" action="{{ route('rider.logout') }}">
        @csrf
        <button type="button" class="btn btn-danger w-100 mt-2 logout-btn">🚪 Logout</button>
      </form>
    </div>



  </div>

  <!-- === MAIN CONTENT === -->
  <div class="main-content">
    <div class="tab-content">
      <!-- MOBILE PROFILE TAB -->
      <div class="tab-pane fade" id="profile">
        <div class="p-3 text-center">
          <img
            src="{{ asset('videos/A952D051-BA1D-47D0-B69D-08AC9EB2ADEE_1_201_a.jpeg') }}"
            alt="Profile Picture"
            style="width:80px; height:80px; border-radius:50%; margin-bottom:10px;">
          <h5>{{ $rider->name ?? 'Rider' }}</h5>
          <p class="text-muted">Welcome Back!</p>

          <!-- Logout Form -->
          <form id="logoutFormMobile" method="POST" action="{{ route('rider.logout') }}">
            @csrf
            <button type="button" class="btn btn-danger w-100 logout-btn">🚪 Logout</button>
          </form>
        </div>
      </div>



      <!-- DASHBOARD -->
      <div class="tab-pane fade show active" id="dashboard">
        <div class="dashboard-header">
          <img src="{{ asset('videos/header.png') }}" alt="Header Left">
        </div>

        <div class="container py-3">
          <div class="row g-3">
            <!-- Search Card -->
            <div class="col-12">
              <div class="card shadow-sm p-3">
                <h5 class="text-center mb-3">🗺️ Set Your Route</h5>

                <div class="mb-2">
                  <label for="fromPlace" class="form-label">From:</label>
                  <input type="text" id="fromPlace" class="form-control" placeholder="Starting point">
                </div>

                <div class="mb-2">
                  <label for="toPlace" class="form-label">To:</label>
                  <input type="text" id="toPlace" class="form-control" placeholder="Destination">
                </div>

                <div class="text-center">
                  <button id="calculateRouteBtn" class="btn btn-danger w-100">Calculate Route</button>
                </div>
              </div>
            </div>

            <!-- Map Card -->
            <div class="col-12">
              <div class="card shadow-sm p-2">
                <div id="mapContainer">
                  <div id="map"></div>
                </div>


                <!-- Fullscreen Button -->
                <button id="fullscreenBtn"
                  class="btn btn-light position-absolute top-0 end-0 m-2 shadow rounded-circle"
                  style="z-index: 10000;">
                  <i class="bi bi-arrows-fullscreen"></i>
                </button>


              </div>
            </div>

            <!-- Route Info -->
            <div class="col-12">
              <div id="routeInfo" class="alert alert-info d-none"></div>
              <div id="loadingSpinner" class="text-center" style="display:none;">
                <div class="spinner-border text-danger" role="status"></div>
                <p>Calculating route...</p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- HISTORY -->
      <div class="tab-pane fade" id="history">
        <div class="content-card">
          <h3>📜 Delivery History</h3>
          <table class="table table-dark table-striped table-hover mt-3">
            <thead>
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
                <td>
                  <span class="badge {{ $item->status == 'Delivered' ? 'bg-success' : 'bg-danger' }}">
                    {{ $item->status }}
                  </span>
                </td>
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

<div class="tab-pane fade" id="transaction">
    <div class="content-card">
        <h3>💳 Transactions</h3>
        <div class="accordion" id="transactionsAccordion">
            @foreach ($transactions as $transaction)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $transaction->transaction_id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $transaction->transaction_id }}" aria-expanded="false"
                        aria-controls="collapse{{ $transaction->transaction_id }}">
                        {{ $transaction->customer_name }} 
                        <span class="badge bg-info ms-2">{{ $transaction->delivery_status }}</span>
                    </button>
                </h2>
                <div id="collapse{{ $transaction->transaction_id }}" class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $transaction->transaction_id }}" data-bs-parent="#transactionsAccordion">
                    <div class="accordion-body">
                        <p><strong>Address:</strong> {{ $transaction->customer_address }}</p>
                        <p><strong>Contact:</strong> {{ $transaction->customer_contact }}</p>
                        <button class="btn btn-primary set-route-btn" 
    data-name="{{ $transaction->customer_name }}" 
    data-address="{{ $transaction->customer_address }}" 
    data-contact="{{ $transaction->customer_contact }}">
    Set as Destination
</button>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


    </div>
  </div>

  <!-- === MOBILE BOTTOM NAVBAR === -->
  <div class="mobile-bottom-nav d-md-none">
    <button class="nav-btn active" data-bs-toggle="pill" data-bs-target="#dashboard" type="button">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </button>
    <button class="nav-btn" data-bs-toggle="pill" data-bs-target="#history" type="button">
      <i class="bi bi-clock-history"></i>
      <span>History</span>
    </button>
    <button class="nav-btn" data-bs-toggle="pill" data-bs-target="#transaction" type="button">
      <i class="bi bi-credit-card"></i>
      <span>Transaction</span>
    </button>
    <button class="nav-btn" data-bs-toggle="pill" data-bs-target="#profile" type="button">
      <i class="bi bi-person-circle"></i>
      <span>Profile</span>
    </button>
  </div>


  <!-- Customer Details Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerModalLabel">Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong> <span id="modalCustomerName"></span></p>
        <p><strong>Contact:</strong> <span id="modalCustomerContact"></span></p>
        <p><strong>Address:</strong> <span id="modalCustomerAddress"></span></p>
        <input type="hidden" id="modalCustomerLat">
        <input type="hidden" id="modalCustomerLng">
      </div>
      <div class="modal-footer">
<button type="button" class="btn btn-primary" id="setDestinationBtn">Set Route</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const setRouteButtons = document.querySelectorAll('.set-route-btn'); // correct selector
    const modalElement = document.getElementById('customerModal');
    const modal = new bootstrap.Modal(modalElement);
    const modalName = document.getElementById('modalCustomerName');
    const modalAddress = document.getElementById('modalCustomerAddress');
    const modalContact = document.getElementById('modalCustomerContact');
    const toPlaceInput = document.getElementById('toPlace');
    const setDestinationBtn = document.getElementById('setDestinationBtn');

    let selectedAddress = '';
    let selectedName = '';
    let selectedContact = '';

    // Open modal when clicking "Set as Destination"
    setRouteButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            selectedName = btn.dataset.name;
            selectedAddress = btn.dataset.address;
            selectedContact = btn.dataset.contact;

            modalName.textContent = selectedName;
            modalAddress.textContent = selectedAddress;
            modalContact.textContent = selectedContact;

            modal.show();
        });
    });

    // Set route with confirmation
    setDestinationBtn.addEventListener('click', () => {
        if (!selectedAddress) return;

        Swal.fire({
            title: 'Set this customer as your destination?',
            text: `${selectedName} - ${selectedAddress}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, set route',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                toPlaceInput.value = selectedAddress;

                // Trigger route calculation
                document.getElementById('calculateRouteBtn').click();

                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Route set!',
                    text: 'Destination has been updated. ',
                    text: 'Go to your Dashboard to proceed. ',


                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });
});
</script>







  <!-- === SCRIPTS === -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

</body>

</html>