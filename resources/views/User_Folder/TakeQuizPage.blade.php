<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
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

        .quiz-container {
            width: 100%;
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-bottom: 10px;
            text-align: center;
            font-size: 1.3rem;
        }

        /* Progress bar */
        .progress-wrapper {
            width: 100%;
            background: #ddd;
            border-radius: 50px;
            margin: 15px 0;
            height: 12px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: lightgreen;
            width:
                {{ $progress ?? '0%' }}
            ;
            transition: width 0.3s ease-in-out;
        }

        .question-box {
            padding: 20px;
            text-align: center;
            background: #4b32a8;
            color: white;
            border-radius: 8px;
            font-size: 1.1rem;
            margin-top: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
        }

        label {
            background: #f8f8f8;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.2s;
            font-size: 1rem;
        }

        label:hover {
            border-color: #4b32a8;
        }

        label:has(input:checked) {
            background: #4b32a8;
            color: white;
            border-color: #4b32a8;
        }

        input[type="radio"] {
            display: none;
        }

        button {
            background: #4b32a8;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 1rem;
            margin-top: 10px;
        }

        button:hover {
            background: #36248b;
        }

        /* Mobile */
        @media (max-width: 500px) {
            .quiz-container {
                padding: 15px;
            }

            label {
                font-size: 0.95rem;
            }
        }

        .error {
            padding: 5px 10px;
            color: red;
            background: pink;
            text-align: center;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <header class="quiz-header">
        {{ $quiz->title ?? '[ Quiz Topic ]' }}
    </header>

    <div class="container">
        <div class="quiz-container">

            <h2>Question {{ $currentQuestionNumber ?? 1 }}</h2>

            <!-- Progress bar -->
            <div class="progress-wrapper">
                <div class="progress-bar"></div>
            </div>
            {{-- Display here the error is no option is selected --}}
            @error('answer')
                <div class="error">{{ $message }}</div>
            @enderror
            <!-- Question -->
            <div class="question-box">
                {{ $question->question_text ?? 'Question goes here...' }}
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('quiz.submit') }}">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id ?? '' }}">

                @if(isset($options) && count($options))
                    @foreach($options as $option)
                        <label>
                            <input type="radio" name="answer" value="{{ $option->id }}">
                            {{ $option->option_text }}
                        </label>
                    @endforeach
                @else
                    <p>No options available.</p>
                @endif

                <button type="submit">Next</button>
            </form>



        </div>
    </div>

</body>

</html>