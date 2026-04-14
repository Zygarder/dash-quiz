<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $apiKey = 'AIzaSyC3idYzd06V-eKCbkvVVgMfK48nrjFZpbU';
    
        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey,
            [
                'system_instruction' => [
                    'parts' => [
                        [
                            'text' => "You are Dash Quiz AI assistant.
You ONLY talk about Dash Quiz system.

Dash Quiz is a web-based NC II readiness and performance analytics system for students.

If the user asks unrelated questions, politely redirect them back to Dash Quiz topics."
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