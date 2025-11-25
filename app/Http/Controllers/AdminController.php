<?php

namespace App\Http\Controllers;

use App\Models\Dasher;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    ####### Below is ang mga code para ma view ang pages #########
    public function RegisterPage()
    {
        return view('RegisterPage');
    }

    public function LoginPage()
    {
        return view('LoginPage');
    }

    //dont forget to add session destroy after logging out
    public function LogoutRequest()
    {
        Auth::guard('dasher')->logout();
        session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login_page');
    }

    ################## Below is code para sa mga requests ng user OK?!####################

    public function LoginRequest(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::guard('dasher')->attempt($valid)) {
            dd(Auth::guard('dasher'));
            return redirect()->intended(route('user-board'));
        }

        return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
    }

    public function RegisterRequest(Request $request)
    {
        // Validate the incoming request
        $valid = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:dasher,email',
            'password' => 'required|string|confirmed|min:6',
        ], [
            'first_name.required' => 'First required',
            'last_name.required' => 'Last required',
            'email.required' => 'Email required',
            'email.unique' => 'Email already taken',
            'password.required' => 'Password required',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        // Create the user
        $dasher = Dasher::create([
            'first_name' => $valid['first_name'],
            'last_name' => $valid['last_name'],
            'email' => $valid['email'],
            'password' => Hash::make($valid['password']),
        ]);



        $ye = Auth::guard('dasher')->loginUsingId($dasher->id);
        
        // Redirect to user dashboard after registration
        return redirect()->route('login_page')->with('success', 'Account created successfully!');
    }

    ############################### USER DB ##################################

    //Admin side nav controller
    public function Dashboard()
    {
        $dboard = DB::select("SELECT COUNT(*) as total FROM dasher")[0]->total;
        $totalQuizzes = DB::table('quizzes')->count();

        return view('Admin_Folder.Dashboard', compact('dboard', 'totalQuizzes'));
    }

    public function Quizmgmt()
    {
        $quizzes = Quiz::all();
        return view('Admin_Folder.ManageQuestions', compact('quizzes'));
    }
    public function UserTable()
    {
        $dasher = Dasher::all();
        return view('Admin_Folder.UsersTable', compact('dasher'));
    }
    public function srecords()
    {
        return view('Admin_Folder.StudentRecords');
    }

    //database manager for users (admin side)
    public function dasherdelete($id)
    {
        //find user then delete
        $user = Dasher::findOrFail($id);
        dd($user);
        return redirect()->route('user-table')->with('success', 'data deleted');
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

        // prepare the query
        $sql = "INSERT INTO quizzes (title, description) VALUES (?, ?)";
        // Insert Quiz
        DB::insert($sql, [$request->title, $request->description]);

        // Get quiz ID
        $quiz_id = DB::getPdo()->lastInsertId();

        // Loop through questions
        foreach ($request->questions as $q) {

            // Insert Question
            DB::insert(
                "INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)",
                [$quiz_id, $q['text']]
            );

            $question_id = DB::getPdo()->lastInsertId();

            // Initialize option IDs array to save it here later on
            $option_ids = [];

            // Insert options
            foreach ($q['options'] as $opt) {
                $sql = "INSERT INTO question_options (question_id, option_text) VALUES (?, ?)";
                DB::insert($sql, [$question_id, $opt]);

                // Save option ID so we know which one is correct later
                $option_ids[] = DB::getPdo()->lastInsertId();
            }

            // Mark correct answer in `answers` table
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
        // Fetch the quiz
        $sql = "SELECT * FROM quizzes WHERE id = ?";
        $quiz = DB::select($sql, [$id])[0];

        // Fetch questions for this quiz
        $sql = "SELECT * FROM questions WHERE quiz_id = ?";
        $questions = DB::select($sql, [$id]);

        // For each question, fetch options and correct answer
        foreach ($questions as &$q) {
            $sql = "SELECT option_text FROM question_options WHERE question_id = ?";
            $q->options = DB::select($sql, [$q->id]);
            $q->options = array_map(fn($opt) => $opt->option_text, $q->options); // extract text

            $sql = "SELECT answer_text FROM answers WHERE question_id = ? AND is_correct = 1";
            $correct = DB::select($sql, [$q->id]);
            $q->correct_option = $correct ? $correct[0]->answer_text : null;
        }

        return view('/Admin_folder.quizedit', compact('quiz', 'questions'));
    }

    public function updateQuiz(Request $request, $id)
    {
        // Validate input
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'required|integer',  // ← important
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|size:4',
            'questions.*.option_ids' => 'required|array|size:4', // ← important
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
            'questions.*.answer_id' => 'required|integer', // ← important
        ]);

        $sql = "UPDATE quizzes SET title = ?, description = ? WHERE id = ?";
        // Update quiz
        DB::update($sql, [$valid['title'], $valid['description'], $id]);

        // Update each question
        foreach ($valid['questions'] as $q) {
            //prepare the query
            $sql = "UPDATE questions SET question_text = ? WHERE id = ?";
            $questionId = $q['id'];

            // Update question text
            DB::update($sql, [$q['text'], $questionId]);

            // Update options
            foreach ($q['options'] as $i => $optText) {
                $sql = "UPDATE question_options SET option_text = ? WHERE id = ?";
                $optionId = $q['option_ids'][$i];

                DB::update($sql, [$optText, $optionId]);
            }

            // Reset all answers to incorrect
            $sql = "UPDATE answers SET is_correct = 0 WHERE question_id = ?";
            DB::update($sql, [$questionId]);

            // Set correct answer
            $correctOptionIndex = $q['correct_option'];
            $correctText = $q['options'][$correctOptionIndex];

            $sql = "UPDATE answers SET is_correct = 1 WHERE question_id = ? AND answer_text = ?";
            DB::update($sql, [$questionId, $correctText]);
        }

        return redirect()->route('quiz-manage')->with('success', 'Quiz updated successfully!');
    }

    public function deletequiz($id)
    {
        Quiz::findOrFail($id)->delete();

        return redirect()->route('quiz-manage')->with('success', 'data deleted');
    }
}
