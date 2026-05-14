<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/staff_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@if(session('staff_role') === 'Admin')
@include('staff.admin-sidebar')
@else
@include('staff.sidebar')
@endif


<div class="container" style="padding: 32px 36px;">
    <h3 class="section-title"><span style="margin-left: 10px;"></span><i class="fa-solid fa-calendar-check"></i> Manage Reservations</h3>

    @if(session('payment_confirmed'))
        @php $p = session('payment_confirmed'); @endphp
        <div id="paymentNotif" style="
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #86efac;
            border-left: 5px solid #16a34a;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 18px;
            box-shadow: 0 4px 16px rgba(22, 163, 74, 0.10);
            position: relative;
        ">
            {{-- Icon --}}
            <div style="
                background-color: #16a34a;
                color: #fff;
                border-radius: 50%;
                width: 44px;
                height: 44px;
                min-width: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                margin-top: 2px;
            ">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            {{-- Content --}}
            <div style="flex: 1;">
                <div style="font-size: 15px; font-weight: 700; color: #15803d; margin-bottom: 4px;">
                    Payment Received — Reservation #{{ $p['reservation_no'] }} Confirmed
                </div>
                <div style="font-size: 13px; color: #166534; margin-bottom: 12px;">
                    {{ $p['guest_name'] }}'s booking for <strong>{{ $p['room'] }}</strong> has been successfully confirmed.
                </div>

                {{-- Details Row --}}
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div style="
                        background: #fff;
                        border: 1px solid #bbf7d0;
                        border-radius: 8px;
                        padding: 8px 14px;
                        font-size: 12.5px;
                        color: #166534;
                        display: flex;
                        align-items: center;
                        gap: 7px;
                    ">
                        <i class="fa-solid fa-peso-sign" style="color: #16a34a;"></i>
                        <span><strong>Amount:</strong> ₱{{ $p['amount'] }}</span>
                    </div>
                    <div style="
                        background: #fff;
                        border: 1px solid #bbf7d0;
                        border-radius: 8px;
                        padding: 8px 14px;
                        font-size: 12.5px;
                        color: #166534;
                        display: flex;
                        align-items: center;
                        gap: 7px;
                    ">
                        <i class="fa-solid fa-credit-card" style="color: #16a34a;"></i>
                        <span><strong>Method:</strong> {{ $p['method'] }}</span>
                    </div>
                    <div style="
                        background: #fff;
                        border: 1px solid #bbf7d0;
                        border-radius: 8px;
                        padding: 8px 14px;
                        font-size: 12.5px;
                        color: #166534;
                        display: flex;
                        align-items: center;
                        gap: 7px;
                    ">
                        <i class="fa-solid fa-user-check" style="color: #16a34a;"></i>
                        <span><strong>Confirmed by:</strong> {{ $p['confirmed_by'] }}</span>
                    </div>
                    <div style="
                        background: #fff;
                        border: 1px solid #bbf7d0;
                        border-radius: 8px;
                        padding: 8px 14px;
                        font-size: 12.5px;
                        color: #166534;
                        display: flex;
                        align-items: center;
                        gap: 7px;
                    ">
                        <i class="fa-solid fa-clock" style="color: #16a34a;"></i>
                        <span>{{ $p['confirmed_at'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Close button --}}
            <button onclick="document.getElementById('paymentNotif').remove()" style="
                position: absolute;
                top: 14px;
                right: 16px;
                background: none;
                border: none;
                color: #16a34a;
                font-size: 16px;
                cursor: pointer;
                opacity: 0.7;
                line-height: 1;
            " title="Dismiss">&times;</button>
        </div>

        <script>
            setTimeout(() => {
                const el = document.getElementById('paymentNotif');
                if (el) {
                    el.style.transition = 'opacity 0.6s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 650);
                }
            }, 7000);
        </script>
    @endif

    @if(session('success'))
        <div id="successAlert" style="
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-left: 5px solid #0284c7;
            color: #0c4a6e;
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);
        ">
            <i class="fa-solid fa-circle-info" style="font-size: 16px; color: #0284c7;"></i>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const el = document.getElementById('successAlert');
                if (el) {
                    el.style.transition = 'opacity 0.5s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }
            }, 4000);
        </script>
    @endif

    <style>
        body { overflow-y: auto !important; overflow-x: hidden; }

        .res-table-wrap {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow-x: auto;
            overflow-y: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        .res-table-wrap table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .res-table-wrap th {
            background: #f8fafc;
            color: #64748b;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .res-table-wrap td {
            padding: 12px 10px;
            font-size: 12.5px;
            color: #475569;
            font-weight: 500;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .res-table-wrap tr:last-child td { border-bottom: none; }
        .res-table-wrap tr:hover td { background: #fafbff; }

        .res-id {
            font-size: 12px; font-weight: 800; color: #1e293b;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            padding: 4px 10px; border-radius: 8px; letter-spacing: 0.3px; white-space: nowrap;
        }
        .res-guest-name { font-weight: 700; color: #0f172a; font-size: 13.5px; text-transform: capitalize; }
        .res-room-type   { font-weight: 700; color: #0f172a; font-size: 13px; }
        .res-room-number { font-size: 11px; color: #94a3b8; font-weight: 600; margin-top: 2px; }

        .res-method {
            display: inline-flex; align-items: center; gap: 5px;
            background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd;
            border-radius: 20px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; white-space: nowrap;
        }
        .res-amount { font-weight: 800; color: #0f172a; font-size: 14px; white-space: nowrap; }
        .res-date { font-size: 13px; color: #475569; white-space: nowrap; }
        .res-date-label { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        .res-actions { display: flex; flex-direction: column; gap: 6px; align-items: flex-start; min-width: 120px; }

        .res-btn-receipt {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11.5px; font-weight: 700; color: #0284c7;
            background: #f0f9ff; border: 1px solid #bae6fd;
            border-radius: 7px; padding: 5px 10px; cursor: pointer;
            text-decoration: none; transition: all 0.15s;
            width: 100%; box-sizing: border-box;
        }
        .res-btn-receipt:hover { background: #e0f2fe; border-color: #7dd3fc; }

        .res-btn-row { display: flex; gap: 6px; width: 100%; }

        .res-btn-confirm {
            flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 4px;
            background: #dcfce7; color: #166534; border: 1px solid #86efac;
            padding: 7px 8px; border-radius: 8px; font-size: 12px; font-weight: 700;
            cursor: pointer; transition: all 0.2s; white-space: nowrap; width: 100%;
        }
        .res-btn-confirm:hover { background: #22c55e; color: #fff; border-color: #22c55e; box-shadow: 0 3px 8px rgba(34,197,94,0.3); }

        .res-btn-cancel {
            flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 4px;
            background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;
            padding: 7px 8px; border-radius: 8px; font-size: 12px; font-weight: 700;
            cursor: pointer; transition: all 0.2s; white-space: nowrap; width: 100%;
        }
        .res-btn-cancel:hover { background: #ef4444; color: #fff; border-color: #ef4444; box-shadow: 0 3px 8px rgba(239,68,68,0.3); }

        .res-btn-checkout {
            display: inline-flex; align-items: center; justify-content: center; gap: 5px;
            background: #1e293b; color: #fff; border: none;
            padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700;
            cursor: pointer; transition: all 0.2s; width: 100%; box-sizing: border-box;
        }
        .res-btn-checkout:hover { background: #0f172a; box-shadow: 0 3px 10px rgba(15,23,42,0.3); }

        .res-checked-out { display: inline-flex; align-items: center; gap: 5px; color: #10b981; font-size: 12.5px; font-weight: 700; }
        .res-no-action { color: #cbd5e1; font-size: 13px; }

        .res-filter-bar {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .res-filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
            gap: 10px;
            align-items: flex-end;
        }
        @media (max-width: 900px) {
            .res-filter-grid { grid-template-columns: 1fr 1fr; }
        }
        .res-filter-label {
            display: block; font-size: 10.5px; font-weight: 700;
            color: #64748b; text-transform: uppercase;
            letter-spacing: 0.05em; margin-bottom: 5px;
        }
        .res-filter-input, .res-filter-select {
            width: 100%; padding: 8px 10px 8px 32px;
            border: 1.5px solid #e2e8f0; border-radius: 8px;
            font-size: 12.5px; box-sizing: border-box;
            outline: none; transition: border-color 0.2s; background: #fff;
        }
        .res-filter-select { padding-left: 10px; }
        .res-filter-input:focus, .res-filter-select:focus { border-color: #3b82f6; }
        .res-filter-search-wrap { position: relative; }
        .res-filter-search-wrap i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 11px; }
        .res-filter-btn-apply {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; background: linear-gradient(135deg, #003366, #004fa3);
            color: #fff; border: none; border-radius: 8px;
            font-size: 12.5px; font-weight: 700; cursor: pointer;
            white-space: nowrap; transition: opacity 0.2s;
        }
        .res-filter-btn-apply:hover { opacity: 0.88; }
        .res-filter-btn-clear {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 8px 12px; background: #f1f5f9;
            border: 1.5px solid #e2e8f0; color: #64748b; border-radius: 8px;
            font-size: 12.5px; font-weight: 600; cursor: pointer;
            text-decoration: none; transition: background 0.2s;
        }
        .res-filter-btn-clear:hover { background: #e2e8f0; }
        .res-active-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: #eff6ff; border: 1px solid #bfdbfe;
            color: #1d4ed8; border-radius: 20px;
            padding: 4px 10px; font-size: 11.5px; font-weight: 600;
        }
    </style>

    {{-- Search & Filter Bar --}}
    <form method="GET" action="/staff/reservations">
        <div class="res-filter-bar">
            <div class="res-filter-grid">
                <div>
                    <label class="res-filter-label">Search</label>
                    <div class="res-filter-search-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" class="res-filter-input"
                            placeholder="Name, room type, or ID..."
                            value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>
                <div>
                    <label class="res-filter-label">Booking Status</label>
                    <select name="status" class="res-filter-select">
                        <option value="">All Statuses</option>
                        @foreach(['Pending','Confirmed','Checked Out','Cancelled'] as $s)
                            <option value="{{ $s }}" {{ ($filters['statusFilter'] ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="res-filter-label">Payment</label>
                    <select name="payment_status" class="res-filter-select">
                        <option value="">All</option>
                        <option value="50% Deposit" {{ ($filters['paymentFilter'] ?? '') == '50% Deposit' ? 'selected' : '' }}>50% Deposit</option>
                        <option value="Fully Paid"  {{ ($filters['paymentFilter'] ?? '') == 'Fully Paid'  ? 'selected' : '' }}>Fully Paid</option>
                    </select>
                </div>
                <div>
                    <label class="res-filter-label">Check-In From</label>
                    <input type="date" name="date_from" class="res-filter-input" style="padding-left:10px;"
                        value="{{ $filters['dateFrom'] ?? '' }}">
                </div>
                <div>
                    <label class="res-filter-label">Check-In To</label>
                    <input type="date" name="date_to" class="res-filter-input" style="padding-left:10px;"
                        value="{{ $filters['dateTo'] ?? '' }}">
                </div>
                <div style="display:flex; gap:6px; align-items:flex-end;">
                    <button type="submit" class="res-filter-btn-apply">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @php
                        $hasFilters = !empty($filters['search']) || !empty($filters['statusFilter'])
                            || !empty($filters['paymentFilter']) || !empty($filters['dateFrom']) || !empty($filters['dateTo']);
                    @endphp
                    @if($hasFilters ?? false)
                        <a href="/staff/reservations" class="res-filter-btn-clear" title="Clear all filters">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>

            @if($hasFilters ?? false)
            <div style="margin-top:10px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <span style="font-size:11px; color:#94a3b8; font-weight:600;">Active:</span>
                @if(!empty($filters['search']))
                    <span class="res-active-badge"><i class="fa-solid fa-magnifying-glass" style="font-size:9px;"></i> "{{ $filters['search'] }}"</span>
                @endif
                @if(!empty($filters['statusFilter']))
                    <span class="res-active-badge"><i class="fa-solid fa-tag" style="font-size:9px;"></i> {{ $filters['statusFilter'] }}</span>
                @endif
                @if(!empty($filters['paymentFilter']))
                    <span class="res-active-badge"><i class="fa-solid fa-peso-sign" style="font-size:9px;"></i> {{ $filters['paymentFilter'] }}</span>
                @endif
                @if(!empty($filters['dateFrom']) || !empty($filters['dateTo']))
                    <span class="res-active-badge"><i class="fa-solid fa-calendar" style="font-size:9px;"></i> {{ $filters['dateFrom'] ?? '…' }} → {{ $filters['dateTo'] ?? '…' }}</span>
                @endif
                <span style="font-size:11px; color:#64748b; margin-left:4px;">
                    — {{ $reservations->total() }} result{{ $reservations->total() != 1 ? 's' : '' }}
                </span>
            </div>
            @endif
        </div>
    </form>

    <div class="res-table-wrap">
        {{-- Legend bar --}}
        <div style="display:flex;align-items:center;gap:20px;padding:12px 20px;background:#f8fafc;border-bottom:1px solid #e2e8f0;flex-wrap:wrap;">
            <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;margin-right:4px;">Amount Guide:</span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#475569;">
                <span style="font-weight:800;color:#0f172a;">₱X.XX</span>
                <span style="font-size:11px;font-weight:700;color:#dc2626;">+₱X.XX due</span>
                <span style="color:#94a3b8;">= 50% deposit paid, balance due at check-in</span>
            </span>
            <span style="width:1px;height:16px;background:#e2e8f0;"></span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#475569;">
                <span style="font-weight:800;color:#16a34a;">₱X.XX</span>
                <span style="font-size:11px;font-weight:600;color:#16a34a;display:inline-flex;align-items:center;gap:3px;"><i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Fully Paid</span>
                <span style="color:#94a3b8;">= total amount, no balance owed</span>
            </span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;">ID</th>
                    <th>Customer</th>
                    <th>Room</th>
                    <th>Payment</th>
                    <th style="text-align:right;">Amount</th>
                    <th style="text-align:center;">Check-In</th>
                    <th style="text-align:center;">Check-Out</th>
                    <th style="text-align:center;">Booking Status</th>
                    <th style="text-align:center;">Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                <tr>
                    <td style="text-align:center;">
                        <span class="res-id">#{{ str_pad($res->RESERVATION_ID, 4, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td><span class="res-guest-name">{{ $res->First_Name }} {{ $res->Last_Name }}</span></td>
                    <td>
                        <div class="res-room-type">{{ $res->Room_Type }}</div>
                        <div class="res-room-number">#{{ $res->Room_Number }}</div>
                    </td>
                    <td>
                        <span class="res-method">
                            <i class="fa-solid fa-credit-card" style="font-size:10px;"></i>
                            {{ $res->Payment_Method ?? '—' }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        @php
                            $deposit  = $res->Amount_Paid ?? $res->Total_Amount;
                            $fullPrice = $deposit * 2;
                            $balance  = $deposit;
                            $isFullyPaid = ($res->Payment_Status ?? '50% Deposit') === 'Fully Paid';
                        @endphp

                        @if($isFullyPaid)
                            <div style="font-weight:800;color:#16a34a;font-size:14px;">₱{{ number_format($fullPrice, 2) }}</div>
                            <div style="font-size:11px;color:#16a34a;font-weight:600;margin-top:2px;">
                                <i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Fully Paid
                            </div>
                        @else
                            <div style="font-weight:800;color:#0f172a;font-size:14px;">₱{{ number_format($deposit, 2) }}</div>
                            <div style="font-size:11px;font-weight:700;color:#dc2626;margin-top:3px;">
                                +₱{{ number_format($balance, 2) }} due
                            </div>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div class="res-date-label">Check-In</div>
                        <div class="res-date">{{ \Carbon\Carbon::parse($res->Check_In_Date)->format('M d, Y') }}</div>
                    </td>
                    <td style="text-align:center;">
                        <div class="res-date-label">Check-Out</div>
                        <div class="res-date">{{ \Carbon\Carbon::parse($res->Check_Out_Date)->format('M d, Y') }}</div>
                    </td>
                    <td style="text-align:center;">
                        <span class="status-badge status-{{ $res->Status }}">{{ $res->Status }}</span>
                    </td>
                    {{-- Payment Status --}}
                    <td style="text-align:center;">
                        @php $ps = $res->Payment_Status ?? '50% Deposit'; @endphp
                        @if($ps === 'Fully Paid')
                            <span style="display:inline-flex;align-items:center;gap:5px;background:#dcfce7;color:#166534;border:1px solid #86efac;border-radius:20px;padding:5px 12px;font-size:11.5px;font-weight:700;">
                                <i class="fa-solid fa-circle-check" style="font-size:10px;"></i> Fully Paid
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:5px;background:#fef9c3;color:#854d0e;border:1px solid #fde047;border-radius:20px;padding:5px 12px;font-size:11.5px;font-weight:700;">
                                <i class="fa-solid fa-clock" style="font-size:10px;"></i> 50% Deposit
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="res-actions">
                            @if($res->Receipt_Image)
                                <a href="#" class="res-btn-receipt" onclick="openReceipt('{{ asset(ltrim($res->Receipt_Image, '/')) }}'); return false;">
                                    <i class="fa-solid fa-image"></i> View Receipt
                                </a>
                            @endif
                            @if($res->Status == 'Pending')
                                <div class="res-btn-row">
                                    <form action="/staff/update-reservation/{{ $res->RESERVATION_ID }}" method="POST" style="flex:1;margin:0;">
                                        @csrf
                                        <input type="hidden" name="status" value="Confirmed">
                                        <button type="submit" class="res-btn-confirm">
                                            <i class="fa-solid fa-check"></i> Confirm
                                        </button>
                                    </form>
                                    <form action="/staff/update-reservation/{{ $res->RESERVATION_ID }}" method="POST" style="flex:1;margin:0;">
                                        @csrf
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button type="submit" class="res-btn-cancel" onclick="return confirm('Cancel this booking? The room will be freed up.');">
                                            <i class="fa-solid fa-xmark"></i> Cancel
                                        </button>
                                    </form>
                                </div>
                            @elseif($res->Status == 'Confirmed' || $res->Status == 'Booked')
                                @if(($res->Payment_Status ?? '50% Deposit') !== 'Fully Paid')
                                    <form action="/staff/mark-fully-paid/{{ $res->RESERVATION_ID }}" method="POST" style="width:100%;margin:0 0 6px;">
                                        @csrf
                                        <button type="submit" style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:5px;background:#f0fdf4;color:#166534;border:1px solid #86efac;padding:7px 10px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;transition:all 0.2s;" onclick="return confirm('Mark this reservation as Fully Paid?');">
                                            <i class="fa-solid fa-peso-sign" style="font-size:10px;"></i> Mark Fully Paid
                                        </button>
                                    </form>
                                    <span style="color:#94a3b8;font-size:11.5px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                                        <i class="fa-solid fa-lock" style="font-size:10px;"></i> Pay balance to check out
                                    </span>
                                @else
                                    <form action="/staff/checkout/{{ $res->RESERVATION_ID }}" method="POST" style="width:100%;margin:0;">
                                        @csrf
                                        <button type="submit" class="res-btn-checkout" onclick="return confirm('Check out {{ $res->First_Name }} {{ $res->Last_Name }}? Room will be set to Needs Cleaning.');">
                                            <i class="fa-solid fa-door-open"></i> Check Out
                                        </button>
                                    </form>
                                @endif
                            @elseif($res->Status == 'Checked Out')
                                <span class="res-checked-out"><i class="fa-solid fa-circle-check"></i> Checked Out</span>
                            @else
                                <span class="res-no-action">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center; color:#94a3b8; padding:40px; font-size:14px;">
                        <i class="fa-solid fa-calendar-xmark" style="font-size:24px; display:block; margin-bottom:8px; color:#cbd5e1;"></i>
                        No reservations found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $reservations->links() }}
    </div>
</div>
{{-- Receipt Image Modal --}}
<div id="receiptModal" onclick="closeReceipt(event)" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    align-items: center;
    justify-content: center;
">
    <div style="position: relative; max-width: 90vw; max-height: 90vh;">
        <button onclick="document.getElementById('receiptModal').style.display='none'" style="
            position: absolute;
            top: -14px;
            right: -14px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #fff;
            border: none;
            font-size: 18px;
            cursor: pointer;
            line-height: 1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            z-index: 1;
        ">&times;</button>
        <img id="receiptImg" src="" alt="Payment Receipt" style="
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            display: block;
        ">
    </div>
</div>

<script>
function openReceipt(url) {
    document.getElementById('receiptImg').src = url;
    document.getElementById('receiptModal').style.display = 'flex';
}
function closeReceipt(e) {
    if (e.target === document.getElementById('receiptModal')) {
        document.getElementById('receiptModal').style.display = 'none';
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.getElementById('receiptModal').style.display = 'none';
});
</script>

</body>
</html>