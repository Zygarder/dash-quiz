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
            'email' => 'required|string',
            'password' => 'required'
        ]);

        // STATIC ADMIN LOGIN
        if ($valid['email'] === 'admin@admin.com' && $valid['password'] === 'admin') {
            return response()->json([
                'status' => 'success',
                'role' => 'admin',
                'message' => 'Admin authenticated'
            ]);
        }

        if (Auth::guard('dasher')->attempt($valid)) {
            return response()->json([
                'status' => 'success',
                'role' => 'dasher',
                'message' => 'Login successful',
                'user' => Auth::guard('dasher')->user()
            ]);
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
            'user' => $dasher
        ], 201);
    }

    ###############################################
    # DASHBOARD DATA API
    ###############################################
    public function dashboard()
    {
        return response()->json([
            'status' => 'success',
            'total_users' => DB::table('dasher')->count(),
            'total_quizzes' => DB::table('quizzes')->count(),
            'recent_logs' => DB::table('activity_logs')
                ->orderBy('created_at', 'DESC')
                ->limit(20)
                ->get()
        ]);
    }

    ###############################################
    # QUIZ MANAGEMENT APIs
    ###############################################
    public function allQuizzes()
    {
        return response()->json([
            'status' => 'success',
            'data' => Quiz::all()
        ]);
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

            $option_ids = [];

            foreach ($q['options'] as $opt) {
                DB::insert("INSERT INTO question_options (question_id, option_text) VALUES (?, ?)", [
                    $question_id, $opt
                ]);

                $option_ids[] = DB::getPdo()->lastInsertId();
            }

            $correct = $q['correct_option'];

            DB::insert(
                "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, 1)",
                [$question_id, $q['options'][$correct]]
            );

            foreach ($q['options'] as $i => $opt) {
                if ($i !== $correct) {
                    DB::insert(
                        "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, 0)",
                        [$question_id, $opt]
                    );
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz created',
            'quiz_id' => $quiz_id
        ]);
    }

    ###############################################
    # DELETE QUIZ
    ###############################################
    public function deleteQuiz($id)
    {
        Quiz::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Quiz ID $id deleted"
        ]);
    }

    ###############################################
    # USERS TABLE API
    ###############################################
    public function allUsers()
    {
        return response()->json([
            'status' => 'success',
            'data' => Dasher::all()
        ]);
    }

    public function deleteUser($id)
    {
        Dasher::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => "User ID $id deleted"
        ]);
    }

    ###############################################
    # STUDENT RECORDS API
    ###############################################
    public function studentRecords()
    {
        return response()->json([
            'status' => 'success',
            'data' => QuizRecord::all()
        ]);
    }
}
