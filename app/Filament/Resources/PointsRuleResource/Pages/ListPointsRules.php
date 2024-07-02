<?php

namespace App\Filament\Resources\PointsRuleResource\Pages;

use App\Filament\Resources\PointsRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointsRules extends ListRecords
{
    protected static string $resource = PointsRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
