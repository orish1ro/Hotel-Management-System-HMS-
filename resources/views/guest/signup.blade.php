<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

   <header>
        <div class="logo-text">Ragadio Plaza Hotel</div>
        <div class="search-container">
            <input type="text" placeholder="search">
        </div>
        <nav>
            <a href="/">Home</a>
            <a href="/rooms">Rooms</a>
            <a href="/reservations">Reservations</a>
            
            @if(session()->has('guest_id'))
                <a href="/customer.message">Messages</a>
                <a href="/logout">Logout</a>
            @else
                <a href="/login">Login</a>
            @endif
        </nav>
    </header>

    <div class="form-wrapper">
        <div class="form-card">
            <h2>Create Account</h2>

            @if ($errors->any())
                <div style="color: red; margin-bottom: 15px; font-size: 14px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/signup-submit" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-submit">Register</button>
            </form>

            <div class="form-footer">
                Already have an account? <a href="/login">Back to Login</a>
            </div>
        </div>
    </div>

</body>
</html>