<?php

namespace App\Filament\Resources\LeagueResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

use App\Models\Team;

class LeagueStandings extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Team::query()
                    ->orderByDesc('points');
            })
            ->columns([

            ]);
    }
}
