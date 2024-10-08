<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeagueResource\Pages;
use App\Filament\Resources\LeagueResource\RelationManagers;
use App\Models\League;
use App\Models\Fixture;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Toggle;
use App\Filament\Tables\Actions\ApiLoadData;
use Filament\Tables\Actions\Action;

use Filament\Notifications\Notification;

use App\Jobs\MakeApiRequest;

use App\Services\ApiFootballService;

use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Columns\IconColumn;

class LeagueResource extends Resource
{
    protected static ?string $model = League::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
       return false;
    }

    public static function form(Form $form): Form
    {

        $leagues = config('services.api-football.leagues');

        return $form
            ->schema([
                Toggle::make('active')
                    ->onColor('success')
                    ->offColor('danger')
            ]);
    }


    public static function table(Table $table): Table
    {  
        return $table
            ->columns([
                TextColumn::make('name'),
                IconColumn::make('active')
                ->boolean()
                

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('fetchTeamsData')
                    ->label('Import Teams')
                    ->visible(function($record) {
                        return $record->teams()->count() === 0;
                    })
                        ->action(function($record){
                            dispatch(new MakeApiRequest(app(ApiFootballService::class), 'teams', [
                                'league' => $record->api_id,
                                'season' => config('services.api-football.season'),
                            ], static::$model))->hidden(true);
    
    
                            Notification::make()
                            ->title('Teams imported successfully')
                            ->send();
                        }),
    
                        Tables\Actions\Action::make('fetchFixturesData')
                        ->label('Import Fixtures')
                        ->visible(function( $record){
                            return $record->fixtures->count() === 0;
                        })
                            ->action(function($record){
                                dispatch(new MakeApiRequest(app(ApiFootballService::class), 'fixtures', [
                                    'league' => $record->api_id,
                                    'season' => config('services.api-football.season'),
                                ], static::$model));
    
    
                                Notification::make()
                                ->title('Fixtures imported successfully')
                                ->send();
                            }), 
                            
                            Tables\Actions\Action::make('fetchResultsData')
                            ->label('Import Results')
                            ->visible(function( $record){
                                return $record->fixtures->count() > 0;
                            })
                            ->action(function($record){
                                dispatch(new MakeApiRequest(app(ApiFootballService::class), 'fixtures', [
                                    'league' => $record->api_id,
                                    'season' => config('services.api-football.season'),
                                ], static::$model));
    
    
                                Notification::make()
                                ->title('Results imported successfully')
                                ->send();
                            }),  
                            
                            Tables\Actions\Action::make('fetchGoalScorersData')
                            ->label('Import Top Goalscorers')
                            ->visible(function( $record){
                                return $record->fixtures->count() > 0;
                            })
                            ->action(function($record){
                                dispatch(new MakeApiRequest(app(ApiFootballService::class), 'players/topscorers', [
                                    'league' => $record->api_id,
                                    'season' => config('services.api-football.season'),
                                ], static::$model));
    
    
                                Notification::make()
                                ->title('Top goalscorers imported successfully')
                                ->send();
                            }),                              
                ]),
                
            ])
            ->headerActions([
                Action::make('calculate points')
                ->action(function () {
                    Fixture::calculatePoints();
                }),

            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [    
            RelationManagers\TeamsRelationManager::class,
            RelationManagers\FixturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeagues::route('/'),
            'view' => Pages\ViewLeague::route('/{record}'),
            'edit' => Pages\EditLeague::route('/{record}/edit'),
        ];
    }
}
