<?php

namespace App\Http\Controllers;

use App\Models\Dasher;
use App\Models\QuizRecord;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    ####### Below is ang mga code para ma view ang pages #########

    public function LoginPage()
    {
        return view('LoginPage');
    }

    public function RegisterPage()
    {
        return view('RegisterPage');
    }
    public function ForgotPage()
    {
        return view('ForgotPage');
    }

    //dont forget to add session destroy after logging out
    public function LogoutRequest()
    {
        Auth::guard('dasher')->logout();
        session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    ################## Below is code para sa mga requests ng user OK?!####################

    public function LoginRequest(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|string',
            'password' => 'required'
        ], [
            'email.required' => 'Email required',
            'password.required' => 'Password required',
        ]);

        if ($valid['email'] === 'admin@admin.com' && $valid['password'] === 'admin') {
            return redirect()->route('admin-board');
        }

        if (Auth::guard('dasher')->attempt($valid)) {
            return redirect()->intended(route('user-board'));
        }

        return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
    }

    public function RegisterRequest(Request $request)
    {
        // Validate the incoming request
        $valid = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
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

        Auth::guard('dasher')->loginUsingId($dasher->id);
        // Redirect to user dashboard after registration
        return redirect()->route('login')->with('success', 'Account created successfully!');
    }

    ############################### USER DB ##################################

    //Admin side nav controller
    //logs function
    public function logActivity($action, $description)
    {
        DB::table('activity_logs')->insert([
            'user_id' => Auth::guard('dasher')->user()->id, // Admin user ID
            'action_type' => $action,
            'description' => $description,
            'created_at' => now()
        ]);

    }
    public function Dashboard()
    {
        $dboard = DB::select("SELECT COUNT(*) as total FROM dasher")[0]->total;
        $totalQuizzes = DB::table('quizzes')->count();
        $logs = DB::select("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 20");

        return view('Admin_Folder.Dashboard', compact('dboard', 'totalQuizzes', 'logs'));
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
        $quiz_records = QuizRecord::all();
        return view('Admin_Folder.StudentRecords', compact('quiz_records'));
    }

    //database manager for users (admin side)
    public function dasherdelete($id)
    {
        //find user then delete
        $user = Dasher::findOrFail($id);
        $user->delete();
        $this->logActivity('User Deletion.', "User ID {$id} was deleted");
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
        $this->logActivity('New Quiz!', "Quiz $request->title was created");
        return redirect()->route('quiz-manage')->with('success', 'Quiz created successfully!, remember to take note of the quiz id in case of deletion tracking.');
    }

    public function editQuiz($id)
    {
        // Fetch the quiz
        $sql = "SELECT * FROM quizzes WHERE id = ?";
        $quiz = DB::select($sql, [$id])[0];

        // Fetch questions for this quiz
        $sql = "SELECT * FROM questions WHERE quiz_id = ?";
        $questions = DB::select($sql, [$id]);

        foreach ($questions as &$q) {
            // Fetch options including IDs
            $sql = "SELECT id, option_text FROM question_options WHERE question_id = ?";
            $q->options = DB::select($sql, [$q->id]);

            // Fetch the correct answer by option ID
            $sql = "SELECT id FROM answers WHERE question_id = ? AND is_correct = 1";
            $correct = DB::select($sql, [$q->id]);
            $q->correct_option_id = $correct ? $correct[0]->id : null;
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
            'questions.*.id' => 'required|integer',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|size:4',
            'questions.*.option_ids' => 'required|array|size:4',
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
        ]);

        // Update quiz
        DB::update("UPDATE quizzes SET title = ?, description = ? WHERE id = ?", [
            $valid['title'],
            $valid['description'],
            $id
        ]);

        // Update each question
        foreach ($valid['questions'] as $q) {
            $questionId = $q['id'];

            // Update question text
            DB::update("UPDATE questions SET question_text = ? WHERE id = ?", [
                $q['text'],
                $questionId
            ]);

            // Update options
            foreach ($q['options'] as $i => $optText) {
                $optionId = $q['option_ids'][$i];
                DB::update("UPDATE question_options SET option_text = ? WHERE id = ?", [
                    $optText,
                    $optionId
                ]);
            }

            // Reset all answers to incorrect
            DB::update("UPDATE answers SET is_correct = 0 WHERE question_id = ?", [$questionId]);
            // Set correct answer by option ID
            $correctOptionIndex = $q['correct_option'];
            $correctOptionId = $q['option_ids'][$correctOptionIndex];

            DB::update("UPDATE answers SET is_correct = 1 WHERE id = ?", [$correctOptionId]);
        }

        $this->logActivity('Edit.', "Quiz Title $request->title was updated");

        return redirect()->route('quiz-manage')->with('success', 'Quiz updated successfully!');
    }

    public function deletequiz($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        $this->logActivity('Deletion.', "Quiz ID $id was deleted");

        return redirect()->route('quiz-manage')->with('success', 'Quiz deleted');
    }
}
