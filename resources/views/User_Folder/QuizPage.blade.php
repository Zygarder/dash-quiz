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
        <p>CHOOSE YOUR QUIZ</p>
        <a href="{{ route('logout') }}" class="logout-btn">Log Out</a>
    </header>

    <!-- Main Content -->
    <main class="container">
        <section class="challenge-container">
            <h2>Computer Systems Servicing</h2>

            <div class="quiz-container">
                @foreach($quizzes as $quiz)
                    <form action="{{ route('quiz.start') }}" class="quiz-choice" method="GET">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <button type="submit" class="competency-btn">{{ $quiz->title }}</button>
                    </form>
                @endforeach
            </div>
            
        </section>

    </main>

    <!-- Sidebar Toggle Script -->
    <script src="{{ asset('js/sidebar_function.js') }}"></script>
</body>

</html>