<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "name" => 'required',
            "password" => 'required'
        ], [
            'name.required' => 'fill the name',
            'password.required' => 'fill the password'
        ]);

        if ($request) {
            return response()->json(['success' => 'Successfully login']);
        }
    }
}
