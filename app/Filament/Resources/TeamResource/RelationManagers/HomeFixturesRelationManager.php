<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Fixture;

class HomeFixturesRelationManager extends RelationManager
{
    protected static string $relationship = 'homeFixtures';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('homeTeam.name'),
                Tables\Columns\TextColumn::make('awayTeam.name'),
                Tables\Columns\TextColumn::make('homeTeamPoints')
                    ->label('Points')
                    ->getStateUsing(function(Fixture $record){
                    return $record->homeTeamPoints();
                }),
                Tables\Columns\TextColumn::make('result')
                    ->label('Result')
                    ->getStateUsing(function(Fixture $record){
                        return $record->result();
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('With Results')
                ->query(fn (Builder $query) => $query->withResults()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}