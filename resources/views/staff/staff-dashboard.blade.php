<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/staff_style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('staff.sidebar')


    <div class="container dashboard-container">

        <h3 class="section-title"><i class="fa-solid fa-chart-line"></i> Staff Dashboard Overview</h3>

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
            <div class="metric-card">
                <div class="metric-title text-left">PENDING RESERVATIONS</div>
                <div class="metric-value text-right">{{ $pendingReservations }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">ROOMS TO CLEAN</div>
                <div class="metric-value text-right">{{ $roomsToClean }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-title text-left">RECENT ARRIVALS</div>
                <div class="metric-value text-right">{{ $recentArrivals }}</div>
            </div>
        </div>

        <h3 class="section-title" style="margin-top: 10px; margin-bottom: 10px;">
            <i class="fa-solid fa-clock-rotate-left"></i> Recent Reservations
        </h3>

        <div class="table-container">
            <table style="width: 100%; border-collapse: collapse; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <thead style="background-color: #f4f7f6; text-align: left;">
                    <tr>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Res ID</th>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Guest Name</th>
                        <th style="padding: 12px; border-bottom: 2px solid #ddd;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReservations as $res)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">#{{ $res->RESERVATION_ID }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $res->First_Name }} {{ $res->Last_Name }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span style="padding: 5px 10px; border-radius: 20px; font-size: 12px; background-color: #eef2fb; color: #003366;">
                                {{ $res->Status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 12px; text-align: center; color: #999;">No recent reservations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>