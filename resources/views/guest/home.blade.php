<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ragadio Plaza Hotel - Premium Stay</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        .hero-booking-widget {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 8px;
            padding: 6px;
            margin-top: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            width: fit-content;
        }
        .hero-widget-field {
            display: flex;
            flex-direction: column;
            padding: 4px 14px;
        }
        .hero-widget-field label {
            font-size: 9px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 3px;
        }
        .hero-widget-field input {
            border: none;
            outline: none;
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            background: transparent;
            width: 120px;
            padding: 0;
            cursor: pointer;
        }
        .hero-widget-field input[type="number"] { width: 50px; }
        .hero-widget-divider {
            width: 1px;
            height: 30px;
            background: #e5e7eb;
            margin: 0 4px;
            flex-shrink: 0;
        }
        .hero-check-btn {
            background: #ffc107;
            color: #003366;
            border: none;
            border-radius: 6px;
            padding: 10px 22px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            transition: background 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .hero-check-btn:hover { background: #e0a800; transform: translateY(-1px); }

        @media (max-width: 700px) {
            .hero-booking-widget {
                flex-direction: column;
                width: 100%;
                align-items: stretch;
                gap: 4px;
            }
            .hero-widget-divider { display: none; }
            .hero-widget-field { padding: 6px 10px; border-bottom: 1px solid #f0f0f0; }
            .hero-widget-field input { width: 100%; }
            .hero-check-btn { width: 100%; justify-content: center; margin-top: 4px; }
        }
    </style>
</head>
<body>

    @include('layouts.header')

    <section class="hero">
        <div class="hero-content">
            <h1>Experience Elegance <br>& Ultimate Comfort</h1>
            <p>Your premium destination for business and leisure in the heart of the city.</p>
            <div class="hero-buttons">
                <a href="/rooms" class="btn btn-primary">Browse Rooms</a>
                <a href="/reservations" class="btn btn-secondary">My Reservation</a>
            </div>

            <form action="/rooms" method="GET" class="hero-booking-widget">
                <div class="hero-widget-field">
                    <label>Check-in</label>
                    <input type="date" name="checkin" required>
                </div>
                <div class="hero-widget-divider"></div>
                <div class="hero-widget-field">
                    <label>Check-out</label>
                    <input type="date" name="checkout" required>
                </div>
                <div class="hero-widget-divider"></div>
                <div class="hero-widget-field">
                    <label>Guests</label>
                    <input type="number" name="guests" min="1" value="2" required>
                </div>
                <button type="submit" class="hero-check-btn">
                    <i class="fa-solid fa-magnifying-glass"></i> Check Availability
                </button>
            </form>
        </div>
    </section>

    <section class="features-container">
        <div class="feature-card">
            <i class="fa-solid fa-bed"></i>
            <h4>Luxury Living</h4>
            <p>Experience our standard and deluxe rooms designed for ultimate relaxation.</p>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-utensils"></i>
            <h4>Fine Dining</h4>
            <p>Enjoy world-class dining at our signature restaurant with local cuisines.</p>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-spa"></i>
            <h4>Wellness & Spa</h4>
            <p>Rejuvenate your body and mind at our exclusive luxury spa facilities.</p>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-map-location-dot"></i>
            <h4>Prime Location</h4>
            <p>Located in the heart of the city, easily access top tourist spots and malls.</p>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <span class="sub-title">Our Story</span>
            <h2>About Ragadio Plaza Hotel</h2>
            <div class="divider"></div>
            <p>Ragadio Plaza Hotel is your premium destination for business and leisure. We pride ourselves on delivering exceptional service, luxurious accommodations, and a welcoming atmosphere. Whether you are staying for a quick business trip or a long vacation, our dedicated staff is here to ensure your experience is nothing short of perfect.</p>
        </div>
    </section>

    @include('layouts.footer')
    @include('layouts.chat')

</body>
</html>