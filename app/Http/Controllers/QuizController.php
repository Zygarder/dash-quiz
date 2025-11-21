<?php
namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /*
     * Display quiz questions one at a ti``me
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

            // Reset session if mag change or back ng quizzes
            if (session('quiz_id') !== $quizId) {
                session()->forget(['quiz_questions', 'quiz_index', 'score']);// reset session 
            }

            session([
                'quiz_id' => $quizId,
                'score' => 0,
            ]);
        }

        // No quiz selected then return to quiz selection page
        if (!session()->has('quiz_id')) {
            session()->forget(['quiz_questions', 'quiz_index', 'score']); // reset session 
            return redirect()->route('take-quiz-page');
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

        // finished quiz
        if (!$currentQuestionId) {
            $score = session('score');
            $quizTitle = session('quiz_title');

            return view('User_Folder.QuizResult', compact('score', 'quizTitle'));
        }

        $question = Question::findOrFail($currentQuestionId);
        $options = $question->options;
        $currentQuestionNumber = $index + 1;
        $totalQuestions = count($questionIds);
        $progress = round($currentQuestionNumber / $totalQuestions * 100) . '%';

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
        $question = Question::findOrFail($request->id);
        if (!session()->has('quiz_questions')) {
            return redirect()->route('user-board')->with('error', 'Quiz session expired.');
        } else {
            $isCorrect = $question->correct_option_id == $request->answer;

            if ($isCorrect) {
                $currentScore = session('score', 0);
                session(['score' => $currentScore + 1]);
            }
        }
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

        return redirect()->route('quiz.start')->with([
            'success' => ($question->correct_option_id == $request->answer) ? 'Correct!' : 'Wrong!'
        ]);
    }
}


