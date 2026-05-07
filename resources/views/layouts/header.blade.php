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

    /* --- NAVIGATION --- */
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

    <form action="/rooms" method="GET" class="header-booking-widget">
        <div class="widget-field">
            <label>Check-in</label>
            <input type="date" name="checkin" required>
        </div>
        
        <div class="widget-field divider"></div>
        
        <div class="widget-field">
            <label>Check-out</label>
            <input type="date" name="checkout" required>
        </div>
        
        <div class="widget-field divider"></div>
        
        <div class="widget-field">
            <label>Guests</label>
            <input type="number" name="guests" min="1" value="2" required>
        </div>
        
        <button type="submit" class="check-btn">Check</button>
    </form>

    <nav>
        <a href="/"              class="{{ Request::is('/')              ? 'active' : '' }}">Home</a>
        <a href="/rooms"         class="{{ Request::is('rooms*')         ? 'active' : '' }}">Rooms</a>
        <a href="/reservations"  class="{{ Request::is('reservations*')  ? 'active' : '' }}">Reservations</a>
        @if(session()->has('guest_id'))
            <a href="/logout">Logout</a>
        @else
            <a href="/login" class="{{ Request::is('login') ? 'active' : '' }}">Login</a>
        @endif
    </nav>
</header>