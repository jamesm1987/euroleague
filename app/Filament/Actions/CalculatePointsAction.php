<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use App\Models\Fixture;
use App\Models\FixturePoint;
use Filament\Actions\Concerns\Asynchronous;
use Filament\Actions\Concerns\WithMiddleware;
use Filament\Actions\Concerns\WithRedirects;


class CalculatePointsAction extends Action
{
    use Asynchronous, WithRedirects, WithMiddleware;

    protected $fixturePoints = [];

    public function handle()
    {

        $this->redirectSuccess(__('Points calculated successfully.'));
    }
}