<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        body { background: #f5f6fa; }
        .page-wrap { max-width: 860px; margin: 0 auto; padding: 36px 20px; }
        .page-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .page-sub   { font-size: 14px; color: #888; margin-bottom: 28px; }
        .alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;border-radius:8px;padding:12px 16px;font-size:14px;margin-bottom:24px;display:flex;align-items:center;gap:8px; }
        .res-card { background:#fff;border:1px solid #e8e8e8;border-radius:12px;overflow:hidden;margin-bottom:16px;transition:box-shadow .2s; }
        .res-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .card-main { display:flex; }
        .card-img { width:160px;min-height:130px;background:#eee center/cover no-repeat;flex-shrink:0; }
        .card-body { padding:18px 20px;flex:1;display:flex;justify-content:space-between;align-items:flex-start;gap:16px; }
        .room-type { font-size:16px;font-weight:700;color:#1a1a2e;margin-bottom:8px; }
        .info-row { font-size:13px;color:#555;margin-bottom:4px; }
        .info-row span { color:#1a1a2e;font-weight:600; }
        .total { font-size:15px;font-weight:700;color:#003366;margin-top:10px; }
        .badge { display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600; }
        .badge-pending    { background:#fef9c3;color:#854d0e; }
        .badge-confirmed  { background:#dcfce7;color:#166534; }
        .badge-cancelled  { background:#fee2e2;color:#991b1b; }
        .badge-checkedout { background:#dbeafe;color:#1e40af; }
        .badge-default    { background:#f1f5f9;color:#475569; }
        .card-actions { display:flex;flex-direction:column;align-items:flex-end;gap:10px;padding-top:2px; }
        .btn-receipt { font-size:13px;font-weight:600;background:#f1c40f;color:#333;border:none;border-radius:7px;padding:7px 14px;text-decoration:none;white-space:nowrap;cursor:pointer; }
        .btn-receipt:hover { background:#d4ac0d; }
        .btn-services { font-size:12px;font-weight:600;background:transparent;color:#003366;border:1px solid #003366;border-radius:7px;padding:6px 13px;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:5px; }
        .btn-services:hover { background:#003366;color:#fff; }
        .btn-services i { transition:transform .25s; }
        .btn-services.open i { transform:rotate(180deg); }
        .services-panel { display:none;border-top:1px solid #f0f0f0;padding:14px 20px;background:#fafafa; }
        .services-panel.open { display:block; }
        .services-label { font-size:12px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px; }
        .service-item { display:flex;justify-content:space-between;align-items:center;padding:8px 12px;background:#fff;border:1px solid #eee;border-radius:8px;margin-bottom:6px;font-size:13px;color:#333; }
        .service-name { display:flex;align-items:center;gap:8px; }
        .service-name i { color:#003366;width:16px;text-align:center; }
        .service-qty { color:#888;font-size:12px; }
        .service-price { font-weight:700;color:#003366; }
        .empty-state { background:#fff;border:1px solid #e8e8e8;border-radius:12px;text-align:center;padding:56px 24px; }
        .empty-icon { font-size:40px;color:#ddd;margin-bottom:14px; }
        .empty-title { font-size:16px;font-weight:700;color:#333;margin-bottom:6px; }
        .empty-sub { font-size:14px;color:#888;margin-bottom:20px; }
        .btn-browse { display:inline-block;padding:10px 22px;background:#f1c40f;color:#333;font-weight:700;border-radius:8px;text-decoration:none;font-size:14px; }
        .btn-browse:hover { background:#d4ac0d; }
    </style>
</head>
<body>

    @include('layouts.header')

    <div class="page-wrap">

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="page-title">My Reservations</div>
        <div class="page-sub">View your past and upcoming stays.</div>

        @if(count($reservations) > 0)
            @foreach($reservations as $res)
            <div class="res-card">
                <div class="card-main">
                    <div class="card-img" style="{{ $res->Picture_Url ? 'background-image:url(' . asset($res->Picture_Url) . ')' : 'background:#eee' }}"></div>
                    <div class="card-body">
                        <div>
                            <div class="room-type">{{ $res->Room_Type }}</div>
                            <div class="info-row">Check-in: <span>{{ \Carbon\Carbon::parse($res->Check_In_Date)->format('M d, Y') }}</span></div>
                            <div class="info-row">Check-out: <span>{{ \Carbon\Carbon::parse($res->Check_Out_Date)->format('M d, Y') }}</span></div>
                            <div class="total">₱{{ number_format($res->Total_Amount, 2) }}</div>
                        </div>
                        <div class="card-actions">
                            @if($res->Status == 'Pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif(in_array($res->Status, ['Confirmed','Booked']))
                                <span class="badge badge-confirmed">Confirmed</span>
                            @elseif($res->Status == 'Cancelled')
                                <span class="badge badge-cancelled">Cancelled</span>
                            @elseif($res->Status == 'Checked Out')
                                <span class="badge badge-checkedout">Checked Out</span>
                            @else
                                <span class="badge badge-default">{{ $res->Status }}</span>
                            @endif

                            @if($res->PAYMENT_ID)
                                <a href="/receipt/{{ $res->RESERVATION_ID }}" class="btn-receipt">
                                    <i class="fas fa-receipt"></i> Receipt
                                </a>
                            @endif

                            @if(!empty($res->services) && count($res->services) > 0)
                                <button class="btn-services" onclick="toggleServices(this, 'services-{{ $res->RESERVATION_ID }}')">
                                    <i class="fas fa-chevron-down"></i> Services
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                @if(!empty($res->services) && count($res->services) > 0)
                <div class="services-panel" id="services-{{ $res->RESERVATION_ID }}">
                    <div class="services-label">Add-on Services</div>
                    @foreach($res->services as $svc)
                    @php
                        $icons = ['Transport'=>'fa-car','Dining'=>'fa-utensils','Accommodation'=>'fa-bed','Housekeeping'=>'fa-broom','Wellness'=>'fa-spa','Activities'=>'fa-map-marked-alt'];
                        $icon = $icons[$svc->Category ?? ''] ?? 'fa-concierge-bell';
                    @endphp
                    <div class="service-item">
                        <div class="service-name">
                            <i class="fas {{ $icon }}"></i>
                            {{ $svc->Service_Name }}
                            @if(isset($svc->Quantity) && $svc->Quantity > 1)
                                <span class="service-qty">×{{ $svc->Quantity }}</span>
                            @endif
                        </div>
                        <div class="service-price">₱{{ number_format($svc->Price * ($svc->Quantity ?? 1), 2) }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                <div class="empty-title">No reservations yet</div>
                <div class="empty-sub">Ready for a getaway? Explore our rooms.</div>
                <a href="/rooms" class="btn-browse">Browse Rooms</a>
            </div>
        @endif
    </div>

    @include('layouts.footer')
    @include('layouts.chat')

    <script>
    function toggleServices(btn, panelId) {
        const panel = document.getElementById(panelId);
        const isOpen = panel.classList.contains('open');
        panel.classList.toggle('open', !isOpen);
        btn.classList.toggle('open', !isOpen);
    }
    </script>

</body>
</html>