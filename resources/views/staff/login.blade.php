<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body> 
    <div class="form-wrapper">
        <div class="form-card">
            <h2 style="color: #003366; text-align: center;">STAFF PORTAL</h2>
            
            @if(session('error'))
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="/staff/login-submit" method="POST">
                @csrf
                <div class="form-group">
                    <label>Staff Email:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="btn-submit" style="background-color: #003366;">Staff Login</button>
            </form>
            
            <div class="form-footer">
                Don't have a staff account? <a href="/staff/signup">Sign up here</a><br><br>
                <a href="/">Return to Guest Site</a>
            </div>
        </div>
    </div>
</body>
</html>