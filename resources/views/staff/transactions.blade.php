<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - Ragadio Plaza Hotel</title>
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

    <h3 class="section-title" style="margin-bottom: 24px;">
        <i class="fa-solid fa-clock-rotate-left"></i> Transaction History
    </h3>

    {{-- ── Search & Filter Bar ── --}}
    <form method="GET" action="/staff/transactions">
        <div style="
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px 22px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        ">
            <div style="display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr auto; gap:12px; align-items:flex-end;">

                {{-- Search --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Search</label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:12px;"></i>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Customer name or room..."
                            style="width:100%; padding:9px 12px 9px 34px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; box-sizing:border-box; outline:none; transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Status</label>
                    <select name="status" style="width:100%; padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#fff; outline:none; box-sizing:border-box;">
                        <option value="">All Statuses</option>
                        <option value="Pending"     {{ ($filters['status'] ?? '') == 'Pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="Confirmed"   {{ ($filters['status'] ?? '') == 'Confirmed'   ? 'selected' : '' }}>Confirmed</option>
                        <option value="Checked Out" {{ ($filters['status'] ?? '') == 'Checked Out' ? 'selected' : '' }}>Checked Out</option>
                        <option value="Cancelled"   {{ ($filters['status'] ?? '') == 'Cancelled'   ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                {{-- Method --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Method</label>
                    <select name="method" style="width:100%; padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#fff; outline:none; box-sizing:border-box;">
                        <option value="">All Methods</option>
                        <option value="E-Money" {{ ($filters['method'] ?? '') == 'E-Money' ? 'selected' : '' }}>E-Money</option>
                        <option value="Cash"    {{ ($filters['method'] ?? '') == 'Cash'    ? 'selected' : '' }}>Cash</option>
                        <option value="Card"    {{ ($filters['method'] ?? '') == 'Card'    ? 'selected' : '' }}>Card</option>
                    </select>
                </div>

                {{-- Date From --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">From</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                        style="width:100%; padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; box-sizing:border-box; outline:none; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                </div>

                {{-- Date To --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">To</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                        style="width:100%; padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; box-sizing:border-box; outline:none; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                </div>

                {{-- Action Buttons --}}
                <div style="display:flex; gap:8px;">
                    <button type="submit" style="
                        display:inline-flex; align-items:center; gap:6px;
                        padding:9px 18px; background:linear-gradient(135deg,#2563eb,#1d4ed8);
                        color:#fff; border:none; border-radius:9px; font-size:13px;
                        font-weight:700; cursor:pointer; white-space:nowrap;
                        box-shadow:0 2px 8px rgba(37,99,235,0.2);
                    ">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @if(array_filter($filters ?? []))
                    <a href="/staff/transactions" title="Clear filters" style="
                        display:inline-flex; align-items:center; justify-content:center;
                        padding:9px 14px; background:#f1f5f9; border:1.5px solid #e2e8f0;
                        color:#64748b; border-radius:9px; font-size:13px;
                        font-weight:600; text-decoration:none;
                    "><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>

            </div>
        </div>
    </form>

    {{-- ── Results Summary Banner ── --}}
    @if(array_filter($filters ?? []))
    <div style="
        background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px;
        padding:12px 18px; margin-bottom:16px;
        display:flex; align-items:center; justify-content:space-between;
        font-size:13px; color:#1d4ed8;
    ">
        <div style="display:flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-circle-info"></i>
            <span>Found <strong>{{ $countFiltered }}</strong> transaction{{ $countFiltered != 1 ? 's' : '' }} matching your filters</span>
        </div>
        <div style="font-weight:700; font-size:14px;">
            Total: ₱{{ number_format($totalFiltered, 2) }}
        </div>
    </div>
    @endif

    {{-- ── Table ── --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="num-header">ID</th>
                    <th class="text-left">Customer</th>
                    <th class="text-left">Room</th>
                    <th class="text-left">Method</th>
                    <th class="num-header">Amount</th>
                    <th class="num-header">Date & Time</th>
                    <th class="text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td class="num-header">
                        <strong style="color:#1e293b; font-size:13px;">
                            #{{ str_pad($t->PAYMENT_ID, 4, '0', STR_PAD_LEFT) }}
                        </strong>
                    </td>
                    <td class="text-left">
                        <div style="font-weight:600; font-size:14px; color:#1e293b; text-transform:capitalize;">
                            {{ $t->First_Name }} {{ $t->Last_Name }}
                        </div>
                    </td>
                    <td class="text-left">
                        <div style="font-weight:600; font-size:13px; color:#334155;">{{ $t->Room_Type }}</div>
                        <div style="font-size:11px; color:#94a3b8; margin-top:1px;">#{{ $t->Room_Number }}</div>
                    </td>
                    <td class="text-left">
                        <span style="
                            background:#f0f9ff; border:1px solid #bae6fd;
                            color:#0369a1; border-radius:20px; padding:3px 10px;
                            font-size:12px; font-weight:600;
                        ">{{ $t->Payment_Method ?? '—' }}</span>
                    </td>
                    <td class="num-header">
                        <span style="font-weight:700; font-size:14px; color:#15803d;">
                            ₱{{ number_format($t->Amount, 2) }}
                        </span>
                    </td>
                    <td class="num-header">
                        <div style="font-size:13px; color:#475569; font-weight:500;">
                            {{ \Carbon\Carbon::parse($t->Payment_Date)->format('M d, Y') }}
                        </div>
                        <div style="font-size:11px; color:#94a3b8; margin-top:1px;">
                            {{ \Carbon\Carbon::parse($t->Payment_Date)->format('h:i A') }}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $t->ReservationStatus }}">
                            {{ $t->ReservationStatus }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:#94a3b8;">
                        <i class="fa-solid fa-receipt" style="font-size:2rem; opacity:0.3; display:block; margin-bottom:10px;"></i>
                        No transactions found{{ array_filter($filters ?? []) ? ' matching your filters' : '' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $transactions->links() }}
    </div>

</div>
</body>
</html>