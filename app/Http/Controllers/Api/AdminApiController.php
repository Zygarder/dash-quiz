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

            $request->session()->regenerate();

            return response()->json([
                'status' => 'success',
                'role' => 'admin'
            ], 200);
        }

        //renegerate a session for user
        if (Auth::guard('dasher')->attempt($valid)) {
            $id = Auth::guard('dasher')->user()->id;

            $request->session()->regenerate();
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
    # DASHBOARD DATA API
    ###############################################
    public function dashboard()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_users' => DB::table('dasher')->count(),
                'total_quizzes' => DB::table('quizzes')->count(),
                'recent_logs' => DB::table('activity_logs')
                    ->orderBy('created_at', 'DESC')
                    ->limit(20)
                    ->get()
            ]
        ], 200);
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
            // 1. Fetch the quiz
            $sql = "SELECT * FROM quizzes WHERE id = ?";
            $quizData = DB::select($sql, [$id]);

            // If quiz doesn't exist, stop here and return a 404
            if (empty($quizData)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quiz not found'
                ], 404);
            }

            $quiz = $quizData[0];

            // 2. Fetch questions
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

            // 3. Return as pure JSON for Vue!
            return response()->json([
                'status' => 'success',
                'quiz' => $quiz,
                'questions' => $questions
            ], 200);

        } catch (\Exception $e) {
            // If the SQL fails, send the actual error message to the console
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

        // 1. Update the Main Quiz (No updated_at!)
        DB::update("UPDATE quizzes SET title = ?, description = ? WHERE id = ?", [
            $request->title,
            $request->description,
            $id
        ]);

        $receivedQuestionIds = [];

        // 2. Loop through Questions
        foreach ($request->questions as $qData) {
            
            // Update or Create Question
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

            // 3. Handle Options
            $existingOptions = DB::select("SELECT id FROM question_options WHERE question_id = ? ORDER BY id ASC", [$questionId]);
            
            // Delete old answers so we can rebuild them cleanly
            DB::delete("DELETE FROM answers WHERE question_id = ?", [$questionId]);

            foreach ($qData['options'] as $index => $optionText) {
                // Update or Create Option
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

                // Is this the correct option?
                $isCorrect = ($qData['correct_option'] == $index) ? 1 : 0;
                
                // Save to answers table
                DB::insert("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)", [
                    $questionId, 
                    $optionText, 
                    $isCorrect
                ]);

                // If this is the correct option, save its ID for the questions table
                if ($isCorrect == 1) {
                    $correctOptionIdForQuestionTable = $optionId;
                }
            }

            // Update the questions table with the correct_option_id
            DB::update("UPDATE questions SET correct_option_id = ? WHERE id = ?", [
                $correctOptionIdForQuestionTable, 
                $questionId
            ]);
        }

        // 4. Housekeeping: Remove deleted questions
        if (!empty($receivedQuestionIds)) {
            $placeholders = implode(',', array_fill(0, count($receivedQuestionIds), '?'));
            $deleteParams = array_merge([$id], $receivedQuestionIds);
            
            DB::delete("DELETE FROM questions WHERE quiz_id = ? AND id NOT IN ($placeholders)", $deleteParams);
        }

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

        $quiz->delete();

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