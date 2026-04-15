<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $user = Auth::guard('dasher')->user();

        $records = QuizRecord::where('user_id', Auth::guard('dasher')->id())
            ->with(['quiz'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $historyText = "";

        foreach ($records as $record) {
            $historyText .= "- {$record->quiz->title} ({$record->created_at->format('Y-m-d')}): Score {$record->score}\n";
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $apiKey = 'AIzaSyDmdj4WoNJ_tFPoAsyJ4L_oNxc9XBBpu6k';

        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $apiKey,
            [
                'system_instruction' => [
                    'parts' => [
                        [
                            'text' => "
Ralph and Louie developed the system.
Ralph developed you.

You are Dash Quiz AI assistant.

You ONLY talk about Dash Quiz system.

Dash Quiz is a web-based NC II readiness and performance analytics system for students.

You are currently talking to:
Name: {$user->first_name}

Recent Quiz Records:
{$historyText}

Your role is to:
- Help the student understand their quiz performance
- Give feedback based on their scores
- Suggest improvements for NC II readiness
- Explain quizzes and analytics

When possible:
- Mention their quiz history
- Give advice based on their scores
- Encourage improvement

If the user asks unrelated questions, politely redirect them back to Dash Quiz topics.
"
                        ]
                    ]
                ],

                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $request->message
                            ]
                        ]
                    ]
                ]
            ]
        );

        return response()->json($response->json());
    }
}