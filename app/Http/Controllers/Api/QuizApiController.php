<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizApiController extends Controller
{
    // Fetch quiz details & first question
    public function getQuiz(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id'
        ]);

        $quiz = Quiz::with('questions.options')->findOrFail($validated['quiz_id']);

        // Take first 10 questions randomly
        $questions = $quiz->questions()->inRandomOrder()->limit(10)->get();

        // Option labels
        $optionLabels = ['A', 'B', 'C', 'D'];

        // Return quiz + questions with labeled options
        return response()->json([
            'status' => 'success',
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'total_questions' => $questions->count(),
                'questions' => $questions->map(function ($q, $index) use ($optionLabels) {
                    $options = $q->options->shuffle(); // Randomize option order
                    return [
                        'id' => $q->id,
                        'text' => $q->question_text,
                        'question_number' => $index + 1,
                        'options' => $options->values()->map(function ($opt, $optIndex) use ($optionLabels) {
                            return [
                                'id' => $opt->id,
                                'text' => $opt->option_text,
                                'label' => $optionLabels[$optIndex] ?? chr(65 + $optIndex),
                            ];
                        })
                    ];
                })
            ]
        ]);
    }

    // Submit answer for a question
    public function submitAnswer(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required|exists:question_options,id',
        ]);

        $user = Auth::guard('dasher')->user();
        $question = Question::findOrFail($validated['question_id']);
        $correct = $question->correct_option_id == $validated['answer_id'];

        // Return response with correctness and progress info
        return response()->json([
            'status' => 'success',
            'correct' => $correct,
            'correct_option_id' => $question->correct_option_id
        ]);
    }

    // Store final quiz record
    public function submitQuizResult(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'score' => 'required|integer|min:0'
        ]);

        $user = Auth::guard('dasher')->user();

        $record = QuizRecord::create([
            'user_id' => $user->id,
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['score'],
            'completed_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'record' => $record
        ]);
    }

    // Get quiz progress and option statistics
    public function getQuizProgress(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id'
        ]);

        $user = Auth::guard('dasher')->user();
        $quiz = Quiz::with('questions.options')->findOrFail($validated['quiz_id']);

        // Get user's previous answers for this quiz (if any)
        $previousRecords = QuizRecord::where('user_id', $user->id)
            ->where('quiz_id', $validated['quiz_id'])
            ->get();

        // Calculate option choice statistics (A, B, C, D)
        $optionStats = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0
        ];

        $totalAnswers = 0;
        foreach ($quiz->questions as $question) {
            // This would need actual answer tracking - for now, return structure
            // In a real implementation, you'd track user answers in a separate table
        }

        return response()->json([
            'status' => 'success',
            'progress' => [
                'total_questions' => $quiz->questions->count(),
                'completed_quizzes' => $previousRecords->count(),
                'best_score' => $previousRecords->max('score') ?? 0,
                'option_statistics' => $optionStats,
                'total_answers_tracked' => $totalAnswers
            ]
        ]);
    }
}
