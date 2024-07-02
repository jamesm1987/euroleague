<?php

namespace App\Filament\Resources\PointsRuleResource\Pages;

use App\Filament\Resources\PointsRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPointsRule extends EditRecord
{
    protected static string $resource = PointsRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
