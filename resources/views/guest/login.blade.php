<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    
    <style>
        header.guest-header {
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 72px; 
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
            width: 110px; 
            padding: 0;
            cursor: pointer;
        }

        .widget-field input[type="number"] {
            width: 50px;
        }

        .widget-field.divider {
            width: 1px;
            height: 28px;
            background: #e5e7eb;
            padding: 0;
            margin: 0 4px;
        }

        .check-btn {
            background: #ffc107; 
            color: #003366; 
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
            background: #e0a800; 
        }

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
    </style>
</head>
<body>

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

    <div class="form-wrapper">
        <div class="form-card">
            
            @if(session('error'))
                <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                    {{ session('error') }}
                </div>
            @endif

            <h2>Login</h2>
            
            <form action="/login-submit" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <div class="form-footer">
                Don't have an account? <a href="/signup">Sign up here</a>
            </div>
        </div>
    </div>

</body>
</html>