<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\TeamResource\Widgets\TeamStats;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

 
    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }


    protected function getHeaderWidgets(): array
    {

        return [
            TeamStats::class,
        ];
    }
}
