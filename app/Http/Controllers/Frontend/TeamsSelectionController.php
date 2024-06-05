<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\League;
use Inertia\Inertia;

class TeamsSelectionController extends Controller
{


    public function __invoke()
    {

        $leagues = League::with('teams')->get();

        return Inertia::render('TeamsSelection', [
            'leagues' => $leagues
        ]);
    }
}
