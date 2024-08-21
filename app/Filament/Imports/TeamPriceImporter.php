<?php

namespace App\Filament\Imports;

use App\Models\Team;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TeamPriceImporter extends Importer
{
    protected static ?string $model = Team::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('price')
        ];

        
    }

    public function resolveRecord(): ?Team
    {
        return Team::where(function ($query) {
            $query->where('name', $this->data['name'])
                  ->orWhere('preferred_name', $this->data['name']);
        })->first();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your team price import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
