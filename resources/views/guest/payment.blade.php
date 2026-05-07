<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Secure Payment - Ragadio Plaza Hotel</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
<style>
.payment-body{
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
margin:0;
padding:10px;
background-color:#f1f5f9;
box-sizing:border-box;
overflow:hidden;
}
.receipt-card{
background:#ffffff;
width:100%;
max-width:400px;
padding:20px 30px; /* Increased side padding to 30px */
border-radius:16px;
box-shadow:0 10px 30px rgba(0,0,0,0.04);
border:1px solid rgba(226, 232, 240, 0.8);
box-sizing:border-box;
}
.receipt-header{
text-align:center;
margin-bottom:12px;
}
.receipt-header h2{
font-size:18px;
margin:0;
color:#0f172a;
font-weight:800;
letter-spacing:-0.3px;
}
.receipt-header p{
font-size:10px;
margin:2px 0 0;
color:#94a3b8;
font-weight:500;
}
.receipt-total-hero{
text-align:center;
margin:12px 0;
padding:12px;
background:#1e293b;
border-radius:12px;
color:#ffffff;
}
.receipt-total-hero .label{
display:block;
font-size:9px;
text-transform:uppercase;
letter-spacing:0.8px;
opacity:0.6;
margin-bottom:2px;
}
.receipt-total-hero .amount{
font-size:28px;
font-weight:800;
}
.receipt-details{
margin-bottom:12px;
padding:0 5px; /* Added padding to move details away from the edges */
}
.receipt-row{
display:flex;
justify-content:space-between;
align-items:center;
padding:8px 0;
border-bottom:1px solid #f1f5f9;
font-size:12px;
}
.receipt-row:last-child{border-bottom:none;}
.receipt-row .label{
color:#64748b;
font-weight:500;
display:flex;
align-items:center;
}
.receipt-row .label i{
margin-right:10px; /* Added space between icon and text */
width:16px;
text-align:center;
color:#94a3b8;
}
.receipt-row .value{
font-weight:700;
color:#1e293b;
}
.services-card{
background:#f8fafc;
border-radius:10px;
padding:12px 15px; /* Increased internal padding */
margin:12px 0;
border:1px solid #e2e8f0;
}
.services-title{
font-size:9px;
font-weight:700;
text-transform:uppercase;
color:#94a3b8;
margin-bottom:6px;
display:block;
}
.service-line{
display:flex;
justify-content:space-between;
font-size:11px;
margin-bottom:4px;
color:#334155;
}
.service-line .price{color:#10b981;font-weight:700;}
.input-section{
margin-top:12px;
}
.input-label{
display:block;
font-size:10px;
font-weight:700;
color:#64748b;
margin-bottom:4px;
text-transform:uppercase;
}
.payment-input{
width:100%;
padding:10px;
border:1.5px solid #e2e8f0;
border-radius:8px;
font-size:15px;
font-weight:700;
color:#1e293b;
outline:none;
box-sizing:border-box;
text-align:center;
background:#fcfdfe;
}
.btn-confirm{
width:100%;
background:#0f172a;
color:#ffffff;
border:none;
padding:12px;
border-radius:8px;
font-weight:700;
font-size:13px;
cursor:pointer;
margin-top:14px;
text-transform:uppercase;
letter-spacing:0.5px;
}
.cancel-link{
display:block;
text-align:center;
font-size:11px;
color:#94a3b8;
text-decoration:none;
margin-top:12px;
font-weight:600;
}
.cancel-link:hover{color:#ef4444;}
</style>
</head>
<body class="payment-body">
<div class="receipt-card">
<div class="receipt-header">
<h2>Secure Payment</h2>
<p>Ragadio Plaza Hotel Reservation</p>
</div>
<div class="receipt-total-hero">
<span class="label">Total Amount Due</span>
<div class="amount">₱{{ number_format($amount, 2) }}</div>
</div>
<div class="receipt-details">
<div class="receipt-row">
<span class="label"><i class="fa-solid fa-bed" style="padding: 10px;"></i> Room Type</span>
<span class="value">{{ $room_type }}</span>
</div>
<div class="receipt-row">
<span class="label"><i class="fa-solid fa-moon" style="padding: 10px;"></i> Nights</span>
<span class="value">{{ $nights }}</span>
</div>
<div class="receipt-row">
<span class="label"><i class="fa-solid fa-credit-card" style="padding: 10px;"></i> Payment Method</span>
<span class="value">{{ $payment_method }}</span>
</div>
</div>
@if(!empty($selected_services) && count($selected_services) > 0)
<div class="services-card">
<span class="services-title">Add-On Services</span>
@foreach($selected_services as $svc)
<div class="service-line">
<span>{{ $svc->Service_Name }}</span>
<span class="price">+₱{{ number_format($svc->Price, 2) }}</span>
</div>
@endforeach
<div class="service-line" style="margin-top:6px;padding-top:6px;border-top:1px solid #e2e8f0;font-weight:700;">
<span>Services Subtotal</span>
<span class="price">+₱{{ number_format($services_total ?? 0, 2) }}</span>
</div>
</div>
@endif
<form action="/book-final-submit" method="POST">
@csrf
<input type="hidden" name="room_id" value="{{ $room_id }}">
<input type="hidden" name="check_in" value="{{ $check_in }}">
<input type="hidden" name="check_out" value="{{ $check_out }}">
<input type="hidden" name="amount" value="{{ $amount }}">
<input type="hidden" name="payment_method" value="{{ $payment_method }}">
<input type="hidden" name="guests" value="{{ request('guests') }}">
<input type="hidden" name="service_ids" value="{{ implode(',', array_map(fn($s) => is_object($s) ? $s->SERVICES_ID : $s, $service_ids ?? [])) }}">
<div class="input-section">
<label class="input-label" style="padding: 15px;">Amount Paid (₱)</label>
<input type="number" name="amount_paid" class="payment-input" step="0.01" min="{{ $amount }}" value="{{ $amount }}" required>
</div>
<button type="submit" class="btn-confirm">Confirm & Submit</button>
</form>
<a href="/book/{{ $room_id }}" class="cancel-link">Cancel and return</a>
</div>
</body>
</html>