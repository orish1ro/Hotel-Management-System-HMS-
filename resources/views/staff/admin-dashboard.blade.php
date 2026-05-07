<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/staff_style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/rooms-responsive.css') }}">
</head>
<body>
    @include('staff.admin-sidebar')

    <div class="container dashboard-container">

        <h3 class="section-title"><i class="fa-solid fa-chart-line"></i> Admin Dashboard Overview</h3>

        {{-- ROW 1: Room Stats --}}
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-title text-left">TOTAL ROOMS</div>
                <div class="metric-value text-right">{{ $totalRooms }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">AVAILABLE NOW</div>
                <div class="metric-value text-right">{{ $availableRooms }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">BOOKED ROOMS</div>
                <div class="metric-value text-right">{{ $bookedRooms }}</div>
            </div>

            {{-- ROW 2: Revenue & Operations --}}
            <div class="metric-card">
                <div class="metric-title text-left">TODAY'S REVENUE</div>
                <div class="metric-value text-right" style="display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
                    ₱{{ number_format($revenueToday, 2) }}
                    <span class="trend-indicator trend-{{ $revenueDirection ?? 'up' }}">
                        <i class="fa-solid fa-arrow-{{ ($revenueDirection ?? 'up') == 'down' ? 'down' : 'trend-up' }}"></i>
                        {{ $revenueTrend ?? '0%' }}
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">TOTAL REVENUE</div>
                <div class="metric-value text-right">₱{{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">PENDING TASKS</div>
                <div class="metric-value text-right">{{ $pendingReservations }}</div>
            </div>

            {{-- ROW 3: Extra Admin Metrics --}}
            <div class="metric-card">
                <div class="metric-title text-left">TOTAL GUESTS</div>
                <div class="metric-value text-right">{{ $totalGuests }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">OCCUPANCY RATE</div>
                <div class="metric-value text-right">{{ $occupancyRate }}%</div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">ROOMS TO CLEAN</div>
                <div class="metric-value text-right">{{ $roomsToClean }}</div>
            </div>
        </div>

        {{-- Recent Reservations Table --}}
        <h3 class="section-title" style="margin-top: 10px; margin-bottom: 10px;">
            <i class="fa-solid fa-clock-rotate-left"></i> Recent Reservations
        </h3>

        <div class="table-container">
            <table style="width: 100%; border-collapse: collapse; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <thead style="background-color: #f4f7f6; text-align: left;">
                    <tr>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Res ID</th>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Guest Name</th>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Room</th>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Check-In</th>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Check-Out</th>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReservations as $res)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">#{{ $res->RESERVATION_ID }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $res->First_Name }} {{ $res->Last_Name }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            {{ $res->Room_Number }} <span style="font-size: 11px; color: #888;">({{ $res->Room_Type }})</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $res->Check_In_Date }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $res->Check_Out_Date }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            @php
                                $statusColors = [
                                    'Pending'    => ['bg' => '#fff3cd', 'color' => '#856404'],
                                    'Confirmed'  => ['bg' => '#d1e7dd', 'color' => '#0a3622'],
                                    'Booked'     => ['bg' => '#cfe2ff', 'color' => '#084298'],
                                    'Checked Out'=> ['bg' => '#e2e3e5', 'color' => '#41464b'],
                                    'Cancelled'  => ['bg' => '#f8d7da', 'color' => '#842029'],
                                ];
                                $sc = $statusColors[$res->Status] ?? ['bg' => '#eef2fb', 'color' => '#003366'];
                            @endphp
                            <span style="padding: 5px 10px; border-radius: 20px; font-size: 12px; background-color: {{ $sc['bg'] }}; color: {{ $sc['color'] }};">
                                {{ $res->Status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 12px; text-align: center; color: #999;">No recent reservations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>