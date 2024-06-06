<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\TeamResource\RelationManagers\HomeFixturesRelationManager;
use App\Filament\Resources\TeamResource\RelationManagers\AwayFixturesRelationManager;

use App\Filament\Resources\TeamResource\Widgets\TeamStats;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

 
    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            // ...
        ]);
    }

    protected function getHeaderWidgets(): array
    {

        return [
            TeamStats::class,
        ];
    }
}
