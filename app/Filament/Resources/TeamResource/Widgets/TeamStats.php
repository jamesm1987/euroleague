<?php

namespace App\Filament\Resources\TeamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Model;

class TeamStats extends BaseWidget
{

    public ?Model $record = null;

    protected function getCards(): array
    {

        $winLossDraw = "{$this->record->matches_won} / {$this->record->matches_lost} / {$this->record->matches_drawn}";

        return [

            Card::make('Total Points', $this->record->calculateTotalPoints()),
            Card::make('Match Points', $this->record->calculateMatchPoints()),
            Card::make('Score Points', $this->record->calculateScorePoints()),
            Card::make('Played', $this->record->fixtures()->count()),
            
            Card::make('W / L / D', $winLossDraw),
            Card::make('GD', $this->record->getGoalDifference()),

        ];
    }
}
