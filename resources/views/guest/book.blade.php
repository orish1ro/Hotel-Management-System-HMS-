<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking - Ragadio Plaza Hotel</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
<style>
/* Main Layout Fixes */
.booking-page-wrapper {
    display: flex;
    justify-content: center;
    padding: 15p    x;
    min-height: 98vh;
}
/* Increased width to 1250px to prevent squeezing */
.booking-card {
    max-width: 1250px;
    width: 100%;
    margin: 0 auto;
    padding: 35px 45px;
}
/* Two-Column Grid */
.booking-grid {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 0; /* Gap is handled by column padding now */
    align-items: start;
}
.booking-col-left {
    padding-right: 45px;
}
/* Added a vertical border line to separate columns */
.booking-col-right {
    padding-left: 45px;
    border-left: 1px solid #e2e8f0;
}
.section-label {
    margin-top: 15px !important;
    margin-bottom: 12px !important;
    font-size: 12px !important;
}
.form-group {
    margin-bottom: 14px;
}
.form-group input {
    padding: 10px 14px;
}
/* Scrollable Services Area */
.services-scroll-area {
    max-height: 280px;
    overflow-y: auto;
    padding-right: 10px;
    margin-bottom: 15px;
}
/* Custom Scrollbar for better look */
.services-scroll-area::-webkit-scrollbar { width: 5px; }
.services-scroll-area::-webkit-scrollbar-track { background: #f1f1f1; }
.services-scroll-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

/* Responsive: Stack columns and hide line on mobile */
@media(max-width: 950px) {
    .booking-grid { grid-template-columns: 1fr; }
    .booking-col-right { border-left: none; padding-left: 0; margin-top: 20px; }
    .booking-col-left { padding-right: 0; }
}
</style>
</head>
<body>
<header>
<div class="logo-text">Ragadio Plaza Hotel</div>
<div class="search-container"><input type="text" placeholder="search"></div>
<nav>
<a href="/">Home</a>
<a href="/rooms">Rooms</a>
<a href="/reservations">Reservations</a>
@if(session()->has('guest_id'))
<a href="/logout">Logout</a>
@else
<a href="/login">Login</a>
@endif
</nav>
</header>

<div class="booking-page-wrapper">
<div class="booking-card">
<h2 style="margin-bottom: 4px; color: #003366;">Booking Information & Confirmation</h2>
<p class="subtitle" style="margin-bottom: 25px;">Review and complete your reservation details below.</p>

<form action="/payment-process" method="POST">
@csrf
<div class="booking-grid">
    
    <div class="booking-col-left">
        <span class="section-label">Guest Information</span>
        <div class="form-row">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Juan Dela Cruz" value="{{ isset($guest) ? $guest->First_Name . ' ' . $guest->Last_Name : '' }}" required readonly style="background:#f8fafc;">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@example.com" value="{{ isset($guest) ? $guest->Email : '' }}" required readonly style="background:#f8fafc;">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="+63 912 345 6789" value="{{ isset($guest) ? $guest->Phone_Number : '' }}" required readonly style="background:#f8fafc;">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" placeholder="City, Country" required>
            </div>
        </div>

        <span class="section-label" style="margin-top:25px !important;">Reservation Details</span>
        <div class="form-group">
            <label>Selected Room</label>
            <input type="text" value="{{ $room->Room_Type }} (#{{ $room->Room_Number }})" readonly style="background:#f8fafc; font-weight:700;">
            <input type="hidden" name="room_id" value="{{ $room->ROOM_ID }}">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Check-in Date</label>
                <input type="date" name="check_in" id="check_in" required>
            </div>
            <div class="form-group">
                <label>Check-out Date</label>
                <input type="date" name="check_out" id="check_out" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Number of Guests</label>
                <input type="number" name="guests" value="1" min="1" max="{{ $room->Capacity }}" required>
            </div>
            <div class="form-group">
                <label>Total Nights</label>
                <input type="number" name="nights" id="nights" value="1" readonly style="background:#f1f5f9; font-weight:700;">
            </div>
        </div>
    </div>

    <div class="booking-col-right">
        <span class="section-label">Add-On Services</span>
        <div style="margin-bottom:15px;">
            @php
                $services = \Illuminate\Support\Facades\DB::table('services')->orderBy('Service_Category')->get();
                $grouped = $services->groupBy('Service_Category');
                $catIndex = 0;
            @endphp

            @forelse($grouped as $category => $items)
            @php $catId = 'cat_' . $catIndex++; @endphp
            <div style="border:1.5px solid #e2e8f0; border-radius:10px; margin-bottom:8px; overflow:hidden;">
                <!-- Accordion Header -->
                <div onclick="toggleAccordion('{{ $catId }}')"
                     style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#f8fafc; cursor:pointer; user-select:none;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">{{ $category }}</span>
                        <span style="font-size:10px; background:#e2e8f0; color:#64748b; border-radius:99px; padding:1px 7px;">{{ count($items) }}</span>
                    </div>
                    <span id="{{ $catId }}_arrow" style="font-size:11px; color:#94a3b8; transition:transform 0.2s;">▼</span>
                </div>
                <!-- Accordion Body -->
                <div id="{{ $catId }}" style="display:none; padding:8px 10px; background:#fff;">
                    @foreach($items as $svc)
                    <div class="service-item" style="display:flex; align-items:center; justify-content:space-between; border:1.5px solid #e2e8f0; border-radius:8px; background:#f8fafc; padding:10px 12px; margin-bottom:6px; cursor:pointer;" onclick="toggleService(this, {{ $svc->SERVICES_ID }}, {{ $svc->Price }})">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <input type="checkbox" name="services[]" value="{{ $svc->SERVICES_ID }}" id="svc_{{ $svc->SERVICES_ID }}" onclick="event.stopPropagation(); toggleService(this.closest('.service-item'), {{ $svc->SERVICES_ID }}, {{ $svc->Price }})">
                            <span style="font-size:13px; font-weight:600; color:#334155;">{{ $svc->Service_Name }}</span>
                        </div>
                        <span style="font-size:13px; font-weight:700; color:#16a34a;">+₱{{ number_format($svc->Price, 0) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
                <p style="font-size:12px; color:#94a3b8;">No extra services available.</p>
            @endforelse
        </div>

        <span class="section-label" style="margin-top:20px !important;">Summary & Payment</span>

        <!-- Deposit Notice -->
        <div style="background:#fffbeb; border:1.5px solid #fcd34d; border-radius:10px; padding:11px 14px; margin-bottom:14px; display:flex; gap:10px; align-items:flex-start;">
            <span style="font-size:18px; line-height:1;">⚠️</span>
            <div>
                <div style="font-size:12px; font-weight:800; color:#92400e; margin-bottom:2px;">50% Deposit Required to Confirm Booking</div>
                <div style="font-size:11px; color:#b45309; line-height:1.5;">The remaining balance must be settled upon arrival at the lobby. <strong>This deposit is non-refundable.</strong></div>
            </div>
        </div>

        <div class="payment-summary-box" style="margin-top:0; padding: 18px; border-radius:12px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-size:13px; color:#64748b;">Room Rate</span>
                <span style="font-size:13px; font-weight:600; color:#334155;">₱{{ number_format($room->Price_Per_Night, 2) }} / night</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-size:13px; color:#64748b;">Nights</span>
                <span id="summaryNights" style="font-size:13px; font-weight:600; color:#334155;">× 1</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-size:13px; color:#64748b;">Room Subtotal</span>
                <span id="summaryRoomTotal" style="font-size:13px; font-weight:600; color:#334155;">₱{{ number_format($room->Price_Per_Night, 2) }}</span>
            </div>
            <div id="servicesSummaryRow" style="display:none; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-size:13px; color:#64748b;">Add-on Services <span id="servicesCount" style="font-size:11px; background:#dbeafe; color:#1d4ed8; border-radius:99px; padding:1px 7px;"></span></span>
                <span id="summaryServicesTotal" style="font-size:13px; font-weight:600; color:#16a34a;"></span>
            </div>
            <div style="border-top:1.5px dashed #cbd5e1; margin: 10px 0;"></div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-size:14px; font-weight:600; color:#64748b;">Total Amount</span>
                <span id="summaryGrandTotal" style="font-size:15px; font-weight:700; color:#334155;">₱{{ number_format($room->Price_Per_Night, 2) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-size:13px; color:#64748b;">Remaining Balance <span style="font-size:10px; background:#fef3c7; color:#92400e; border-radius:99px; padding:1px 7px;">Pay at Lobby</span></span>
                <span id="summaryBalancedue" style="font-size:13px; font-weight:700; color:#b45309;">₱{{ number_format($room->Price_Per_Night / 2, 2) }}</span>
            </div>
            <div style="border-top:2px solid #003366; margin: 10px 0;"></div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-size:15px; font-weight:800; color:#003366;">Deposit Due Now</span>
                    <div style="font-size:10px; color:#94a3b8;">50% of total · Non-refundable</div>
                </div>
                <span id="summaryDeposit" style="font-size:19px; font-weight:800; color:#003366;">₱{{ number_format($room->Price_Per_Night / 2, 2) }}</span>
            </div>
        </div>

        <div class="form-group" style="margin-top:16px;">
            <label class="payment-method-label" style="font-size:13px; cursor:pointer;">
                <input type="radio" name="payment_method" value="E-Money" checked required>
                <span style="margin-left:8px;">E-Money (GCash / PayMaya)</span>
            </label>
        </div>

        <button type="submit" class="btn-submit" style="margin-top:15px; padding: 14px; font-size: 14px;">Confirm & Pay 50% Deposit</button>
    </div>
</div>
<input type="hidden" name="services_total" id="services_total" value="0">
</form>
</div>
</div>

<script>
const checkIn = document.getElementById('check_in');
const checkOut = document.getElementById('check_out');
const nights = document.getElementById('nights');
const today = new Date().toISOString().split('T')[0];
const roomPricePerNight = {{ $room->Price_Per_Night }};

checkIn.setAttribute('min', today);

function calculateNights() {
    const inDate = new Date(checkIn.value);
    const outDate = new Date(checkOut.value);
    if (checkIn.value && checkOut.value && outDate > inDate) {
        nights.value = Math.round((outDate - inDate) / (1000 * 60 * 60 * 24));
    } else {
        nights.value = 1;
    }
    updateSummary();
}

checkIn.addEventListener('change', function() {
    const nextDay = new Date(this.value);
    nextDay.setDate(nextDay.getDate() + 1);
    checkOut.setAttribute('min', nextDay.toISOString().split('T')[0]);
    calculateNights();
});
checkOut.addEventListener('change', calculateNights);

// Add-on Services Toggle (fixed double-fire bug)
let selectedServices = {};

function toggleService(row, id, price) {
    const cb = row.querySelector('input[type=checkbox]');
    cb.checked = !cb.checked;
    if (cb.checked) {
        row.style.borderColor = '#3b82f6';
        row.style.background = '#eff6ff';
        selectedServices[id] = price;
    } else {
        row.style.borderColor = '#e2e8f0';
        row.style.background = '#f8fafc';
        delete selectedServices[id];
    }
    updateSummary();
}

function formatPHP(amount) {
    return '₱' + parseFloat(amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function updateSummary() {
    const nightCount = parseInt(nights.value) || 1;
    const roomTotal = roomPricePerNight * nightCount;
    const servicesTotal = Object.values(selectedServices).reduce((a, b) => a + parseFloat(b), 0);
    const grandTotal = roomTotal + servicesTotal;
    const deposit = grandTotal * 0.50;
    const balanceDue = grandTotal - deposit;
    const serviceCount = Object.keys(selectedServices).length;

    // Update nights label
    document.getElementById('summaryNights').textContent = '× ' + nightCount;
    // Update room subtotal
    document.getElementById('summaryRoomTotal').textContent = formatPHP(roomTotal);
    // Update services row
    const svcRow = document.getElementById('servicesSummaryRow');
    if (serviceCount > 0) {
        svcRow.style.display = 'flex';
        document.getElementById('servicesCount').textContent = serviceCount;
        document.getElementById('summaryServicesTotal').textContent = '+' + formatPHP(servicesTotal);
    } else {
        svcRow.style.display = 'none';
    }
    // Update totals
    document.getElementById('summaryGrandTotal').textContent = formatPHP(grandTotal);
    document.getElementById('summaryDeposit').textContent = formatPHP(deposit);
    document.getElementById('summaryBalancedue').textContent = formatPHP(balanceDue);
    // Keep hidden input in sync — only send 50% deposit as the amount
    document.getElementById('services_total').value = servicesTotal.toFixed(2);
}

// Initialize on load
updateSummary();

// Accordion toggle
function toggleAccordion(id) {
    const body = document.getElementById(id);
    const arrow = document.getElementById(id + '_arrow');
    const isOpen = body.style.display !== 'none';
    body.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
}
</script>
@include('layouts.chat')
</body>
</html>