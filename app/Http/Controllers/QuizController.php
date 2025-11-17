<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display quiz questions one at a time
     */
    public function show(Request $request)
    {
        // Validate quiz_id if included in request
        if ($request->has('quiz_id')) {
            $validated = $request->validate([
                'quiz_id' => 'required|exists:quizzes,id'
            ]);

            $quiz = Quiz::findOrFail($validated['quiz_id']);

            // Reset session if switching to a new quiz
            if (!session()->has('quiz_id') || session('quiz_id') !== $quiz->id) {
                session()->forget(['quiz_questions', 'quiz_index']);
            }

            session(['quiz_id' => $quiz->id]);
        }

        // Make sure quiz_id exists in session
        if (!session()->has('quiz_id')) {
            return redirect()->route('user-board')->with('error', 'No quiz selected.');
        }

        $quiz = Quiz::findOrFail(session('quiz_id'));
        $questionIds = $quiz->questions()->pluck('id')->toArray();

        // Initialize quiz session if not already done
        if (!session()->has('quiz_questions')) {
            session([
                'quiz_questions' => $questionIds,
                'quiz_index' => 0,
            ]);
        }

        $index = session('quiz_index');
        $currentQuestionId = session('quiz_questions')[$index] ?? null;

        // If quiz finished
        if (!$currentQuestionId) {
            session()->forget(['quiz_questions', 'quiz_index', 'quiz_id']);
            return redirect()->route('user-board')->with('success', 'Quiz Completed!');
        }

        $question = Question::findOrFail($currentQuestionId);
        $options = $question->options;
        $currentQuestionNumber = $index + 1;
        $totalQuestions = count($questionIds);

        $progress = round(($currentQuestionNumber / $totalQuestions) * 100) . '%';

        return view('User_Folder.TakeQuizPage', compact(
            'quiz',
            'question',
            'options',
            'currentQuestionNumber',
            'progress',
            'totalQuestions'
        ));
    }


    /**
     * Store user answer and move to next question
     */
    public function submitAnswer(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:questions,id',
            'answer' => 'required',
        ], [
            'answer.required' => 'Please select an answer before continuing.'
        ]);

        // Quiz session expired
        if (!session()->has('quiz_questions')) {
            return redirect()->route('user-board')->with('error', 'Quiz session expired.');
        }

        $question = Question::findOrFail($request->id);

        // Prevent duplicate answers from refreshing
        $alreadyAnswered = Answer::where([
            'question_id' => $question->id,
            // Add user/quiz identifiers here if needed
        ])->exists();

        if (!$alreadyAnswered) {
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => $request->answer, // stores option ID
                'is_correct' => $question->correct_option_id == $request->answer,
            ]);
        }

        // Move to next question
        session(['quiz_index' => session('quiz_index') + 1]);

        return redirect()->route('quiz.show')->with([
            'success' => ($question->correct_option_id == $request->answer) ? 'Correct!' : 'Wrong!'
        ]);
    }
}
