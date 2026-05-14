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
                Showing {{ $rooms->firstItem() ?? 0 }}–{{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }} rooms
            </span>
        </div>

        {{-- Search & Filter Bar --}}
        <form method="GET" action="/staff/rooms-view" style="margin-bottom: 16px;">
            <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                <div style="position:relative; flex:1; min-width:180px;">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13px;"></i>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search room # or type…"
                        style="width:100%; padding:8px 12px 8px 34px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#334155; box-sizing:border-box; outline:none;">
                </div>
                <select name="type" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#334155; background:#fff; cursor:pointer;">
                    <option value="">All Types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type }}" {{ ($filters['type'] ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="sort" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#334155; background:#fff; cursor:pointer;">
                    <option value="Room_Number"     {{ ($filters['sort'] ?? 'Room_Number') === 'Room_Number'    ? 'selected' : '' }}>Sort: Room #</option>
                    <option value="Price_Per_Night" {{ ($filters['sort'] ?? '') === 'Price_Per_Night' ? 'selected' : '' }}>Sort: Price</option>
                    <option value="Capacity"        {{ ($filters['sort'] ?? '') === 'Capacity'        ? 'selected' : '' }}>Sort: Capacity</option>
                </select>
                <button type="submit" style="padding:8px 18px; background:#003366; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                    <i class="fa-solid fa-filter"></i> Search
                </button>
                @if(!empty($filters['search']) || !empty($filters['type']))
                    <a href="/staff/rooms-view" style="padding:8px 14px; background:#f1f5f9; color:#64748b; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">
                        <i class="fa-solid fa-xmark"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Status Filter Buttons --}}
        <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
            <a href="/staff/rooms-view?search={{ urlencode($filters['search'] ?? '') }}&type={{ urlencode($filters['type'] ?? '') }}&sort={{ $filters['sort'] ?? 'Room_Number' }}"
                style="padding: 6px 16px; border-radius: 50px; border: 1px solid {{ empty($filters['status'] ?? '') ? '#003366' : '#e2e8f0' }}; background: {{ empty($filters['status'] ?? '') ? '#003366' : '#fff' }}; color: {{ empty($filters['status'] ?? '') ? '#fff' : '#64748b' }}; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration:none;">
                <i class="fa-solid fa-table-cells-large"></i> All
            </a>
            @foreach(['Available' => 'fa-circle-check', 'Booked' => 'fa-lock', 'Needs Cleaning' => 'fa-broom'] as $s => $icon)
            <a href="/staff/rooms-view?search={{ urlencode($filters['search'] ?? '') }}&type={{ urlencode($filters['type'] ?? '') }}&sort={{ $filters['sort'] ?? 'Room_Number' }}&status={{ urlencode($s) }}"
                style="padding: 6px 16px; border-radius: 50px; border: 1px solid {{ ($filters['status'] ?? '') === $s ? '#003366' : '#e2e8f0' }}; background: {{ ($filters['status'] ?? '') === $s ? '#003366' : '#fff' }}; color: {{ ($filters['status'] ?? '') === $s ? '#fff' : '#64748b' }}; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration:none;">
                <i class="fa-solid {{ $icon }}"></i> {{ $s }}
            </a>
            @endforeach
        </div>

        <div class="rooms-scroll-area">
            <div class="rooms-grid" id="roomsGrid">
                @forelse($rooms as $room)
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
                @empty
                    <div style="grid-column:1/-1; text-align:center; padding:60px 20px; color:#94a3b8;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size:32px; margin-bottom:12px; display:block;"></i>
                        <p style="font-size:15px; font-weight:600; margin:0 0 6px;">No rooms found</p>
                        <p style="font-size:13px; margin:0;">Try adjusting your search or filters.</p>
                    </div>
                @endforelse
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

</body>
</html>