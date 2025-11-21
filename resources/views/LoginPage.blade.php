<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dash Quiz - Learn and test yourself!</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Inline styles (optional) -->
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

                <!-- Email Input -->
                <input type="email" id="email" name="email" placeholder="Email Address" value="{{ old('email') }}" />
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
                @if($errors->has('error'))
                    <div class="error">{{ $errors->first('error') }}</div>
                @endif

                <!-- Password Input -->
                <input type="password" id="pass" name="password" placeholder="Enter Password" />
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Submit Button -->
                <button type="submit" class="login-btn">Log In</button>

                <!-- Links -->
                <a href="{{ route('register_page') }}"  target="_blank" class="forgot">Forgot password?</a>
                <button type="button" class="register-btn"
                    onclick="window.location.href='{{ route('register_page') }}'" target="_blank">
                    Register Now!
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Dash Quiz All Rights Reserved.</p>
    </footer>
</body>

</html>