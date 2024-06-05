<?php

namespace App\Livewire;

use Livewire\Component;

class TeamSelection extends Component
{

    public $leagues;
    public $selectedTeams = [];
    public $remainingBudget = 125;

    public function mount($leagues)
    {
        $this->leagues = $leagues;
    }

    public function toggleTeam($teamId, $teamPrice)
    {
        if (in_array($teamId, $this->selectedTeams)) {
            $this->selectedTeams = array_diff($this->selectedTeams, [$teamId]);
            $this->remainingBudget += $teamPrice;
        } else {
            if ($this->remainingBudget >= $teamPrice) {
                $this->selectedTeams[] = $teamId;
                $this->remainingBudget -= $teamPrice;
            }
        }
    }

    public function render()
    {
        return view('livewire.team-selection');
    }
}
