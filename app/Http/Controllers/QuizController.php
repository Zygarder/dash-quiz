<?php
namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /*
     * Display quiz questions one at a time
     */
    public function show(Request $request)
    {
        // Initialize quiz session and unsa na quiz ang gi pili base sa quiz_id
        if ($request->has('quiz_id')) {
            $validated = $request->validate([
                'quiz_id' => 'required|exists:quizzes,id'
            ]);

            $quizId = $validated['quiz_id'];

            $quiz = Quiz::findOrFail($quizId);

            session([
                'quiz_id' => $quizId,
                'score' => session('score'),
                'quiz_title' => $quiz->title,
            ]);

        }

        // Reset session if mag change or back ng quizzes
        if (session('quiz_id') !== $request->quiz_id) {
            session()->forget(['quiz_questions', 'quiz_index', 'score']);// reset session 
        }

        // Load quiz once
        $quiz = Quiz::with('questions.options')->findOrFail(session('quiz_id'));
        $questionIds = $quiz->questions->pluck('id')->toArray();

        // Initialize quiz session
        if (!session()->has('quiz_questions')) {
            session([
                'quiz_questions' => $quiz->questions()  //10 questions are randomly chosen for the session
                    ->inRandomOrder()
                    ->limit(10)
                    ->pluck('id')
                    ->toArray(),
                'quiz_index' => 0,
            ]);
        }

        $index = session('quiz_index');
        $currentQuestionId = session('quiz_questions')[$index] ?? [];

        // Redirect to result page after finishing the quiz
        if (!$currentQuestionId) {
            $score = session('score');
            $quizTitle = session('quiz_title');
            $totalQuestions = count(session('quiz_questions'));

            // Store quiz result in database
            if (Auth::guard('dasher')->check()) {
                QuizRecord::create([
                    'user_id' => auth()->guard('dasher')->id(),
                    'quiz_id' => $request->quiz_id,
                    'score' => $score,
                    'created_at' => now()->format('y-m-d'),
                ]);
            }
            return view('User_Folder.QuizResult', compact('score', 'quizTitle', 'totalQuestions'));
        }

        $question = Question::findOrFail($currentQuestionId);
        $options = $question->options;
        $currentQuestionNumber = $index + 1;
        $totalQuestions = count($questionIds);
        $progress = round($currentQuestionNumber / $totalQuestions * 100) . '%';

        return response()
            ->view('User_Folder.TakeQuizPage', compact(
                'quiz',
                'question',
                'options',
                'currentQuestionNumber',
                'progress',
                'totalQuestions'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Store user answer and move to next question
     */
    public function submitAnswer(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:questions,id',
            'answer' => 'required|exists:answers,id',
        ]);

        // Session expired
        if (!session()->has('quiz_questions')) {
            return redirect()->route('take-quiz-page')->with('error', 'Session expired.');
        }

        // Get the selected answer option
        $selectedOption = Answer::findOrFail($request->answer);

        // Check correctness
        if ($selectedOption->is_correct) {
            session(['score' => session('score', 0) + 1]);

        }

        // Move to next question
        session(['quiz_index' => session('quiz_index') + 1]);

        return redirect()->back();
    }
}


