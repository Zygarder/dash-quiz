<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
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

        // Return quiz + questions
        return response()->json([
            'status' => 'success',
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'questions' => $questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'text' => $q->question_text,
                        'options' => $q->options->map(function ($opt) {
                            return [
                                'id' => $opt->id,
                                'text' => $opt->option_text
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
            'answer_id' => 'required|exists:answers,id',
        ]);

        $user = Auth::guard('dasher')->user();
        $answer = Answer::findOrFail($validated['answer_id']);
        $correct = $answer->is_correct;

        // Return response with correctness
        return response()->json([
            'status' => 'success',
            'correct' => $correct
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
}
