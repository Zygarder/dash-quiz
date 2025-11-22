<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Account</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    body {
      background-color: #f5f6fa;
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .top-bar {
      background-color: #4b3fc2;
      color: #fff;
      padding: 12px 20px;
      font-weight: 600;
      text-align: center;
      font-size: 1rem;
    }

    .center-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .register-box {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 380px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .register-box h2 {
      color: #3f2f87;
      text-align: center;
      margin-bottom: 15px;
    }

    .register-box input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 0.95rem;
      width: 100%;
    }

    .register-box input:focus {
      border-color: #4b3fc2;
      outline: none;
      box-shadow: 0 0 5px rgba(75, 63, 194, 0.5);
    }

    .register-btn {
      padding: 12px;
      background-color: #4b3fc2;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .register-btn:hover {
      background-color: #3f2ea3;
    }

    .error {
      color: red;
      font-size: 0.85rem;
      margin-top: -10px;
      margin-bottom: 10px;
    }

    .small-text {
      text-align: center;
      margin-top: 10px;
      font-size: 0.85rem;
      color: #555;
    }

    .small-text a {
      color: #4b3fc2;
      font-weight: 600;
      text-decoration: none;
    }

    footer {
      background-color: #4b3fc2;
      color: #fff;
      text-align: center;
      padding: 12px;
      font-size: 0.85rem;
    }

    @media (max-width: 576px) {
      .register-box {
        padding: 25px 15px;
      }

      .register-box h2 {
        font-size: 1.2rem;
      }

      .register-btn {
        font-size: 0.9rem;
      }
    }

    form input {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <header class="top-bar">
    New Account
  </header>

  <main class="center-container">
    <div class="register-box">
      <h2>Account Registration</h2>

      <form method="POST" action="{{ route('register_request') }}">
        @csrf

        <input id="name" type="text" name="name" placeholder="First Name" value="{{ old('name') }}" />
        @error('name')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="last" type="text" name="last" placeholder="Last Name" value="{{ old('last') }}" />
        @error('last')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="email" type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" />
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="password" type="password" name="password" placeholder="Enter Password" />
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Re-type Password" />

        <button type="submit" class="register-btn">Submit</button>

        <p class="small-text">
          Already have an account? <a href="{{ route('login_page') }}">click here</a>
        </p>
      </form>
    </div>
  </main>

  <footer>
    © 2025 Dash Quiz All Rights Reserved.
  </footer>
</body>

</html>