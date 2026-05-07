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