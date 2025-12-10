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

        .quiz-container {
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
        <a href="{{ route('profile-page') }}">
            <img src="{{ auth()->guard('dasher')->user()->get_profile() }}" alt="DP"
                style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:1px solid #ccc;">
        </a>
    </header>

    <!-- Main Content -->
    <main class="container">
        <section class="challenge-container">
            <h2>Computer Systems Servicing</h2>

            <div class="quiz-container">
                {{-- quizzes from UserController --}}
                @foreach($quizzes as $quiz)
                    <a class="quiz-choice" href="{{ route('quiz.start', ['quiz_id' => $quiz->id]) }}">
                        <div class="competency-btn">
                            {{ $quiz->description }}
                        </div>
                    </a>
                @endforeach
            </div>

        </section>

    </main>

    <!-- Sidebar Toggle Script -->
    <script src="{{ asset('js/sidebar_function.js') }}"></script>
</body>

</html>