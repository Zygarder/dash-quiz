<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dash Quiz - Learn and test yourself!</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .error {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
    
<body>
    <!-- Top Header -->
    <header class="top-bar">
        <p>Dash Quiz - Learn and test yourself!</p>
    </header>

    <!-- Main Section -->
    <main class="container">
        <div class="left-section">
            <h1>
                Learning is<br />
                better when we<br />
                do it together.
            </h1>
        </div>

        <div class="right-section">
            <form class="login-box" method="POST" action="{{ route('login_request') }}">
                @csrf
                <input type="email" id="email" name="email" placeholder="Email Address" />
                @error('email')
                <div class="error"> Email Error</div>
                @enderror
                <!-- Displays the error-->
                <input type="password" id="pass" name="password" placeholder="Enter Password" autocomplete="false" />
                @error('password')
                <div class="error">Password Error</div>
                @enderror
                <!-- Displays the error-->  

                <button type="submit" class="login-btn">Log In</button>

                <a href="forgot.html" class="forgot">forgot password?</a>
                <button class="register-btn" onclick="window.location.href='register.html'">
                    Register Now!
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Dash Quiz All Rights Reserved.</p>
    </footer>
