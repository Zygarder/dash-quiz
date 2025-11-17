<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Challenge</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .quiz-choice {
            display: inline-block;
            margin: 10px;
        }

        .quiz-container{
            text-align: center;
        }
    </style>
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
    <main class="container">
        <section class="challenge-container">
            <h2>Computer Systems Servicing</h2>

            <div class="quiz-container">
                <form action="{{ route('quiz.show') }}" class="quiz-choice" method="POST">
                    @csrf
                    <input type="hidden" name="quiz_id" value="1">
                    <button type="submit" class="competency-btn">Certificate Of Competency 1</button>
                </form>

                <form action="{{ route('quiz.show') }}" class="quiz-choice" method="POST">
                    @csrf
                    <input type="hidden" name="quiz_id" value="2">
                    <button type="submit" class="competency-btn">Certificate Of Competency 2</button>
                </form>

                <form action="{{ route('quiz.show') }}" class="quiz-choice" method="POST">
                    @csrf
                    <input type="hidden" name="quiz_id" value="3">
                    <button type="submit" class="competency-btn">Certificate Of Competency 3</button>
                </form>
            </div>
        </section>

    </main>

    <!-- Sidebar Toggle Script -->
    <script src="{{ asset('js/sidebar_function.js') }}"></script>
</body>

</html>