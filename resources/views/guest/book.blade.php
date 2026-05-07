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
                <input type="text" name="full_name" placeholder="Juan Dela Cruz" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="+63 912 345 6789" required>
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
        <div class="services-scroll-area">
            @php
                $services = \Illuminate\Support\Facades\DB::table('services')->orderBy('Service_Category')->get();
                $grouped = $services->groupBy('Service_Category');
            @endphp

            @forelse($grouped as $category => $items)
                <div style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin: 12px 0 6px; letter-spacing: 0.5px;">{{ $category }}</div>
                @foreach($items as $svc)
                <div class="service-item" style="display:flex; align-items:center; justify-content:space-between; border:1.5px solid #e2e8f0; border-radius:8px; background:#f8fafc; padding:10px 12px; margin-bottom:6px; cursor:pointer;" onclick="toggleService(this, {{ $svc->SERVICES_ID }}, {{ $svc->Price }})">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <input type="checkbox" name="services[]" value="{{ $svc->SERVICES_ID }}" id="svc_{{ $svc->SERVICES_ID }}" onclick="event.stopPropagation(); toggleService(this.closest('.service-item'), {{ $svc->SERVICES_ID }}, {{ $svc->Price }})">
                        <span style="font-size:13px; font-weight:600; color:#334155;">{{ $svc->Service_Name }}</span>
                    </div>
                    <span style="font-size:13px; font-weight:700; color:#16a34a;">+₱{{ number_format($svc->Price, 0) }}</span>
                </div>
                @endforeach
            @empty
                <p style="font-size:12px; color:#94a3b8;">No extra services available.</p>
            @endforelse
        </div>

        <span class="section-label" style="margin-top:20px !important;">Summary & Payment</span>
        <div class="payment-summary-box" style="margin-top:0; padding: 18px; border-radius:12px;">
            <p style="font-size:14px; margin-bottom: 5px;">Room Price: <strong>₱{{ number_format($room->Price_Per_Night, 2) }} / night</strong></p>
            <div id="servicesSummary" style="display:none; font-size:13px; color:#1d4ed8; padding-top:8px; border-top:1px solid #cbd5e1; margin-top:8px;">
                <i class="fa-solid fa-plus-circle"></i> <span id="servicesSummaryText"></span>
            </div>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label class="payment-method-label" style="font-size:13px; cursor:pointer;">
                <input type="radio" name="payment_method" value="E-Money" checked required>
                <span style="margin-left:8px;">E-Money (GCash / PayMaya)</span>
            </label>
        </div>

        <button type="submit" class="btn-submit" style="margin-top:15px; padding: 14px; font-size: 14px;">Confirm & Proceed to Payment</button>
    </div>
</div>
<input type="hidden" name="services_total" id="services_total" value="0">
</form>
</div>
</div>

<script>
// Date & Night Calculation
const checkIn = document.getElementById('check_in');
const checkOut = document.getElementById('check_out');
const nights = document.getElementById('nights');
const today = new Date().toISOString().split('T')[0];
checkIn.setAttribute('min', today);

function calculateNights() {
    const inDate = new Date(checkIn.value);
    const outDate = new Date(checkOut.value);
    if (checkIn.value && checkOut.value && outDate > inDate) {
        nights.value = Math.round((outDate - inDate) / (1000 * 60 * 60 * 24));
    } else { nights.value = 1; }
}

checkIn.addEventListener('change', function() {
    const nextDay = new Date(this.value);
    nextDay.setDate(nextDay.getDate() + 1);
    checkOut.setAttribute('min', nextDay.toISOString().split('T')[0]);
    calculateNights();
});
checkOut.addEventListener('change', calculateNights);

// Add-on Services Toggle
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
    updateServicesSummary();
}

function updateServicesSummary() {
    const ids = Object.keys(selectedServices);
    const total = Object.values(selectedServices).reduce((a, b) => a + parseFloat(b), 0);
    const summ = document.getElementById('servicesSummary');
    const hid = document.getElementById('services_total');
    if (ids.length > 0) {
        summ.style.display = 'block';
        document.getElementById('servicesSummaryText').textContent = ids.length + ' service(s) added: +₱' + total.toFixed(2);
        hid.value = total.toFixed(2);
    } else { 
        summ.style.display = 'none'; 
        hid.value = '0'; 
    }
}
</script>
@include('layouts.chat')
</body>
</html>