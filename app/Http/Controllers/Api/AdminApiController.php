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

        if (Auth::guard('admin')->attempt($valid)) {
            $request->session()->regenerate();
            return response()->json([
                'status' => 'success',
                'role' => 'admin'
            ], 200);
        }

        if (Auth::guard('dasher')->attempt($valid)) {
            $request->session()->regenerate();
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
            'password' => Hash::make($valid['password']),
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