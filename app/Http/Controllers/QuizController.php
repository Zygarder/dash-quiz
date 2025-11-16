<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show()
    {
        $quiz = Quiz::first();

        $questionIds = $quiz->questions()->pluck('id')->toArray();

        if (!session()->has('quiz_questions')) {
            session([
                'quiz_questions' => $questionIds,
                'quiz_index' => 0,
            ]);
        }

        $index = session('quiz_index');
        $currentQuestionId = session('quiz_questions')[$index] ?? null;

        if (!$currentQuestionId) {
            return "Quiz finished!";
        }

        // Fetch question and options
        $question = Question::find($currentQuestionId);
        $options = $question->options;
        $currentQuestionNumber = $index + 1;

        // PROGRESS BAR LOGIC
        $totalQuestions = count($questionIds);
        $progress = ($currentQuestionNumber / $totalQuestions) * 100;
        $progress = round($progress) . '%'; // Convert to "35%" format

        return view('User_Folder.TakeQuizPage', compact(
            'quiz',
            'question',
            'options',
            'currentQuestionNumber',
            'progress',
            'totalQuestions'
        ));
    }


    #                       #
    # SUBMIT ANSWER METHOD  #
    #                       #

    public function submitAnswer(Request $request)
    {

        $request->validate([
            'answer' => 'required',
        ], [
            'answer.required' => 'Please select an answer before continuing.'
        ]);

        $question = Question::find($request->question_id);

        // Store user answer
        $answer = Answer::create([
            'question_id' => $question->id,
            'answer_text' => $request->answer, // stores option ID
            'is_correct' => $question->correct_option_id == $request->answer,
        ]);

        // Increment session index
        $index = session('quiz_index');
        session(['quiz_index' => $index + 1]);

        return redirect()->route('quiz.show')->with([
            'success' => $answer->is_correct ? 'Correct!' : 'Wrong!'
        ]);
    }
}
