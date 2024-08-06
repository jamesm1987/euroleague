<?php

namespace App\Filament\Resources\LeagueResource\Pages;

use App\Filament\Resources\LeagueResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\League;

class ViewLeague extends ViewRecord
{
    protected static string $resource = LeagueResource::class;

    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

}
