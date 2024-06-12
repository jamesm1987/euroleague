<?php

namespace App\Filament\Resources\LeagueResource\Pages;

use App\Filament\Resources\LeagueResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\League;
use App\Filament\Resources\LeagueResource\Widgets\LeagueStandings;

class ViewLeague extends ViewRecord
{
    protected static string $resource = LeagueResource::class;
    protected static string $view = 'filament.resources.league-resource.pages.view-league';

    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

    protected function getWidgets(): array
    {
        return [
            LeagueStandings::class => [
                'leagueId' => $this->record->id,
            ],
        ];
    }

}
