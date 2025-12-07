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
        input:invalid {
            border: 1px solid red;
        }


        .error {
            color: red;
            font-size: 13.5px;
        }

        .success-register {
            padding: 10px;
            background: #00b430;
            color: white;
            margin-bottom: 10px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0px 1px 0.5px 1px rgba(255, 255, 255, 1);
        }
    </style>
</head>

<body>
    <!-- Top Header -->
    <header class="top-bar">
        <p>Dash Quiz - Learn and test yourself!</p>
    </header>

    <!--success key from registration-->
    @if (session('success'))
        <div class="success-register">
            {{ session('success') }}
        </div>
    @endif

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

                <!-- Email In   put -->
                <input type="email" id="email" name="email" title="Enter your email" placeholder="Email Address"
                    value="{{ old('email') }}" />
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
                @if($errors->has('error'))
                    <div class="error">{{ $errors->first('error') }}</div>
                @endif

                <!-- Password Input -->
                <input type="password" id="pass" name="password" title="Enter your password"
                    placeholder="Enter Password" />
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Submit Button -->
                <button type="submit" class="login-btn">Log In</button>

                <!-- Links -->
                <p class="small-text">Forgot password? <a href="{{ route('forgot_page') }}" class="forgot">click
                        here</a></p>
                <button type="button" class="register-btn" onclick="location.href='{{ route('register_page') }}'" title="Make new account!">
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