<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuizController extends Controller
{
    public function generateQuiz(Request $request)
    {
        // Input validation
        $validated = $request->validate([
            'kategori' => 'required|string',
            'jumlah' => 'required|integer',
            'kesulitan' => 'required|string',
            'level' => 'required|string',
        ]);

        // Call Python API to generate quiz
        $response = Http::post('http://127.0.0.1:5000/generate-quiz', [
            'kategori' => $validated['kategori'],
            'jumlah' => $validated['jumlah'],
            'kesulitan' => $validated['kesulitan'],
            'level' => $validated['level'],
        ]);

        return response()->json($response->json());
    }
}
