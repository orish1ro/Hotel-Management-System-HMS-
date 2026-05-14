<style>
    /* --- HEADER BASE --- */
    header.guest-header {
        position: sticky;
        top: 0;
        z-index: 999;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 48px;
        height: 72px; /* Increased slightly to fit widget comfortably */
        background: #003366;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .guest-header .logo-text {
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
        white-space: nowrap;
        text-decoration: none;
        letter-spacing: 0.01em;
    }

    /* --- COMPACT BOOKING WIDGET --- */
    .header-booking-widget {
        display: flex;
        align-items: center;
        background: #ffffff;
        border-radius: 6px;
        padding: 4px;
        height: 48px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .widget-field {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 12px;
    }

    .widget-field label {
        font-size: 9px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }

    .widget-field input {
        border: none;
        outline: none;
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        background: transparent;
        width: 110px; /* Kept compact so it fits on smaller screens */
        padding: 0;
        cursor: pointer;
    }

    .widget-field input[type="number"] {
        width: 50px;
    }

    /* Vertical line separator between inputs */
    .widget-field.divider {
        width: 1px;
        height: 28px;
        background: #e5e7eb;
        padding: 0;
        margin: 0 4px;
    }

    .check-btn {
        background: #ffc107; /* Gold accent */
        color: #003366; /* Deep blue text to match header */
        border: none;
        border-radius: 4px;
        padding: 0 20px;
        height: 100%;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: background 0.2s;
    }

    .check-btn:hover {
        background: #e0a800; /* Darker gold on hover */
    }

    /* --- GREETING --- */
    .guest-greeting {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: rgba(255,255,255,0.85);
        white-space: nowrap;
    }
    .guest-greeting .avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 1.5px solid rgba(255,255,255,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    /* --- NOTIFICATION BELL --- */
    .notif-bell {
        position: relative;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 4px;
        color: rgba(255,255,255,0.75);
        font-size: 15px;
        text-decoration: none;
        transition: color 0.2s, background 0.2s;
        display: flex;
        align-items: center;
    }
    .notif-bell:hover { color: #fff; background: rgba(255,255,255,0.12); }
    .notif-dot {
        position: absolute;
        top: 4px;
        right: 6px;
        width: 8px;
        height: 8px;
        background: #ffc107;
        border-radius: 50%;
        border: 1.5px solid #003366;
    }

    /* --- DROPDOWN --- */
    .notif-wrapper { position: relative; }
    .notif-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: 300px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        border: 1px solid #e5e7eb;
        z-index: 9999;
        overflow: hidden;
        padding: 6px 0;
    }
    .notif-dropdown.open { display: block; }
    .notif-dropdown-header {
        padding: 14px 18px 10px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #6b7280;
        border-bottom: 1px solid #f3f4f6;
    }
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 13px 18px;
        border-bottom: 1px solid #f9fafb;
        text-decoration: none;
        transition: background 0.15s;
    }
    .notif-item:hover { background: #f9fafb; }
    .notif-item:last-child { border-bottom: none; }
    .notif-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .notif-icon.pending  { background: #fef9c3; color: #ca8a04; }
    .notif-icon.confirmed { background: #dcfce7; color: #16a34a; }
    .notif-icon.cancelled { background: #fee2e2; color: #dc2626; }
    .notif-icon.checked  { background: #eff6ff; color: #2563eb; }
    .notif-text { flex: 1; }
    .notif-text strong { font-size: 12.5px; font-weight: 700; color: #1f2937; display: block; }
    .notif-text span { font-size: 11px; color: #9ca3af; }
    .notif-empty {
        padding: 20px 16px;
        text-align: center;
        font-size: 12px;
        color: #9ca3af;
    }
    .notif-footer {
        padding: 12px 18px;
        border-top: 1px solid #f3f4f6;
        text-align: center;
    }
    .notif-footer a {
        font-size: 12px;
        font-weight: 600;
        color: #003366;
        text-decoration: none;
    }
    .notif-footer a:hover { text-decoration: underline; }
    .guest-header nav {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .guest-header nav a {
        font-size: 13px;
        font-weight: 600;
        color: rgba(255,255,255,0.80);
        text-decoration: none;
        padding: 6px 16px;
        border-radius: 4px;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        transition: color 0.2s, background 0.2s;
        white-space: nowrap;
    }

    .guest-header nav a:hover,
    .guest-header nav a.active {
        color: #fff;
        background: rgba(255,255,255,0.12);
    }

    /* ── RESPONSIVE: Tablet (≤1024px) ── */
    @media (max-width: 1024px) {
        header.guest-header {
            padding: 8px 16px;
            height: auto;
            min-height: 56px;
            flex-wrap: wrap;
            gap: 6px;
        }
        .header-booking-widget {
            order: 3;
            width: 100%;
            height: 42px;
            margin-bottom: 4px;
        }
        .widget-field input { width: 80px; font-size: 12px; }
        .check-btn { padding: 0 14px; font-size: 12px; }
        .guest-header .logo-text { font-size: 16px; }
        .guest-header nav a { font-size: 12px; padding: 5px 10px; }
    }

    /* ── RESPONSIVE: Mobile (≤600px) ── */
    @media (max-width: 600px) {
        header.guest-header {
            padding: 8px 12px;
            flex-direction: column;
            align-items: flex-start;
            height: auto;
            gap: 0;
        }
        .guest-header .logo-text { font-size: 15px; padding: 4px 0; }

        /* Hide booking widget — too cramped on small screens */
        .header-booking-widget { display: none !important; }

        .guest-header nav {
            width: 100%;
            display: flex;
            overflow-x: auto;
            gap: 2px;
            padding: 4px 0 6px;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }
        .guest-header nav::-webkit-scrollbar { display: none; }
        .guest-header nav a {
            font-size: 11px;
            padding: 5px 10px;
            white-space: nowrap;
            flex-shrink: 0;
        }
    }
</style>

<header class="guest-header">
    <a href="/" class="logo-text">Ragadio Plaza Hotel</a>

    <nav>
        <a href="/"              class="{{ Request::is('/')              ? 'active' : '' }}">Home</a>
        <a href="/rooms"         class="{{ Request::is('rooms*')         ? 'active' : '' }}">Rooms</a>
        <a href="/reservations"  class="{{ Request::is('reservations*')  ? 'active' : '' }}">Reservations</a>

        @if(session()->has('guest_id'))
            @php
                $headerGuest = DB::table('guest')->where('GUEST_ID', session('guest_id'))->first();
                $pendingCount = DB::table('reservation')
                    ->where('GUEST_ID', session('guest_id'))
                    ->whereIn('Status', ['Pending', 'Confirmed'])
                    ->count();
                $recentReservations = DB::table('reservation')
                    ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
                    ->where('reservation.GUEST_ID', session('guest_id'))
                    ->orderBy('reservation.RESERVATION_ID', 'desc')
                    ->limit(5)
                    ->select('reservation.*', 'room.Room_Type')
                    ->get();
            @endphp

            {{-- Greeting --}}
            <div class="guest-greeting">
                <div class="avatar">{{ strtoupper(substr($headerGuest->First_Name ?? 'G', 0, 1)) }}</div>
              {{ $headerGuest->First_Name ?? 'Guest' }}
            </div>



            <a href="/logout">Logout</a>
        @else
            <a href="/login" class="{{ Request::is('login') ? 'active' : '' }}">Login</a>
        @endif
    </nav>
</header>

<script>
function toggleNotif(e) {
    e.preventDefault();
    e.stopPropagation();
    document.getElementById('notifDropdown').classList.toggle('open');
}
document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notifDropdown')?.classList.remove('open');
    }
});
</script>