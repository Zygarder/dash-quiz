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
    <nav id="sidebar" class="sidebar">
        <h2>DASH QUIZ</h2>
        <ul>
            <li><a href="{{ route('user-board') }}">Dashboard</a></li>
            <li><a href="#" class="active">Take a Quiz</a></li>
            <li><a href="{{ route('record-page') }}">Check Records</a></li>
            <li> <a href="{{ route('profile-page') }}">My Profile</a></li>
        </ul>
    </nav>

    <!-- Top Bar -->
    <header class="top-bar">
        <div class="menu-btn" id="menuBtn">&#9776;</div>
        <p>CHOOSE YOUR PREFERRED CHALLENGE</p>
        <a href="index.html" class="logout-btn">Log Out</a>
    </header>

    <!-- Main Content -->
    <main class="dashboard">
        <section class="challenge-container">
            <h2>Computer Systems Servicing</h2>
            <div class="competency-buttons">
                <button class="competency-btn">Certificate Of Competency 1</button>
                <button class="competency-btn">Certificate Of Competency 2</button>
                <button class="competency-btn">Certificate Of Competency 3</button>
            </div>
        </section>
    </main>

    <!-- Sidebar Toggle Script -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
