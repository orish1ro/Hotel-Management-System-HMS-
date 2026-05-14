<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Inventory - Ragadio Plaza Hotel</title>
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


    <div class="container rooms-container">
        
        <div class="header-flex" style="margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 class="section-title" style="margin:0;"><i class="fa-solid fa-bed"></i> Room Inventory</h3>
            <a href="/staff/add-room" class="btn-add">
                <i class="fa-solid fa-plus fa-xs"></i> Add New Room
            </a>
        </div>

        {{-- Search & Filter Bar --}}
        <form method="GET" action="/staff/rooms" style="margin-bottom: 20px;">
            <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                <div style="position:relative; flex:1; min-width:180px;">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13px;"></i>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search room # or type…"
                        style="width:100%; padding:8px 12px 8px 34px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#334155; box-sizing:border-box; outline:none;">
                </div>
                <select name="status" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#334155; background:#fff; cursor:pointer;">
                    <option value="">All Statuses</option>
                    <option value="Available"      {{ ($filters['status'] ?? '') === 'Available'      ? 'selected' : '' }}>Available</option>
                    <option value="Booked"         {{ ($filters['status'] ?? '') === 'Booked'         ? 'selected' : '' }}>Booked</option>
                    <option value="Needs Cleaning" {{ ($filters['status'] ?? '') === 'Needs Cleaning' ? 'selected' : '' }}>Needs Cleaning</option>
                </select>
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
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(!empty($filters['search']) || !empty($filters['status']) || !empty($filters['type']))
                    <a href="/staff/rooms" style="padding:8px 14px; background:#f1f5f9; color:#64748b; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">
                        <i class="fa-solid fa-xmark"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border: 1px solid #bbf7d0;">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="rooms-scroll-area">

            {{-- Results count --}}
            <div style="font-size:13px; color:#64748b; font-weight:500; margin-bottom:14px;">
                Showing {{ $rooms->firstItem() ?? 0 }}–{{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }} room(s)
                @if(!empty($filters['search']) || !empty($filters['status']) || !empty($filters['type']))
                    <span style="color:#003366; font-weight:600;"> (filtered)</span>
                @endif
            </div>

            <div class="rooms-grid">
                @forelse($rooms as $room)
                <div class="room-card">
                    <div class="room-img" style="background-image: url('{{ asset($room->Picture_Url) }}')">
                        @php $statusClass = str_replace(' ', '-', $room->Status); @endphp
                        <span class="status-tag status-{{ $statusClass }}">{{ $room->Status }}</span>
                    </div>
                    
                    <div class="room-info" style="padding: 16px 16px 8px 16px; gap: 4px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="room-title text-left" style="font-size: 16px; margin: 0;">{{ $room->Room_Type }}</h3>
                            <span class="room-number text-right">#{{ $room->Room_Number }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; margin-top: 8px;">
                            <span class="text-left"><i class="fa-solid fa-users"></i> {{ $room->Capacity }} Guests</span>
                            <span class="price-numeric text-right">₱{{ number_format($room->Price_Per_Night, 0) }}</span>
                        </div>
                    </div>

                    <div class="room-availability" style="padding: 12px 16px;">
                        <form action="/staff/update-availability/{{ $room->ROOM_ID }}" method="POST" style="width: 100%; margin: 0;">
                            @csrf
                            <button type="submit"
                                name="availability"
                                value="{{ $room->Status === 'Available' ? 'Booked' : 'Available' }}"
                                class="avail-toggle {{ $room->Status === 'Available' ? 'avail-booked' : 'avail-available' }}" style="width: 100%;">
                                <i class="fa-solid fa-rotate"></i> 
                                Set to {{ $room->Status === 'Available' ? 'Booked' : 'Available' }}
                            </button>
                        </form>
                    </div>

                    <div class="room-actions">
                        <a href="/staff/edit-room/{{ $room->ROOM_ID }}" class="btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                        <form action="/staff/delete-room/{{ $room->ROOM_ID }}" method="POST" style="flex:1; margin:0;">
                            @csrf
                            <button type="submit" class="btn-delete" style="width: 100%; box-sizing: border-box;" onclick="return confirm('Delete this room forever?')"><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
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
            
            <div class="pagination-wrapper" style="margin-top: 20px; margin-bottom: 30px;">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>

</body>
</html>