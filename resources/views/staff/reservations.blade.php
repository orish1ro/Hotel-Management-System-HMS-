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

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="num-header">ID</th>
                    <th class="text-left">Customer</th>
                    <th class="text-left">Room Details</th>
                    <th class="text-left">Payment Method</th>
                    <th class="num-header">Amount</th>
                    <th class="num-header">Check-In</th>
                    <th class="num-header">Check-Out</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                <tr>
                    <td class="num-header"><strong>#{{ str_pad($res->RESERVATION_ID, 4, '0', STR_PAD_LEFT) }}</strong></td>
                    <td class="text-left" style="text-transform: capitalize;">{{ $res->First_Name }} {{ $res->Last_Name }}</td>
                    <td class="text-left">{{ $res->Room_Type }} (#{{ $res->Room_Number }})</td>
                    <td class="text-left">{{ $res->Payment_Method ?? '—' }}</td>
                    <td class="num-header">₱{{ number_format($res->Amount_Paid ?? $res->Total_Amount, 2) }}</td>
                    <td class="num-header">{{ \Carbon\Carbon::parse($res->Check_In_Date)->format('M d, Y') }}</td>
                    <td class="num-header">{{ \Carbon\Carbon::parse($res->Check_Out_Date)->format('M d, Y') }}</td>
                    <td><span class="status-badge status-{{ $res->Status }}">{{ $res->Status }}</span></td>
                    <td>
                        @if($res->Status == 'Pending')
                            <form action="/staff/update-reservation/{{ $res->RESERVATION_ID }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="Confirmed">
                                <button type="submit" class="btn-confirm">Confirm</button>
                            </form>
                            <form action="/staff/update-reservation/{{ $res->RESERVATION_ID }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="Cancelled">
                                <button type="submit" class="btn-cancel" onclick="return confirm('Cancel this booking? The room will be freed up.');">Cancel</button>
                            </form>

                        @elseif($res->Status == 'Confirmed' || $res->Status == 'Booked')
                            <form action="/staff/checkout/{{ $res->RESERVATION_ID }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-checkout" onclick="return confirm('Check out {{ $res->First_Name }} {{ $res->Last_Name }}? Room will be set to Needs Cleaning.');">
                                    <i class="fa-solid fa-door-open"></i> Check Out
                                </button>
                            </form>

                        @elseif($res->Status == 'Checked Out')
                            <span style="color: #10b981; font-size: 13px; font-weight: 600;"><i class="fa-solid fa-circle-check"></i> Checked Out</span>

                        @else
                            <span style="color: #94a3b8; font-size: 12px; font-weight: 600;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 30px;">No reservations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $reservations->links() }}
    </div>
</div>

</body>
</html>