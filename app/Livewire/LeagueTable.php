<?php

namespace App\Livewire;
 
use App\Models\League;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
 
class LeagueTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public League $league;

    public function mount(League $league)
    {
        $this->league = $league;


    }

    
    public function table(Table $table): Table
    {
        
        return $table
            ->query(League::leagueTable())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('played')->label('Played'),
                TextColumn::make('matches_won')->label('Won'),
                TextColumn::make('matches_drawn')->label('Draw'),
                TextColumn::make('matches_lost')->label('Lost'),
                TextColumn::make('goal_difference')->label('Goal Difference'),
                TextColumn::make('fixture_points')->label('Points'),
                
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    
    public function render(): View
    {
        return view('livewire.league-table');
    }
}