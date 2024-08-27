<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Filament\Resources\TeamResource\RelationManager;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action;
use App\Filament\Imports\TeamPriceImporter;
use Filament\Tables\Actions\ImportAction;


use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

use App\Models\Fixture;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('preferred_name'),
                TextInput::make('price'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('price')->formatStateUsing(fn($state) => number_format($state, 0))
                ->prefix('£')    
                 ->suffix('m')->sortable(),
                TextColumn::make('points')
                ->getStateUsing(fn (Team $record) => $record->calculateTotalPoints())
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->select('teams.*')
                              ->leftJoin('fixture_point', 'teams.id', '=', 'fixture_point.team_id')
                              ->leftJoin('points_rules', 'fixture_point.points_rule_id', '=', 'points_rules.id')
                              ->groupBy('teams.id')
                              ->orderByRaw('COALESCE(SUM(points_rules.value), 0) ' . $direction);
                    }),
                    TextColumn::make('score_points')->getStateUsing(fn (Team $record) => $record->calculateScorePoints())
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->select('teams.*')
                              ->leftJoin('fixture_point', 'teams.id', '=', 'fixture_point.team_id')
                              ->leftJoin('points_rules', 'fixture_point.points_rule_id', '=', 'points_rules.id')
                              ->groupBy('teams.id')
                              ->orderByRaw('COALESCE(SUM(points_rules.value), 0) ' . $direction);
                    }),
            ])
            ->filters([
                SelectFilter::make('league')
                    ->relationship('league', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-o-pencil')
                ->form([
                    // Define the fields you want in the modal here
                    TextInput::make('preferred_name'),
                    TextInput::make('price')
                        ->required()
                ])
                ->modalHeading('Edit Team')
                ->modalButton('Save')
                ->action(function ($record, array $data) {
                    $record->update($data);
                }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                ->importer(TeamPriceImporter::class)
                ->label('Import Prices')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\HomeFixturesRelationManager::class,
            RelationManagers\AwayFixturesRelationManager::class
        ];
    }
       

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'view' => Pages\ViewTeam::route('/{record}'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
