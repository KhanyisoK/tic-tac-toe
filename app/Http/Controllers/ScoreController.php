<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;

class ScoreController extends Controller
{
    /**
     * Retrieve the top 10 scores in descending order.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Score::take(10)->orderBy('score', 'DESC')->get();
    }

    /**
     * Store a new score or update an existing score.
     *
     * @param  \App\Http\Requests\ScoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'player' => 'required',
            'score' => 'required|numeric'
        ]);

        // Check if a score record already exists for the player
        $score = Score::firstOrNew(['player' => $request->player]);

        // Add the new score to the existing score
        $score->score += $request->score;

        // Save the updated score record
        $score->save();

        // Return the updated score as JSON response with HTTP status code 201 (Created)
        return response()->json($score, 201);
    }
}
