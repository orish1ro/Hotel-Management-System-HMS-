<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Status - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/staff_style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@if(session('staff_role') === 'Admin')
    @include('staff.admin-sidebar')
@else
    @include('staff.sidebar')
@endif

    <div class="container rooms-container" style="overflow-y: auto;">

        <div class="header-flex" style="margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 class="section-title" style="margin:0;"><i class="fa-solid fa-bed"></i> Room Status</h3>
            <span style="font-size:13px; color:#94a3b8; font-weight:600;">
                Showing {{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} of {{ $rooms->total() }} rooms
            </span>
        </div>

        {{-- Filter Buttons --}}
        <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
            <button onclick="filterRooms('all', this)"
                style="padding: 6px 16px; border-radius: 50px; border: 1px solid #003366; background: #003366; color: #fff; font-size: 12px; font-weight: 600; cursor: pointer;"
                class="filter-btn active">
                <i class="fa-solid fa-table-cells-large"></i> All
            </button>
            <button onclick="filterRooms('Available', this)"
                style="padding: 6px 16px; border-radius: 50px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; font-size: 12px; font-weight: 600; cursor: pointer;"
                class="filter-btn">
                <i class="fa-solid fa-circle-check"></i> Available
            </button>
            <button onclick="filterRooms('Booked', this)"
                style="padding: 6px 16px; border-radius: 50px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; font-size: 12px; font-weight: 600; cursor: pointer;"
                class="filter-btn">
                <i class="fa-solid fa-lock"></i> Booked
            </button>
            <button onclick="filterRooms('Needs Cleaning', this)"
                style="padding: 6px 16px; border-radius: 50px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; font-size: 12px; font-weight: 600; cursor: pointer;"
                class="filter-btn">
                <i class="fa-solid fa-broom"></i> Needs Cleaning
            </button>
        </div>

        <div class="rooms-scroll-area">
            <div class="rooms-grid" id="roomsGrid">
                @foreach($rooms as $room)
                @php
                    $statusClass = str_replace(' ', '-', $room->Status);
                    $statusColors = [
                        'Available'     => ['bg' => '#dcfce7', 'color' => '#15803d', 'border' => '#86efac'],
                        'Booked'        => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'border' => '#93c5fd'],
                        'Needs Cleaning'=> ['bg' => '#ffedd5', 'color' => '#c2410c', 'border' => '#fdba74'],
                    ];
                    $sc = $statusColors[$room->Status] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#cbd5e1'];
                @endphp
                <div class="room-card" data-status="{{ $room->Status }}">
                    <div class="room-img" style="background-image: url('{{ asset($room->Picture_Url) }}'); position:relative;">
                        <span style="position:absolute; top:10px; left:10px; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; background-color: {{ $sc['bg'] }}; color: {{ $sc['color'] }}; border: 1px solid {{ $sc['border'] }};">
                            {{ $room->Status }}
                        </span>
                    </div>

                    <div class="room-info" style="padding: 16px 16px 8px 16px; gap: 4px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="room-title text-left" style="font-size: 16px; margin: 0;">{{ $room->Room_Type }}</h3>
                            <span class="room-number text-right">#{{ $room->Room_Number }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; margin-top: 8px;">
                            <span><i class="fa-solid fa-users"></i> {{ $room->Capacity }} Guests</span>
                            <span style="font-weight: 700; color: #003366;">₱{{ number_format($room->Price_Per_Night, 0) }}</span>
                        </div>
                    </div>

                    {{-- Read-only status indicator, no buttons --}}
                    <div style="padding: 12px 16px; border-top: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748b;">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Status: <strong style="color: {{ $sc['color'] }};">{{ $room->Status }}</strong></span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top: 24px; margin-bottom: 30px; display:flex; justify-content:space-between; align-items:center; padding: 16px 4px; border-top: 1px solid #e2e8f0;">
                <span style="font-size:13px; color:#64748b; font-weight:500;">
                    Page {{ $rooms->currentPage() }} of {{ $rooms->lastPage() }}
                </span>
                <div class="pagination-wrapper">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>

<script>
    function filterRooms(status, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.style.background   = '#fff';
            b.style.color        = '#64748b';
            b.style.borderColor  = '#e2e8f0';
        });
        btn.style.background  = '#003366';
        btn.style.color       = '#fff';
        btn.style.borderColor = '#003366';

        document.querySelectorAll('.room-card').forEach(card => {
            card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
        });
    }
</script>

</body>
</html>