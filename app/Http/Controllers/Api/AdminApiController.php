<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dasher;
use App\Models\QuizRecord;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminApiController extends Controller
{
    ###############################################
    # Logging API
    ###############################################
    public function logActivity($action, $description)
    {
        DB::table('activity_logs')->insert([
            'admin_id' => Auth::guard('admin')->user()->id ?? 1,
            'action_type' => $action,
            'description' => $description,
            'created_at' => now()
        ]);
    }

    ###############################################
    # DASHBOARD DATA API (Merged & Cleaned!)
    ###############################################
    public function dashboard()
    {
        $totalUsers = DB::table('dasher')->count();
        $totalQuizzes = DB::table('quizzes')->count();
        $activeCount = Dasher::where("active_status", '=', 1)->count();

        // Grab the latest 20 logs
        $logs = DB::table('activity_logs')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Return perfectly formatted JSON for Vue
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_users' => $totalUsers,
                'total_quizzes' => $totalQuizzes,
                'active_users' => $activeCount,
                'logs' => $logs
            ]
        ], 200);
    }

    ###############################################
    # LOGIN API
    ###############################################

    public function login(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //renegerate a session for admin
        if (Auth::guard('admin')->attempt($valid)) {

            // get admin id
            $id = Auth::guard('admin')->user()->id;
            DB::update("UPDATE admin SET active_status = 1 WHERE id = ?", [$id]);
            $request->session()->regenerate();

            return response()->json([
                'status' => 'success',
                'role' => 'admin'
            ], 200);
        }

        //renegerate a session for user
        if (Auth::guard('dasher')->attempt($valid)) {
            // get dasher id
            $id = Auth::guard('dasher')->user()->id;

            $request->session()->regenerate();
            //set active_status = true
            DB::update("UPDATE dasher SET active_status = 1 WHERE id = ?", [$id]);

            return response()->json([
                'status' => 'success',
                'role' => 'dasher'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    ###############################################
    # REGISTER API
    ###############################################

    public function register(Request $request)
    {
        $valid = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:dasher,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $dasher = Dasher::create([
            'first_name' => $valid['first_name'],
            'last_name' => $valid['last_name'],
            'email' => $valid['email'],
            'password' => Hash::make($valid['password'])
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully',
            'data' => $dasher
        ], 201);
    }


    ###############################################
    # LOGOUT API
    ###############################################
    public function logout(Request $request)
    {
        // Explicitly log out the user from the "dasher" guard
        if (Auth::guard('dasher')->check()) {

            // Get current user's ID
            $id = Auth::guard('dasher')->user()->id;

            // Mark user as offline by updating active_status
            Dasher::where('id', $id)->update(['active_status' => 0]);

            // Log the user out
            Auth::guard('dasher')->logout();
        }

        // Explicitly log out the admin from the "admin" guard
        if (Auth::guard('admin')->check()) {

            // Get current user's ID
            $id = Auth::guard('admin')->user()->id;

            // Mark admin as offline by updating active_status
            Dasher::where('id', $id)->update(['active_status' => 0]);

            // Log the admin out
            Auth::guard('admin')->logout();
        }

        // Invalidate the session to remove all session data
        $request->session()->invalidate();

        // Regenerate CSRF token for security
        $request->session()->regenerateToken();
    }



    ###############################################
    # QUIZ MANAGEMENT APIs
    ###############################################
    public function allQuizzes()
    {
        return response()->json([
            'status' => 'success',
            'data' => Quiz::all()
        ], 200);
    }

    public function createQuiz(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|size:4',
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
        ]);

        DB::beginTransaction();

        try {
            DB::insert("INSERT INTO quizzes (title, description) VALUES (?, ?)", [
                $request->title,
                $request->description
            ]);

            $quiz_id = DB::getPdo()->lastInsertId();

            foreach ($request->questions as $q) {
                DB::insert("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)", [
                    $quiz_id,
                    $q['text']
                ]);

                $question_id = DB::getPdo()->lastInsertId();

                foreach ($q['options'] as $i => $opt) {
                    DB::insert(
                        "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)",
                        [
                            $question_id,
                            $opt,
                            $i == $q['correct_option'] ? 1 : 0
                        ]
                    );
                }
            }

            // <-- LOG ACTIVITY HERE (Creation)
            $this->logActivity('New Quiz!', "Quiz '{$request->title}' was created");

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz created successfully',
                'quiz_id' => $quiz_id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create quiz',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    ###############################################
    # EDIT QUIZ (API Fetch)
    ###############################################
    public function editQuiz($id)
    {
        try {
            $sql = "SELECT * FROM quizzes WHERE id = ?";
            $quizData = DB::select($sql, [$id]);

            if (empty($quizData)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quiz not found'
                ], 404);
            }

            $quiz = $quizData[0];
            $sql = "SELECT * FROM questions WHERE quiz_id = ?";
            $questions = DB::select($sql, [$id]);

            foreach ($questions as &$q) {
                $sql = "SELECT id, option_text FROM question_options WHERE question_id = ?";
                $q->options = DB::select($sql, [$q->id]);

                $sql = "SELECT id FROM answers WHERE question_id = ? AND is_correct = 1";
                $correct = DB::select($sql, [$q->id]);
                $q->correct_option_id = $correct ? $correct[0]->id : null;
            }

            return response()->json([
                'status' => 'success',
                'quiz' => $quiz,
                'questions' => $questions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server crash: ' . $e->getMessage()
            ], 500);
        }
    }

    ###############################################
    # UPDATE QUIZ (API Submission)
    ###############################################
    public function updateQuiz(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|size:4',
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
        ]);

        try {
            DB::beginTransaction();

            DB::update("UPDATE quizzes SET title = ?, description = ? WHERE id = ?", [
                $request->title,
                $request->description,
                $id
            ]);

            $receivedQuestionIds = [];

            foreach ($request->questions as $qData) {
                if (isset($qData['id'])) {
                    $questionId = $qData['id'];
                    DB::update("UPDATE questions SET question_text = ? WHERE id = ?", [
                        $qData['text'],
                        $questionId
                    ]);
                } else {
                    DB::insert("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)", [
                        $id,
                        $qData['text']
                    ]);
                    $questionId = DB::getPdo()->lastInsertId();
                }

                $receivedQuestionIds[] = $questionId;
                $correctOptionIdForQuestionTable = null;

                $existingOptions = DB::select("SELECT id FROM question_options WHERE question_id = ? ORDER BY id ASC", [$questionId]);

                DB::delete("DELETE FROM answers WHERE question_id = ?", [$questionId]);

                foreach ($qData['options'] as $index => $optionText) {
                    if (isset($existingOptions[$index])) {
                        $optionId = $existingOptions[$index]->id;
                        DB::update("UPDATE question_options SET option_text = ? WHERE id = ?", [
                            $optionText,
                            $optionId
                        ]);
                    } else {
                        DB::insert("INSERT INTO question_options (question_id, option_text) VALUES (?, ?)", [
                            $questionId,
                            $optionText
                        ]);
                        $optionId = DB::getPdo()->lastInsertId();
                    }

                    $isCorrect = ($qData['correct_option'] == $index) ? 1 : 0;

                    DB::insert("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)", [
                        $questionId,
                        $optionText,
                        $isCorrect
                    ]);

                    if ($isCorrect == 1) {
                        $correctOptionIdForQuestionTable = $optionId;
                    }
                }

                DB::update("UPDATE questions SET correct_option_id = ? WHERE id = ?", [
                    $correctOptionIdForQuestionTable,
                    $questionId
                ]);
            }

            if (!empty($receivedQuestionIds)) {
                $placeholders = implode(',', array_fill(0, count($receivedQuestionIds), '?'));
                $deleteParams = array_merge([$id], $receivedQuestionIds);
                DB::delete("DELETE FROM questions WHERE quiz_id = ? AND id NOT IN ($placeholders)", $deleteParams);
            }

            // <-- LOG ACTIVITY HERE (Update)
            $this->logActivity('Edit', "Quiz Title '{$request->title}' was updated");

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Quiz updated successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to update: ' . $e->getMessage()], 500);
        }
    }

    ###############################################
    # DELETE QUIZ
    ###############################################
    public function deleteQuiz($id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found'
            ], 404);
        }

        $quizTitle = $quiz->title; // Save title before deleting for the log
        $quiz->delete();

        // <-- LOG ACTIVITY HERE (Quiz Deletion)
        $this->logActivity('Deletion', "Quiz '{$quizTitle}' (ID: $id) was deleted");

        return response()->json([
            'status' => 'success',
            'message' => "Quiz ID $id deleted"
        ], 200);
    }

    ###############################################
    # USERS TABLE API
    ###############################################
    public function allUsers()
    {
        return response()->json([
            'status' => 'success',
            'data' => Dasher::all()
        ], 200);
    }

    public function deleteUser($id)
    {
        $user = Dasher::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        // <-- LOG ACTIVITY HERE (User Deletion)
        $this->logActivity('User Deletion', "Dasher ID {$id} was deleted");

        return response()->json([
            'status' => 'success',
            'message' => "User ID $id deleted"
        ], 200);
    }

    ###############################################
    # STUDENT RECORDS API
    ###############################################
    public function studentRecords()
    {
        return response()->json([
            'status' => 'success',
            'data' => QuizRecord::all()
        ], 200);
    }
}