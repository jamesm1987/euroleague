<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointsRuleResource\Pages;
use App\Models\PointsRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// inputs
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

// columns
use Filament\Tables\Columns\TextColumn;

class PointsRuleResource extends Resource
{
    protected static ?string $model = PointsRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('description'),
                TextInput::make('key'),
                Select::make('type')
                    ->options([
                        'result' => 'Result',
                        'score' => 'Score',
                        'goalscorer' => 'Goalscorer',
                        'trophy'    => 'Trophy'
                    ]),
                Select::make('outcome')
                    ->options([
                        'win' => 'Win',
                        'draw' => 'Draw',
                        'defeat' => 'Defeat',
                        '1' => 'First',
                        '2' => 'Second',
                        '3' => 'Third',
                        '4' => 'Fourth',
                    ]),
                Select::make('condition')
                    ->options([
                        'home_team_goals > away_team_goals' => 'Home Team Win',
                        'away_team_goals > home_team_goals' => 'Away Team Win',
                        'home_team_goals < away_team_goals' => 'Home Team Defeat',
                        'away_team_goals < home_team_goals' => 'Away Team Defeat',
                    ]),
                    TextInput::make('threshold'),
                    TextInput::make('value')->label('points'),

                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description'),
                TextColumn::make('value'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPointsRules::route('/'),
            'create' => Pages\CreatePointsRule::route('/create'),
            'edit' => Pages\EditPointsRule::route('/{record}/edit'),
        ];
    }
}
