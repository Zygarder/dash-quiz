<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f4ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .result-box {
            background: white;
            padding-bottom: 25px;
            width: 100%;
            max-width: 450px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        .quiz-title {
            background: #4b32a8;
            color: white;
            padding: 18px;
            border-radius: 12px 12px 0 0;
            font-size: 1.3rem;
            font-weight: bold;
        }

        h1 {
            margin-top: 25px;
            font-size: 1.8rem;
            color: #222;
        }

        .score {
            font-size: 1.6rem;
            margin-top: 10px;
            font-weight: bold;
            color: #4b32a8;
        }

        a {
            margin-top: 30px;
            display: inline-block;
            padding: 12px 18px;
            background: #4b32a8;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: .2s;
        }

        .btn:hover {
            background: #3a2586;
        }
    </style>

</head>

<body>

    <div class="result-box">
        <div class="quiz-title">
            {{ $quizTitle ?? 'Quiz Result' }}
        </div>

        <h1>Quiz Completed! 🎉</h1>

        <div class="score">
            Score: {{ $score }} / {{ $totalQuestions }}
        </div>

        <a href="{{ route('quiz-page') }}">Take Another Quiz</a>
    </div>

</body>

</html>
