<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="{{ asset('/css/adminquiz.css') }}">
    @vite(['resources/js/app.js'])
</head>
<body>
    
    <script>
        window.quizData = {
            quiz: @json($quiz),
            questions: @json($questions)
        };
    </script>
    
    @vite(['resources/js/app.js'])
    
    <div id="app">
        <router-view></router-view>
    </div>

</body>
</html>