<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Quiz</title>
    <link rel="stylesheet" href="{{ asset('/css/adminquiz.css') }}">
</head>

<body>
    <div class="quiz-container">
        <h2>Create Quiz</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('quiz-save') }}">
            @csrf

            <!-- Quiz Name -->
            <label>Quiz Name:</label>
            <input type="text" name="title" placeholder="Quiz" required>

            <label>Topic</label>
            <input type="text" name="description" placeholder="description" required>

            <button onclick="location.href='{{ route('quiz-manage') }}'" class="submit-btn">Cancel</button>

            <hr>

            <h3>Questions</h3>
            <div id="questions-container"></div>

            <button class="submit-btn" onclick="addQuestion()">+ Add Question</button>
            <button type="submit" class="submit-btn">Save Quiz</button>
        </form>
    </div>

    <script src="{{ asset('js/addq.js') }}"></script>
</body>

</html>