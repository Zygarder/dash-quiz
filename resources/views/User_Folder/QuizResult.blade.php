<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>

    {{-- CSS Styles --}}

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f4ff;
        }

        header.quiz-header {
            display: flex;
            justify-content: center;
            background: #4b32a8;
            padding: 15px;
            width: 100%;
            color: white;
            font-size: 1.3rem;
            font-weight: bold;
        }

        .container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }
    </style>
</head>

<body>

    <header class="quiz-header">
        {{ $quiz->description ?? '[ Quiz Topic ]' }}
    </header>

    <div class="container">
        <h1>Quiz Completed!</h1>
        <div>Score 10 / 10</div>
    </div>

</body>

</html>