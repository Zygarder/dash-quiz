<?php

namespace App\Http\Controllers;

use App\Models\Dasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    ####### Below is ang mga code para ma view ang pages #########

    //go to register page
    public function RegisterPage()
    {
        return view('RegisterPage');
    }
    public function LoginPage()
    {
        return view('LoginPage');
    }

    //dont forget to add session destroy after logging out
    public function Logout()
    {
        return view('LoginPage');
    }

    public function LoginRequest(Request $request)
    {
        //dw about it, just an admin login test credentials.
        if ($request->email === 'admin@admin.com' && $request->password === 'admin') {
            return redirect()->route('admin-board');
        }
        #################### REMOVE ABOVE WHEN EVERYTHING IS READY OKAY?!#################
        ################## Below is code para sa mga requests ng user OK?!####################

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        return redirect()->route('user-board');
    }

    public function RegisterRequest(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:dasher,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // Create the user
        Dasher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Redirect to user dashboard after registration
        return redirect()->route('login_page')->with('success', 'Account created successfully!');
    }


    ############################### USER DB ##################################



    //Admin side nav controller
    public function Dashboard()
    {
        return view('Admin_Folder.Dashboard');
    }

    public function Quizmgmt()
    {
        $quizzes = DB::select("select * from quizzes");
        return view('Admin_Folder.ManageQuestions', compact('quizzes'));
    }
    public function UserTable()
    {
        $dasher = DB::select("select * from dasher");
        return view('Admin_Folder.UsersTable', compact('dasher'));
    }
    public function srecords()
    {
        return view('Admin_Folder.StudentRecords');
    }



    //db manager for users (admin side)

    public function dasherdelete($id)
    {
        DB::delete("DELETE from dashers where id=?", [$id]);
        return redirect('user-table')->with('success', 'data deleted');
    }

    //DB FOR QUIZZES
    public function addquiz()
    {
        return view('Admin_Folder.quizadd');
    }

    public function savequiz(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:10', // require at least 10 questions
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|size:4', // always 4 choices
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
        ]);

        // Create Quiz
        // 1. Insert Quiz
        DB::insert(
            "INSERT INTO quizzes (title, description) VALUES (?, ?)",
            [$request->title, $request->description]
        );

        // Get quiz ID
        $quiz_id = DB::getPdo()->lastInsertId();

        // 2. Loop through questions
        foreach ($request->questions as $q) {

            // Insert Question
            DB::insert(
                "INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)",
                [$quiz_id, $q['text']]
            );

            $question_id = DB::getPdo()->lastInsertId();

            $option_ids = [];

            // 3. Insert options
            foreach ($q['options'] as $opt) {

                DB::insert(
                    "INSERT INTO question_options (question_id, option_text) VALUES (?, ?)",
                    [$question_id, $opt]
                );

                // Save option ID so we know which one is correct later
                $option_ids[] = DB::getPdo()->lastInsertId();
            }

            // 4. Mark correct answer in `answers` table
            $correctIndex = $q['correct_option']; // 0,1,2,3

            DB::insert(
                "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)",
                [
                    $question_id,
                    $q['options'][$correctIndex],  // correct answer text
                    1
                ]
            );

            // Optional: Insert incorrect answers if your table needs them
            foreach ($q['options'] as $i => $opt) {
                if ($i !== $correctIndex) {
                    DB::insert(
                        "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)",
                        [$question_id, $opt, 0]
                    );
                }
            }
        }


        return redirect()->route('admin-board')->with('success', 'Quiz created successfully!');
    }

    public function editQuiz($id)
    {
        // 1. Fetch the quiz
        $quiz = DB::select("SELECT * FROM quizzes WHERE id = ?", [$id])[0];

        // 2. Fetch questions for this quiz
        $questions = DB::select("SELECT * FROM questions WHERE quiz_id = ?", [$id]);

        // 3. For each question, fetch options and correct answer
        foreach ($questions as &$q) {
            $q->options = DB::select("SELECT option_text FROM question_options WHERE question_id = ?", [$q->id]);
            $q->options = array_map(fn($o) => $o->option_text, $q->options); // extract text

            $correct = DB::select("SELECT answer_text FROM answers WHERE question_id = ? AND is_correct = 1", [$q->id]);
            $q->correct_option = $correct ? $correct[0]->answer_text : null;
        }

        return view('quiz-edit', compact('quiz', 'questions'));
    }


    public function updateQuiz(Request $request, $id)
    {
        // 1. Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|size:4',
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
        ]);

        // 2. Update quiz
        DB::update(
            "UPDATE quizzes SET title = ?, description = ? WHERE id = ?",
            [$request->title, $request->description, $id]
        );

        // 3. Update questions/options/answers
        foreach ($request->questions as $qIndex => $q) {

            // Fetch existing question
            $question = DB::select(
                "SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC LIMIT 1 OFFSET ?",
                [$id, $qIndex]
            )[0];

            // Update question text
            DB::update(
                "UPDATE questions SET question_text = ? WHERE id = ?",
                [$q['text'], $question->id]
            );

            // Update options
            foreach ($q['options'] as $optIndex => $opt) {
                $option = DB::select(
                    "SELECT * FROM question_options WHERE question_id = ? ORDER BY id ASC LIMIT 1 OFFSET ?",
                    [$question->id, $optIndex]
                )[0];

                DB::update(
                    "UPDATE question_options SET option_text = ? WHERE id = ?",
                    [$opt, $option->id]
                );
            }

            // Update answers
            // Sets all answers to incorrect first** DONT FORGET TO DOUBLE CHECK
            DB::update(
                "UPDATE answers SET is_correct = 0 WHERE question_id = ?",
                [$question->id]
            );

            // Mark the correct answer
            $correctText = $q['options'][$q['correct_option']];
            DB::update(
                "UPDATE answers SET is_correct = 1 WHERE question_id = ? AND answer_text = ?",
                [$question->id, $correctText]
            );
        }

        return redirect()->route('quiz-manage')->with('success', 'Quiz updated successfully!');
    }
    public function deletequiz($id) {
        DB::delete('delete * from quizzes where id=?',[$id]);
        return redirect('quiz-manage')->with('success','Quiz Deleted');
    }
}
