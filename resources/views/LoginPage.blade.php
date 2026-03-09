<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dash Quiz - Learn and test yourself!</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        input:invalid { border: 1px solid red; }
        .error { color: red; font-size: 13.5px; }
        .success-register { padding: 10px; background: #00b430; color: white; margin-bottom: 10px; text-align: center; font-weight: 600; box-shadow: 0px 1px 0.5px 1px rgba(255, 255, 255, 1); }
    </style>
</head>

<body>
    <div id="app">

        <header class="top-bar">
            <p>Dash Quiz - Learn and test yourself!</p>
        </header>

        <main class="container">
            <div class="left-section">
                <h1>
                    Learning is<br />
                    better when we<br />
                    do it together.
                </h1>
                
                <hello-vue></hello-vue>
                
            </div>

            <div class="right-section">
                <login-form 
                    csrf-token="{{ csrf_token() }}"
                    submit-url="{{ route('login_request') }}"
                    forgot-url="{{ route('forgot_page') }}"
                    register-url="{{ route('register_page') }}"
                    old-email="{{ old('email') }}"
                    laravel-email-error="{{ $errors->first('email') ?: $errors->first('error') }}"
                    laravel-password-error="{{ $errors->first('password') }}"
                ></login-form>
            </div>
        </main>

        <footer>
            <p>© 2025 Dash Quiz All Rights Reserved.</p>
        </footer>
        
    </div> </body>

</html>