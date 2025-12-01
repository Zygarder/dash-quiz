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
        /* Forgot Password */
        .forgot-box {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            width: 340px;
            height: auto;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .forgot-box h2 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .forgot-box p {
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 15px;
        }

        .forgot-box input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 10px;
        }

        .forgot-box button {
            width: 100%;
        }

        .forgot-box .small-text {
            margin-top: 10px;
            font-size: 0.8rem;
        }

        .forgot-box .small-text a {
            color: #00a02b;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-container {
            max-width: 100%;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        input:invalid {
            border: 1px solid red;
        }

        .error {
            text-align: center;
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

        .input-box {
            width: 100%;
            display: flex;
            justify-content: center;
            font-weight: 400;
            align-items: center;
        }

        .send-btn {
            text-align: center;
            background-color: green;
            color: #fff;
            padding: 10px 25px;
            margin-top: 3%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    </style>
</head>

<body>
    <!-- Top Header -->
    <header class="top-bar">
        <p>Forgot password</p>
    </header>

    <!--success key from registration-->
    @if (session('success'))
        <div class="success-register">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Section -->
    <main class="forgot-container">

        <div class="forgot-box">

            <form method="POST" action="{{ route('login_request') }}">
                <p class="small-text">Enter your email address, we will verify your email account. Then we will give a
                    OTP to that account</p>
                @csrf
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
                <div class="input-box">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" title="Enter your email"
                        placeholder="example@dasher.com" value="{{ old('email') }}" />
                </div>

                <button type="submit" class="send-btn">Send</button>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Dash Quiz All Rights Reserved.</p>
    </footer>
</body>

</html>