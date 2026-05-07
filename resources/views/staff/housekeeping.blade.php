<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Housekeeping - Ragadio Plaza Hotel</title>
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


<div class="hk-wrapper">

    <div class="hk-page-header">
        <div>
            <h2><i class="fa-solid fa-broom" style="margin-right: 8px;"></i>Housekeeping</h2>
            <p>Manage cleaning status for each room.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="hk-alert">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="hk-summary">
        <div class="hk-pill"><span class="dot dot-avail"></span> Available: {{ $allRooms->where('Status','Available')->count() }}</div>
        <div class="hk-pill"><span class="dot dot-needs"></span> Needs Cleaning: {{ $allRooms->where('Status','Needs Cleaning')->count() }}</div>
    </div>

    <div class="hk-filter-bar">
        <button class="hk-filter-btn active" onclick="filterRooms('all', this)">
            <i class="fa-solid fa-table-cells-large"></i> All
        </button>
        <button class="hk-filter-btn f-needs" onclick="filterRooms('Needs Cleaning', this)">
            <i class="fa-solid fa-broom"></i> Needs Cleaning
        </button>
        <button class="hk-filter-btn f-avail" onclick="filterRooms('Available', this)">
            <i class="fa-solid fa-circle-check"></i> Available
        </button>
    </div>

    <div class="hk-grid" id="hkGrid">
        @foreach($rooms as $room)
        @php $statusClass = str_replace(' ', '-', $room->Status); @endphp
        <div class="hk-card" data-status="{{ $room->Status }}">

            <div class="hk-card-accent accent-{{ $statusClass }}"></div>

            <div class="hk-card-header">
                <div>
                    <div class="hk-room-label">Room</div>
                    <div class="hk-room-number">#{{ $room->Room_Number }}</div>
                </div>
                <span class="hk-badge badge-{{ $statusClass }}">{{ $room->Status }}</span>
            </div>

            <div class="hk-card-body">
                <div class="hk-info-row">
                    <span class="hk-room-type">{{ $room->Room_Type }}</span>
                    <span class="hk-capacity">{{ $room->Capacity }} Guests</span>
                </div>
                <hr class="hk-divider">
                <form action="/staff/update-room-status/{{ $room->ROOM_ID }}" method="POST">
                    @csrf
                    <span class="hk-label">Update cleaning status</span>
                    <div class="hk-form-row">
                        <select name="status" class="hk-select">
                            <option value="Needs Cleaning" {{ $room->Status == 'Needs Cleaning' ? 'selected' : '' }}>Needs Cleaning</option>
                            <option value="Available"      {{ $room->Status == 'Available'      ? 'selected' : '' }}>Available</option>
                        </select>
                        <button type="submit" class="hk-btn-set">Set</button>
                    </div>
                </form>
            </div>

        </div>
        @endforeach
    </div>

    <div class="pagination-wrapper" style="margin-top: 24px; margin-bottom: 30px;">
        {{ $rooms->links() }}
    </div>

</div>

<script>
    function filterRooms(status, btn) {
        document.querySelectorAll('.hk-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.hk-card').forEach(card => {
            card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
        });
    }
</script>

</body>
</html>