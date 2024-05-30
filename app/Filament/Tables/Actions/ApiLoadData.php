<?php

namespace App\Filament\Tables\Actions;

use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class ApiLoadData extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Approve User')
            ->action(function ($record) {
                // Perform your custom logic here
                $record->approve();
                Notification::make()
                    ->title('User Approved')
                    ->success()
                    ->send();
            });
    }
}