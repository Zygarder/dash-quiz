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
      padding: 10px 20px;
      text-align: center;
    }

    .center-container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      height: 100vh;
    }

    .register-box {
      background-color: #fff;
      padding: 20px 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 350px;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .register-box h2 {
      color: #3f2f87;
      text-align: center;
      margin-bottom: 15px;
    }

    .register-box input {
      padding: 10px 5px;
      margin: 3px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 13px;
      text-indent: 10px;
      width: 100%;
    }

    .register-box input:focus {
      border-color: #4b3fc2;
      outline: none;
      box-shadow: 0 0 5px rgba(75, 63, 194, 0.5);
    }

    .register-btn {
      padding: 10px 5px;
      width: 100%;
      background-color: #4b3fc2;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.5s ease-out;
    }

    .register-btn:hover {
      background-color: #3f2ea3;
    }

    .error {
      color: red;
      font-size: 12px;
    }

    .small-text {
      text-align: center;
      margin-top: 10px;
      font-size: 0.85rem;
      color: #999;
    }

    .small-text a {
      color: black;
      text-decoration: none;
    }

    footer {
      width: 100%;
      position: fixed;
      left:0;
      bottom: 0;
      background-color: #4b3fc2;
      color: #fff;
      text-align: center;
      padding: 10px;
    }

    input:invalid {
      border-color: red;
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
    Create New Account
  </header>

  <main class="center-container">
    <div class="register-box">
      <h2>Account Registration</h2>

      <form method="POST" action="{{ route('register_request') }}">
        @csrf
        <input id="first_name" type="text" name="first_name" maxlength="50" placeholder="First Name" value="{{ old('first_name') }}" />
        @error('first_name')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="last_name" type="text"  name="last_name" maxlength="50" placeholder="Last Name" value="{{ old('last_name') }}" />
        @error('last_name')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="email" type="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
          placeholder="Email Address" value="{{ old('email') }}" />
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="password" type="password" name="password" placeholder="Enter Password" minlength="6" />
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror

        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Re-type Password"
          minlength="6" />

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