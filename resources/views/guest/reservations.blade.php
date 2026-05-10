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
        body { background: #f1f5f9; }

        .page-wrap { max-width: 1100px; margin: 0 auto; padding: 40px 32px 60px; }

        /* Page header */
        .page-header { margin-bottom: 32px; }
        .page-title  { font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .page-sub    { font-size: 14px; color: #94a3b8; font-weight: 500; }

        /* Alert */
        .alert-success {
            background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;
            border-left: 4px solid #22c55e; border-radius: 10px;
            padding: 13px 18px; font-size: 14px; margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px; font-weight: 600;
        }

        /* ── Card ── */
        .res-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            transition: box-shadow .2s, transform .2s;
        }
        .res-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.09); transform: translateY(-1px); }

        .card-main { display: flex; min-height: 140px; }

        /* Image */
        .card-img {
            width: 160px;
            min-height: 160px;
            height: 100%;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            border-radius: 0;
        }
        .card-img-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to right, transparent 60%, rgba(0,0,0,0.18));
        }
        .card-img-placeholder {
            width: 100%; height: 100%; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 8px;
            color: #94a3b8; font-size: 12px; font-weight: 600;
        }
        .card-img-placeholder i { font-size: 28px; color: #cbd5e1; }

        /* Body */
        .card-body {
            padding: 20px 22px;
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .card-left { flex: 1; }

        /* Room title row */
        .room-title-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap; }
        .room-type { font-size: 17px; font-weight: 800; color: #0f172a; }
        .room-number-tag {
            font-size: 11px; font-weight: 700; color: #64748b;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            border-radius: 6px; padding: 2px 8px;
        }

        /* Info rows */
        .info-grid { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 14px; }
        .info-item { display: flex; flex-direction: column; gap: 2px; }
        .info-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 13px; font-weight: 700; color: #1e293b; }

        /* Amount row */
        .amount-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .amount-deposit {
            font-size: 16px; font-weight: 800; color: #0f172a;
        }
        .amount-due {
            font-size: 12px; font-weight: 700; color: #dc2626;
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 6px; padding: 3px 8px;
        }
        .amount-paid {
            font-size: 16px; font-weight: 800; color: #16a34a;
        }
        .amount-paid-tag {
            font-size: 12px; font-weight: 700; color: #16a34a;
            display: inline-flex; align-items: center; gap: 4px;
        }

        /* Right actions */
        .card-right {
            display: flex; flex-direction: column;
            align-items: flex-end; gap: 10px; padding-top: 2px; min-width: 130px;
        }

        /* Status badges */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 13px; border-radius: 20px;
            font-size: 12px; font-weight: 700; white-space: nowrap;
        }
        .badge-pending   { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .badge-confirmed { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-checkedout{ background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .badge-default   { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* Payment status tag */
        .pay-tag {
            font-size: 11px; font-weight: 700; padding: 3px 10px;
            border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;
        }
        .pay-tag-deposit { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .pay-tag-paid    { background: #dcfce7; color: #166534; border: 1px solid #86efac; }

        /* Buttons */
        .btn-receipt {
            font-size: 12px; font-weight: 700;
            background: #f1c40f; color: #333; border: none;
            border-radius: 8px; padding: 8px 14px;
            text-decoration: none; white-space: nowrap; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .2s;
        }
        .btn-receipt:hover { background: #d4ac0d; }

        .btn-cancel {
            font-size: 12px; font-weight: 700;
            background: #fff; color: #dc2626;
            border: 1px solid #fca5a5; border-radius: 8px;
            padding: 7px 13px; cursor: pointer; white-space: nowrap;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all .2s;
        }
        .btn-cancel:hover { background: #fee2e2; border-color: #dc2626; }

        .btn-services {
            font-size: 12px; font-weight: 700;
            background: transparent; color: #003366;
            border: 1px solid #003366; border-radius: 8px;
            padding: 7px 13px; cursor: pointer; white-space: nowrap;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all .2s;
        }
        .btn-services:hover { background: #003366; color: #fff; }
        .btn-services i { transition: transform .25s; }
        .btn-services.open i { transform: rotate(180deg); }

        /* Services panel */
        .services-panel { display: none; border-top: 1px solid #f1f5f9; padding: 16px 22px; background: #fafafa; }
        .services-panel.open { display: block; }
        .services-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 10px; }
        .service-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 9px 14px; background: #fff;
            border: 1px solid #e2e8f0; border-radius: 10px;
            margin-bottom: 6px; font-size: 13px; color: #333;
        }
        .service-name { display: flex; align-items: center; gap: 8px; }
        .service-name i { color: #003366; width: 16px; text-align: center; }
        .service-qty { color: #94a3b8; font-size: 12px; }
        .service-price { font-weight: 700; color: #003366; }

        /* Empty state */
        .empty-state {
            background: #fff; border: 1px solid #e2e8f0;
            border-radius: 18px; text-align: center; padding: 64px 24px;
        }
        .empty-icon  { font-size: 44px; color: #e2e8f0; margin-bottom: 16px; }
        .empty-title { font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .empty-sub   { font-size: 14px; color: #94a3b8; margin-bottom: 24px; }
        .btn-browse  {
            display: inline-block; padding: 11px 24px;
            background: #f1c40f; color: #333; font-weight: 700;
            border-radius: 10px; text-decoration: none; font-size: 14px;
        }
        .btn-browse:hover { background: #d4ac0d; }

        /* Cancel confirm modal */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 9999;
            align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: #fff; border-radius: 16px;
            padding: 28px 32px; max-width: 400px; width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            text-align: center;
        }
        .modal-icon { font-size: 40px; color: #dc2626; margin-bottom: 12px; }
        .modal-title { font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        .modal-sub { font-size: 13px; color: #64748b; margin-bottom: 24px; line-height: 1.5; }
        .modal-btns { display: flex; gap: 10px; justify-content: center; }
        .modal-btn-cancel {
            flex: 1; padding: 10px; background: #fee2e2; color: #dc2626;
            border: 1px solid #fca5a5; border-radius: 10px;
            font-weight: 700; font-size: 14px; cursor: pointer;
        }
        .modal-btn-cancel:hover { background: #dc2626; color: #fff; }
        .modal-btn-back {
            flex: 1; padding: 10px; background: #f1f5f9; color: #475569;
            border: 1px solid #e2e8f0; border-radius: 10px;
            font-weight: 700; font-size: 14px; cursor: pointer;
        }
        .modal-btn-back:hover { background: #e2e8f0; }
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

        <div class="page-header">
            <div class="page-title"><i class="fa-solid fa-calendar-check" style="color:#003366;margin-right:8px;font-size:22px;"></i>My Reservations</div>
            <div class="page-sub">View your past and upcoming stays.</div>
        </div>

        {{-- Filter Buttons --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px;">
            @php $activeFilter = request('status', 'all'); @endphp
            @foreach([
                'all'         => ['label' => 'All',          'icon' => 'fa-list'],
                'Pending'     => ['label' => 'Pending',      'icon' => 'fa-clock'],
                'Confirmed'   => ['label' => 'Confirmed',    'icon' => 'fa-circle-check'],
                'Checked Out' => ['label' => 'Checked Out',  'icon' => 'fa-door-open'],
                'Cancelled'   => ['label' => 'Cancelled',    'icon' => 'fa-xmark'],
            ] as $value => $meta)
                @php $isActive = $activeFilter === $value; @endphp
                <a href="{{ request()->fullUrlWithQuery(['status' => $value, 'page' => 1]) }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:50px;font-size:13px;font-weight:700;text-decoration:none;transition:all 0.2s;
                          {{ $isActive
                            ? 'background:#003366;color:#fff;border:1px solid #003366;'
                            : 'background:#fff;color:#64748b;border:1px solid #e2e8f0;' }}">
                    <i class="fa-solid {{ $meta['icon'] }}" style="font-size:11px;"></i>
                    {{ $meta['label'] }}
                </a>
            @endforeach
        </div>

        @if(count($reservations) > 0)
            @foreach($reservations as $res)
            @php
                $deposit   = $res->Total_Amount;
                $balance   = $deposit;
                $fullPrice = $deposit * 2;
                $payStatus = $res->Payment_Status ?? '50% Deposit';
                $isFullyPaid = $payStatus === 'Fully Paid';
            @endphp
            <div class="res-card">
                <div class="card-main">

                    {{-- Room Image --}}
                    <div class="card-img">
                        @if($res->Picture_Url)
                            <img src="{{ $res->Picture_Url }}" alt="{{ $res->Room_Type }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                        @else
                            <div class="card-img-placeholder">
                                <i class="fa-solid fa-bed"></i>
                                No Image
                            </div>
                        @endif
                        <div class="card-img-overlay"></div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body">
                        <div class="card-left">

                            {{-- Room Title --}}
                            <div class="room-title-row">
                                <span class="room-type">{{ $res->Room_Type }}</span>
                                @if($res->Room_Number)
                                    <span class="room-number-tag">#{{ $res->Room_Number }}</span>
                                @endif
                            </div>

                            {{-- Countdown for Pending/Confirmed --}}
                            @if(in_array($res->Status, ['Pending', 'Confirmed', 'Booked']))
                                @php
                                    $daysUntil = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($res->Check_In_Date)->startOfDay(), false);
                                @endphp
                                @if($daysUntil > 0)
                                    <div style="display:inline-flex;align-items:center;gap:5px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:4px 10px;font-size:12px;font-weight:700;color:#1d4ed8;margin-bottom:8px;">
                                        <i class="fa-solid fa-hourglass-half" style="font-size:10px;"></i>
                                        Check-in in {{ $daysUntil }} day{{ $daysUntil != 1 ? 's' : '' }}
                                    </div>
                                @elseif($daysUntil == 0)
                                    <div style="display:inline-flex;align-items:center;gap:5px;background:#fef9c3;border:1px solid #fde047;border-radius:8px;padding:4px 10px;font-size:12px;font-weight:700;color:#854d0e;margin-bottom:8px;">
                                        <i class="fa-solid fa-bell" style="font-size:10px;"></i>
                                        Check-in is Today!
                                    </div>
                                @endif
                            @endif

                            {{-- Info Grid --}}
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label"><i class="fa-solid fa-right-to-bracket" style="font-size:9px;"></i> Check-In</span>
                                    <span class="info-value">{{ \Carbon\Carbon::parse($res->Check_In_Date)->format('M d, Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fa-solid fa-right-from-bracket" style="font-size:9px;"></i> Check-Out</span>
                                    <span class="info-value">{{ \Carbon\Carbon::parse($res->Check_Out_Date)->format('M d, Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fa-solid fa-moon" style="font-size:9px;"></i> Nights</span>
                                    <span class="info-value">{{ \Carbon\Carbon::parse($res->Check_In_Date)->diffInDays(\Carbon\Carbon::parse($res->Check_Out_Date)) }}</span>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div class="amount-row">
                                @if($isFullyPaid)
                                    <span class="amount-paid">₱{{ number_format($fullPrice, 2) }}</span>
                                    <span class="amount-paid-tag"><i class="fa-solid fa-circle-check" style="font-size:10px;"></i> Fully Paid</span>
                                @else
                                    <span class="amount-deposit">₱{{ number_format($deposit, 2) }}</span>
                                    <span class="amount-due"><i class="fa-solid fa-clock" style="font-size:9px;"></i> +₱{{ number_format($balance, 2) }} due at check-in</span>
                                @endif
                            </div>

                        </div>

                        {{-- Right Actions --}}
                        <div class="card-right">

                            {{-- Booking Status Badge --}}
                            @if($res->Status == 'Pending')
                                <span class="badge badge-pending"><i class="fa-solid fa-clock" style="font-size:9px;"></i> Pending</span>
                            @elseif(in_array($res->Status, ['Confirmed','Booked']))
                                <span class="badge badge-confirmed"><i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Confirmed</span>
                            @elseif($res->Status == 'Cancelled')
                                <span class="badge badge-cancelled"><i class="fa-solid fa-xmark" style="font-size:9px;"></i> Cancelled</span>
                            @elseif($res->Status == 'Checked Out')
                                <span class="badge badge-checkedout"><i class="fa-solid fa-door-open" style="font-size:9px;"></i> Checked Out</span>
                            @else
                                <span class="badge badge-default">{{ $res->Status }}</span>
                            @endif

                            {{-- Payment Status Tag --}}
                            @if($res->Status !== 'Cancelled')
                                @if($isFullyPaid)
                                    <span class="pay-tag pay-tag-paid"><i class="fa-solid fa-peso-sign" style="font-size:9px;"></i> Fully Paid</span>
                                @else
                                    <span class="pay-tag pay-tag-deposit"><i class="fa-solid fa-percent" style="font-size:9px;"></i> 50% Deposit</span>
                                @endif
                            @endif

                            {{-- Receipt Button --}}
                            @if($res->PAYMENT_ID)
                                <a href="/receipt/{{ $res->RESERVATION_ID }}" class="btn-receipt">
                                    <i class="fas fa-receipt"></i> Receipt
                                </a>
                            @endif

                            {{-- Services Toggle --}}
                            @if(!empty($res->services) && count($res->services) > 0)
                                <button class="btn-services" onclick="toggleServices(this, 'services-{{ $res->RESERVATION_ID }}')">
                                    <i class="fas fa-chevron-down"></i> Services
                                </button>
                            @endif

                            {{-- Cancel Button (only for Pending) --}}
                            @if($res->Status == 'Pending')
                                <button class="btn-cancel" onclick="openCancelModal({{ $res->RESERVATION_ID }})">
                                    <i class="fa-solid fa-xmark" style="font-size:10px;"></i> Cancel
                                </button>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- Services Panel --}}
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

            {{-- Pagination --}}
            @if(method_exists($reservations, 'links') && $reservations->lastPage() > 1)
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:24px;padding:16px 4px;border-top:1px solid #e2e8f0;flex-wrap:wrap;gap:12px;">
                <span style="font-size:13px;color:#64748b;font-weight:500;">
                    Showing {{ $reservations->firstItem() }}–{{ $reservations->lastItem() }} of {{ $reservations->total() }} reservations
                </span>
                <div style="display:flex;align-items:center;gap:6px;">
                    {{-- Previous --}}
                    @if($reservations->onFirstPage())
                        <span style="padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;background:#f1f5f9;color:#cbd5e1;border:1px solid #e2e8f0;cursor:not-allowed;">‹ Prev</span>
                    @else
                        <a href="{{ $reservations->previousPageUrl() }}" style="padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;background:#fff;color:#003366;border:1px solid #e2e8f0;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#003366';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.color='#003366'">‹ Prev</a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($reservations->getUrlRange(1, $reservations->lastPage()) as $page => $url)
                        @if($page == $reservations->currentPage())
                            <span style="padding:7px 13px;border-radius:8px;font-size:13px;font-weight:700;background:#003366;color:#fff;border:1px solid #003366;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="padding:7px 13px;border-radius:8px;font-size:13px;font-weight:600;background:#fff;color:#003366;border:1px solid #e2e8f0;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#003366';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.color='#003366'">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($reservations->hasMorePages())
                        <a href="{{ $reservations->nextPageUrl() }}" style="padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;background:#fff;color:#003366;border:1px solid #e2e8f0;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#003366';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.color='#003366'">Next ›</a>
                    @else
                        <span style="padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;background:#f1f5f9;color:#cbd5e1;border:1px solid #e2e8f0;cursor:not-allowed;">Next ›</span>
                    @endif
                </div>
            </div>
            @endif

        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                <div class="empty-title">No reservations yet</div>
                <div class="empty-sub">Ready for a getaway? Explore our rooms and book your stay.</div>
                <a href="/rooms" class="btn-browse"><i class="fa-solid fa-magnifying-glass" style="margin-right:6px;"></i>Browse Rooms</a>
            </div>
        @endif
    </div>

    {{-- Cancel Confirmation Modal --}}
    <div class="modal-overlay" id="cancelModal" onclick="closeCancelModal(event)">
        <div class="modal-box">
            <div class="modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="modal-title">Cancel Reservation?</div>
            <div class="modal-sub">Are you sure you want to cancel this booking? This action cannot be undone and the room will be released. <strong style="color:#dc2626;">Your 50% deposit will not be refunded.</strong></div>
            <div class="modal-btns">
                <form id="cancelForm" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status" value="Cancelled">
                    <button type="submit" class="modal-btn-cancel" style="width:100%;">
                        <i class="fa-solid fa-xmark"></i> Yes, Cancel
                    </button>
                </form>
                <button class="modal-btn-back" onclick="document.getElementById('cancelModal').classList.remove('open')">
                    Go Back
                </button>
            </div>
        </div>
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

    function openCancelModal(reservationId) {
        document.getElementById('cancelForm').action = '/reservations/cancel/' + reservationId;
        document.getElementById('cancelModal').classList.add('open');
    }

    function closeCancelModal(e) {
        if (e.target === document.getElementById('cancelModal')) {
            document.getElementById('cancelModal').classList.remove('open');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') document.getElementById('cancelModal').classList.remove('open');
    });
    </script>

</body>
</html>