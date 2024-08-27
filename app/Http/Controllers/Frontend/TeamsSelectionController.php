<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\League;
use App\Models\TeamUser;
use Inertia\Inertia;

use Illuminate\Http\Request;

class TeamsSelectionController extends Controller
{


    public function index()
    {

        $leagues = League::with('teams')->get();

        return Inertia::render('TeamsSelection', [
            'leagues' => $leagues
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'selectedTeams' => 'required|array|min:1',
            'selectedTeams.*' => 'exists:teams,id', // Ensure that each selected team exists in the teams table
        ]);

        $teams = []; 

        foreach ($data['selectedTeams'] as $selection) {
            $teams[] = [
                'user_id' => auth()->user()->id,
                'team_id' => $selection,
                'created_at' => now(),
                'updated_at' => now(),
            ];   
        }
        
        TeamUser::insert($teams);
    }
}
