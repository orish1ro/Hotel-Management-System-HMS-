<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

    .header {
        background: #003366;
        color: #fff;
        padding: 18px 24px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .hotel-name { font-size: 20px; font-weight: 700; letter-spacing: 0.3px; }
    .hotel-sub  { font-size: 10px; opacity: 0.65; margin-top: 3px; }
    .report-title { font-size: 14px; font-weight: 700; text-align: right; }
    .report-meta  { font-size: 10px; opacity: 0.65; margin-top: 3px; text-align: right; }

    .filters-bar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 14px;
        margin: 0 0 14px;
        font-size: 11px;
        color: #64748b;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .filters-bar span strong { color: #1e293b; }

    .summary {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
    }
    .summary-card {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        text-align: center;
    }
    .summary-card .label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 4px; }
    .summary-card .value { font-size: 18px; font-weight: 800; color: #003366; }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #003366; color: #fff; }
    thead th { padding: 9px 12px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    thead th.right { text-align: right; }

    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody tr { border-bottom: 1px solid #f1f5f9; }
    tbody td { padding: 9px 12px; font-size: 12px; }
    tbody td.right { text-align: right; }

    .status { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 700; }
    .status-Pending    { background: #fef9c3; color: #854d0e; }
    .status-Confirmed  { background: #dcfce7; color: #166534; }
    .status-Checked-Out { background: #dbeafe; color: #1e40af; }
    .status-Cancelled  { background: #fee2e2; color: #991b1b; }

    .total-row td { font-weight: 700; background: #f1f5f9; border-top: 2px solid #003366; font-size: 13px; }

    .footer {
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
        font-size: 10px;
        color: #94a3b8;
        display: flex;
        justify-content: space-between;
    }
</style>
</head>
<body>

<div class="header">
    <div>
        <div class="hotel-name">Ragadio Plaza Hotel</div>
        <div class="hotel-sub">Transaction History Report</div>
    </div>
    <div>
        <div class="report-title">Transaction Report</div>
        <div class="report-meta">Exported by: {{ $exportedBy }}</div>
        <div class="report-meta">{{ $exportedAt }}</div>
    </div>
</div>

{{-- Active Filters --}}
@if(array_filter($filters))
<div class="filters-bar">
    <span>Filters applied:</span>
    @if(!empty($filters['search']))    <span><strong>Search:</strong> {{ $filters['search'] }}</span> @endif
    @if(!empty($filters['status']))    <span><strong>Status:</strong> {{ $filters['status'] }}</span> @endif
    @if(!empty($filters['method']))    <span><strong>Method:</strong> {{ $filters['method'] }}</span> @endif
    @if(!empty($filters['date_from'])) <span><strong>From:</strong> {{ $filters['date_from'] }}</span> @endif
    @if(!empty($filters['date_to']))   <span><strong>To:</strong> {{ $filters['date_to'] }}</span> @endif
</div>
@endif

{{-- Summary --}}
<div class="summary">
    <div class="summary-card">
        <div class="label">Total Transactions</div>
        <div class="value">{{ $transactions->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total Amount</div>
        <div class="value">₱{{ number_format($total, 2) }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Average Amount</div>
        <div class="value">₱{{ $transactions->count() ? number_format($total / $transactions->count(), 2) : '0.00' }}</div>
    </div>
</div>

{{-- Table --}}
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Room</th>
            <th>Method</th>
            <th class="right">Amount</th>
            <th>Date & Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $t)
        <tr>
            <td><strong>#{{ str_pad($t->PAYMENT_ID, 4, '0', STR_PAD_LEFT) }}</strong></td>
            <td style="text-transform:capitalize;">{{ $t->First_Name }} {{ $t->Last_Name }}</td>
            <td>{{ $t->Room_Type }} <span style="color:#94a3b8;">#{{ $t->Room_Number }}</span></td>
            <td>{{ $t->Payment_Method ?? '—' }}</td>
            <td class="right"><strong>₱{{ number_format($t->Amount, 2) }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($t->Payment_Date)->format('M d, Y h:i A') }}</td>
            <td>
                <span class="status status-{{ str_replace(' ', '-', $t->ReservationStatus) }}">
                    {{ $t->ReservationStatus }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:20px;color:#94a3b8;">No transactions found.</td>
        </tr>
        @endforelse
        @if($transactions->count())
        <tr class="total-row">
            <td colspan="4" style="text-align:right;">TOTAL</td>
            <td class="right">₱{{ number_format($total, 2) }}</td>
            <td colspan="2"></td>
        </tr>
        @endif
    </tbody>
</table>

<div class="footer">
    <span>Ragadio Plaza Hotel — Confidential</span>
    <span>Generated: {{ $exportedAt }}</span>
</div>

</body>
</html>