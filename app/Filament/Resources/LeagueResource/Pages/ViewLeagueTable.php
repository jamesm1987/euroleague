<?php

namespace App\Filament\Resources\LeagueResource\Pages;

use App\Filament\Resources\LeagueResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ViewLeagueTable extends ViewRecord
{
    protected static string $resource = LeagueResource::class;


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
        ]);
    }
}
