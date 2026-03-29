<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizApiController extends Controller
{
    // Get quiz with random questions and their options
    public function getQuiz($id)
    {
        // Find the quiz and eager load its questions and options
        // eager loading prevents N+1 query problems
        $quiz = Quiz::with('questions.options')->findOrFail($id);

        // Get random questions from the quiz
        // whereHas('options') ensures the question actually has answer choices
        // otherwise the frontend would receive questions without options
        $questions = $quiz->questions()
            ->whereHas('options')
            ->inRandomOrder()
            ->limit(10) // limit quiz to 10 questions
            ->get();

        // Labels used for options (A, B, C, D)
        $optionLabels = ['A', 'B', 'C', 'D'];

        return response()->json([
            'status' => 'success',
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'total_questions' => $questions->count(),

                // Transform questions into a clean API structure for the frontend
                'questions' => $questions->values()->map(function ($q, $index) use ($optionLabels) {

                    // Shuffle options so the correct answer is not always in the same position
                    $options = $q->options->shuffle();

                    return [
                        'id' => $q->id,
                        'text' => $q->question_text,
                        'question_number' => $index + 1, // used for displaying question order

                        // Send options to frontend
                        'options' => $options->values()->map(function ($opt, $optIndex) use ($optionLabels) {
                            return [
                                'id' => $opt->id,
                                'text' => $opt->option_text,

                                // Assign label (A, B, C, D) based on index
                                // fallback using ASCII if there are more than 4 options
                                'label' => $optionLabels[$optIndex] ?? chr(65 + $optIndex),
                            ];

                        })->toArray()
                    ];

                })->toArray()
            ]
        ]);
    }

    // Check if the submitted answer is correct
    public function submitAnswer(Request $request)
    {
        // Validate incoming data
        // ensures question and answer exist in the database
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required|exists:question_options,id',
        ]);

        // Load question with its options
        $question = Question::with('options')->findOrFail($validated['question_id']);

        // Find the option selected by the user
        $selectedOption = $question->options->firstWhere('id', $validated['answer_id']);

        // Find the correct option from the question
        $correctOption = $question->options->firstWhere('is_correct', true);

        // Compare selected option with the correct option
        $isCorrect = $selectedOption && $correctOption &&
            $selectedOption->id === $correctOption->id;

        // Return result to frontend
        return response()->json([
            'status' => 'success',
            'correct' => $isCorrect
        ]);
    }

    // Save the final quiz score for the user
    public function submitQuizResult(Request $request)
    {
        // Validate submitted quiz result
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'score' => 'required|integer|min:0'
        ]);

        // Get the authenticated user using the "dasher" guard
        $user = Auth::guard('dasher')->user();

        // Store quiz result in database
        $record = QuizRecord::create([
            'user_id' => $user->id,
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['score'],
            'completed_at' => now()
        ]);

        // Return stored record
        return response()->json([
            'status' => 'success',
            'record' => $record
        ]);
    }
}