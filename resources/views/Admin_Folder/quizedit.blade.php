<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="{{ asset('/css/adminquiz.css') }}">
</head>

<body>
    <div class="quiz-container">
        <h2>Edit Quiz</h2>

        <form method="POST" action="{{ route('quiz-update', $quiz->id) }}">
            @csrf
            @method('PUT')
        
            <!-- Quiz info -->
            <label>Quiz number/name:</label>
            <input type="text" name="title" value="{{ $quiz->title }}" required>
        
            <label>Topic</label>
            <input type="text" name="description" value="{{ $quiz->description }}" required>
        
            <button onclick="window.location='{{ route('quiz-manage') }}'" type="button" class="submit-btn">Cancel</button>
        
            <hr>
        
            <h3>Questions</h3>
            <div id="questions-container">
                @foreach($questions as $qIndex => $q)
                    <div class="question-block">
                        <h4>Question {{ $qIndex + 1 }}</h4>
        
                        <!-- Question -->
                        <input type="hidden" name="questions[{{ $qIndex }}][id]" value="{{ $q->id }}">
                        <label>Question Text</label>
                        <input type="text" name="questions[{{ $qIndex }}][text]" value="{{ $q->question_text }}" required>
        
                        <!-- Options -->
                        <label>Choices</label>
                        @foreach($q->options as $optIndex => $opt)
                            <input type="hidden" name="questions[{{ $qIndex }}][option_ids][]" value="{{ $opt->id }}">
                            <input type="text" name="questions[{{ $qIndex }}][options][]" value="{{ $opt->option_text }}" required>
                        @endforeach
        
                        <!-- Correct answer select -->
                        <label>Correct Answer</label>
                        <select name="questions[{{ $qIndex }}][correct_option]" required>
                            @foreach($q->options as $optIndex => $opt)
                                <option value="{{ $optIndex }}"
                                    {{ $q->correct_option_id == $opt->id ? 'selected' : '' }}>
                                    Option {{ $optIndex + 1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                @endforeach
            </div>
        
            <button type="button" onclick="addQuestion()">+ Add Question</button>
            <button type="submit-btn">Update Quiz</button>
        </form>
        