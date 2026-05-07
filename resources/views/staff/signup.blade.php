<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Signup - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body> 
    <div class="form-wrapper">
        <div class="form-card">
            <h2 style="color: #003366; text-align: center;">STAFF REGISTRATION</h2>
            
            <!-- THIS BLOCK DISPLAYS LARAVEL VALIDATION ERRORS -->
            @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/staff/signup-submit" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>FIRST NAME:</label>
                    <input type="text" name="first_name" required>
                </div>

                <div class="form-group">
                    <label>LAST NAME:</label>
                    <input type="text" name="last_name" required>
                </div>

                <div class="form-group">
                    <label>STAFF EMAIL:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>ROLE:</label>
                    <select name="role" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; background-color: #eef2fb; box-sizing: border-box; font-family: inherit;">
                        <option value="" disabled selected>Select a role...</option>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>PASSWORD:</label>
                    <input type="password" name="password" minlength="6" required>
                </div>

                <button type="submit" class="btn-submit" style="background-color: #d9534f;">CREATE STAFF ACCOUNT</button>
            </form>
            
            <div class="form-footer">
                Already have an account? <a href="/staff/login">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>