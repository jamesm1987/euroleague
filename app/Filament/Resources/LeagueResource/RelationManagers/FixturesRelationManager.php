<?php

namespace App\Filament\Resources\LeagueResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;


class FixturesRelationManager extends RelationManager
{
    protected static string $relationship = 'fixtures';


    public function form(Form $form): Form
    {
        return $form->schema([
            // Define your form schema here
        ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('league_id')
            ->columns([
                Tables\Columns\TextColumn::make('homeTeam.name'),
                Tables\Columns\TextColumn::make('awayTeam.name'),
            ])
            ->filters([
                // Define your filters here
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}