<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Challenge</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Sidebar -->
    @include('User_Folder.Sidebar')

    <!-- Top Bar -->
    <header class="top-bar">
        <div class="menu-btn" id="menuBtn">&#9776;</div>
        <p>CHOOSE YOUR PREFERRED CHALLENGE</p>
        <a href="{{ route('logout') }}" class="logout-btn">Log Out</a>
    </header>

    <!-- Main Content -->
    <main class="dashboard">
        <section class="challenge-container">
            <h2>Computer Systems Servicing</h2>
            <div class="competency-buttons">
                <button onclick="takeQuiz()" class="competency-btn">Certificate Of Competency 1</button>
                <button onclick="takeQuiz()" class="competency-btn">Certificate Of Competency 2</button>
                <button onclick="takeQuiz()" class="competency-btn">Certificate Of Competency 3</button>
            </div>
        </section>
    </main>

    <!-- Sidebar Toggle Script -->
    <script src="{{ asset('js/sidebar_function.js') }}"></script>
    <script>
        function takeQuiz() {
            window.location.href = "/quiz-show";
        }
    </script>
</body>

</html>