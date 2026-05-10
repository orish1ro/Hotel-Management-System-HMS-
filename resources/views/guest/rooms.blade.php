<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        .rooms-page-container {
            width: 100%;
            padding: 28px 32px 20px;
            box-sizing: border-box;
        }

        .rooms-title-sectiontext {
            margin-bottom: 20px;
        }
        .rooms-title-sectiontext h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary, #1a3c6e);
            margin-bottom: 4px;
        }
        .rooms-title-sectiontext p {
            font-size: 14px;
            color: var(--text-muted, #6b7280);
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            justify-content: center;
        }

        .room-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            width: 100%;
            margin: 0 auto;
        }
        .room-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
        }

        .room-image-area {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #cbd5e1;
            flex-shrink: 0;
        }
        .room-image-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        .room-card:hover .room-image-area img {
            transform: scale(1.04);
        }
        .room-image-area .no-img {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 13px;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 20px;
            line-height: 1.4;
            white-space: nowrap;
        }
        .status-Available { background: #16a34a; color: #fff; }
        .status-Booked    { background: #dc2626; color: #fff; }

        .room-card-info {
            padding: 11px 13px 8px;
            flex: 1;
        }
        .room-card-info-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 3px;
            gap: 6px;
        }
        .room-card-info h3 {
            font-size: 16px;
            font-weight: 700;
            color: #1a3c6e;
            margin-bottom: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .room-number-badge {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 2px 8px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .room-card-info > p {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.4;
            margin-bottom: 7px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .inclusions-row {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 8px;
        }
        .inclusion-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: #374151;
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .inclusion-chip i { font-size: 11px; color: #10b981; }

        .room-price {
            display: block;
            font-size: 16px;
            font-weight: 700;
            color: #1a3c6e;
            margin-top: 4px;
            text-align: right;
        }
        .room-price-unavailable {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #ef4444;
            margin-top: 4px;
            text-align: right;
        }

        .room-card-footer {
            padding: 10px 13px 15px;
        }
        .btn-book {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: background 0.15s;
            background: #e8a000;
            color: #fff;
        }
        .btn-book:hover:not(.disabled) { background: #c98a00; }
        .btn-book.disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .flash-msg {
            max-width: 600px;
            margin: 0 auto 16px;
            padding: 12px 16px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }
        .flash-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .flash-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        .rooms-filter-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 22px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .filter-group label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #9ca3af;
        }
        .filter-group select,
        .filter-group input[type="number"] {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 13px;
            font-weight: 500;
            color: #1f2937;
            background: #f9fafb;
            outline: none;
            transition: border-color 0.15s;
            height: 36px;
        }
        .filter-group select { min-width: 150px; cursor: pointer; }
        .filter-group input[type="number"] { width: 90px; }
        .filter-group select:focus,
        .filter-group input:focus { border-color: #003366; background: #fff; }

        .filter-divider {
            width: 1px;
            height: 36px;
            background: #e5e7eb;
            flex-shrink: 0;
        }
        .filter-price-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .filter-price-row span {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        .filter-toggle {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            user-select: none;
        }
        .filter-toggle input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #003366;
            cursor: pointer;
        }

        .filter-apply-btn {
            background: #003366;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0 20px;
            height: 36px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            white-space: nowrap;
            margin-left: auto;
        }
        .filter-apply-btn:hover { background: #002244; transform: translateY(-1px); }

        .filter-clear-btn {
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-decoration: none;
            padding: 0 8px;
            transition: color 0.15s;
            white-space: nowrap;
        }
        .filter-clear-btn:hover { color: #ef4444; }

        .results-info {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .results-info strong { color: #1a3c6e; }
        .active-filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #1d4ed8;
        }
            text-align: center;
            font-size: 13px;
            color: var(--text-muted, #6b7280);
            margin: 20px 0 8px;
        }
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            padding-bottom: 10px;
            flex-wrap: wrap;
        }
        .pagination-wrapper .page-link,
        .pagination-wrapper .page-item.disabled span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }
        .pagination-wrapper .page-item.active .page-link {
            background: var(--primary, #1a3c6e);
            color: #fff;
            pointer-events: none;
        }
        .pagination-wrapper .page-item:not(.active):not(.disabled) .page-link {
            background: #fff;
            color: var(--primary, #1a3c6e);
            border: 1px solid #d1d5db;
        }
        .pagination-wrapper .page-item:not(.active):not(.disabled) .page-link:hover {
            background: var(--primary, #1a3c6e);
            color: #fff;
            border-color: var(--primary, #1a3c6e);
        }
        .pagination-wrapper .page-item.disabled span {
            background: #f3f4f6;
            color: #9ca3af;
            border: 1px solid #e5e7eb;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    @include('layouts.header')

    <div class="rooms-page-container">

        <div class="rooms-title-sectiontext">
            <h1>Our Rooms</h1>
            @if(request('checkin') && request('checkout'))
                <p>Showing available rooms from <strong>{{ \Carbon\Carbon::parse(request('checkin'))->format('M d, Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse(request('checkout'))->format('M d, Y') }}</strong>
                &nbsp;·&nbsp; <a href="/rooms" style="color:#e8a000;font-weight:600;text-decoration:none;">Clear filter</a></p>
            @else
                <p>Find the perfect room for your stay.</p>
            @endif
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="/rooms" class="rooms-filter-bar">
            {{-- Preserve date params --}}
            @if(request('checkin'))  <input type="hidden" name="checkin"  value="{{ request('checkin') }}"> @endif
            @if(request('checkout')) <input type="hidden" name="checkout" value="{{ request('checkout') }}"> @endif
            @if(request('guests'))   <input type="hidden" name="guests"   value="{{ request('guests') }}"> @endif

            {{-- Room Type --}}
            <div class="filter-group">
                <label>Room Type</label>
                <select name="type">
                    <option value="">All Types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-divider"></div>

            {{-- Price Range --}}
            <div class="filter-group">
                <label>Price Range (₱/night)</label>
                <div class="filter-price-row">
                    <input type="number" name="min_price" placeholder="Min" min="0" value="{{ request('min_price') }}">
                    <span>—</span>
                    <input type="number" name="max_price" placeholder="Max" min="0" value="{{ request('max_price') }}">
                </div>
            </div>

            <div class="filter-divider"></div>

            {{-- Sort --}}
            <div class="filter-group">
                <label>Sort By</label>
                <select name="sort">
                    <option value="">Default</option>
                    <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Price: Low → High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                    <option value="type_asc"   {{ request('sort') == 'type_asc'   ? 'selected' : '' }}>Room Type A → Z</option>
                </select>
            </div>

            <div class="filter-divider"></div>

            {{-- Available Only --}}
            <label class="filter-toggle">
                <input type="checkbox" name="available_only" value="1" {{ request('available_only') || (request('checkin') && request('checkout')) ? 'checked' : '' }}>
                Available Only
            </label>

            <button type="submit" class="filter-apply-btn"><i class="fa-solid fa-sliders"></i> Apply</button>

            @if(request('type') || request('min_price') || request('max_price') || request('sort') || request('available_only'))
                <a href="/rooms{{ request('checkin') ? '?checkin='.request('checkin').'&checkout='.request('checkout').'&guests='.request('guests') : '' }}" class="filter-clear-btn">✕ Clear</a>
            @endif
        </form>

        {{-- Results info --}}
        @if(request('type') || request('min_price') || request('max_price') || request('sort') || request('available_only') || (request('checkin') && request('checkout')))
        <div class="results-info">
            <strong>{{ $rooms->total() }} room{{ $rooms->total() != 1 ? 's' : '' }}</strong> found
            @if(request('type')) <span class="active-filter-tag">{{ request('type') }}</span> @endif
            @if(request('available_only')) <span class="active-filter-tag">Available Only</span> @endif
            @if(request('sort') == 'price_asc') <span class="active-filter-tag">Price ↑</span> @endif
            @if(request('sort') == 'price_desc') <span class="active-filter-tag">Price ↓</span> @endif
            @if(request('min_price') || request('max_price')) <span class="active-filter-tag">₱{{ request('min_price','0') }} – ₱{{ request('max_price','∞') }}</span> @endif
        </div>
        @endif

        @if(session('error'))
            <div class="flash-msg flash-error">⚠️ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="flash-msg flash-success">✅ {{ session('success') }}</div>
        @endif

        <div class="rooms-grid">
            @forelse($rooms as $room)
            <div class="room-card" style="{{ $room->Status != 'Available' ? 'opacity:0.78;' : '' }}">

                <div class="room-image-area">
                    @if($room->Picture_Url)
                        <img src="{{ $room->Picture_Url }}" alt="{{ $room->Room_Type }}">
                    @else
                        <div class="no-img"><i class="fa-solid fa-image"></i>&nbsp; No Image</div>
                    @endif
                    @if($room->Status == 'Available')
                        <span class="status-badge status-Available">Available</span>
                    @else
                        <span class="status-badge status-Booked">Not Available</span>
                    @endif
                </div>

                <div class="room-card-body">
                <div class="room-card-info">
                    <div class="room-card-info-header">
                        <h3>{{ $room->Room_Type }}</h3>
                        <span class="room-number-badge"># {{ $room->Room_Number ?? $room->ROOM_ID }}</span>
                    </div>
                    <p>{{ $room->Details ?? 'Experience luxury and comfort in our finely appointed rooms.' }}</p>

                    <div class="inclusions-row">
                        @if($room->Inclusions)
                            @foreach(explode(',', $room->Inclusions) as $inclusion)
                                <span class="inclusion-chip">
                                    <i class="fa-solid fa-circle-check"></i>
                                    {{ trim($inclusion) }}
                                </span>
                            @endforeach
                        @else
                            <span class="inclusion-chip"><i class="fa-solid fa-wifi"></i> Wi-Fi</span>
                            <span class="inclusion-chip"><i class="fa-solid fa-snowflake"></i> Aircon</span>
                        @endif
                    </div>

                    @if($room->Status == 'Available')
                        <span class="room-price">₱{{ number_format($room->Price_Per_Night, 2) }} <span style="font-size:11px;font-weight:400;color:#9ca3af;">/ night</span></span>
                    @else
                        <span class="room-price-unavailable">Room Unavailable</span>
                    @endif
                </div>

                <div class="room-card-footer">
                    @if($room->Status == 'Available')
                        @if(session()->has('guest_id'))
                            <a href="/book/{{ $room->ROOM_ID }}?checkin={{ request('checkin') }}&checkout={{ request('checkout') }}&guests={{ request('guests') }}" class="btn-book">Book Now</a>
                        @else
                            <a href="/login" class="btn-book">Book Now</a>
                        @endif
                    @else
                        <button class="btn-book disabled" disabled>Not Available</button>
                    @endif
                </div><!-- /.room-card-footer -->
                </div><!-- /.room-card-body -->

            </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:40px;">
                    <i class="fa-solid fa-door-closed" style="font-size:32px;color:#e5e7eb;margin-bottom:12px;display:block;"></i>
                    <h3 style="color:#6b7280;font-size:15px;margin-bottom:6px;">No rooms found</h3>
                    <p style="font-size:13px;color:#9ca3af;">Try adjusting your filters or <a href="/rooms" style="color:#e8a000;font-weight:600;">view all rooms</a>.</p>
                </div>
            @endforelse
        </div>

        @if(method_exists($rooms, 'hasPages') && $rooms->hasPages())
            <p class="pagination-info">
                Showing {{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} of {{ $rooms->total() }} rooms
            </p>
            <div class="pagination-wrapper">
                @if($rooms->onFirstPage())
                    <span class="page-item disabled"><span><i class="fa fa-chevron-left"></i></span></span>
                @else
                    <span class="page-item"><a class="page-link" href="{{ $rooms->previousPageUrl() }}"><i class="fa fa-chevron-left"></i></a></span>
                @endif

                @foreach($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)
                    @if($page == $rooms->currentPage())
                        <span class="page-item active"><span class="page-link">{{ $page }}</span></span>
                    @else
                        <span class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></span>
                    @endif
                @endforeach

                @if($rooms->hasMorePages())
                    <span class="page-item"><a class="page-link" href="{{ $rooms->nextPageUrl() }}"><i class="fa fa-chevron-right"></i></a></span>
                @else
                    <span class="page-item disabled"><span><i class="fa fa-chevron-right"></i></span></span>
                @endif
            </div>
        @endif

    </div>

    @include('layouts.footer')
    @include('layouts.chat')

</body>
</html>