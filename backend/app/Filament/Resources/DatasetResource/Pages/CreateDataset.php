<?php

namespace App\Filament\Resources\DatasetResource\Pages;

use App\Filament\Resources\DatasetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDataset extends CreateRecord
{
    protected static string $resource = DatasetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}