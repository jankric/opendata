<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DatasetResource;
use App\Models\Dataset;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDatasets extends BaseWidget
{
    protected static ?string $heading = 'Dataset Terbaru';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Dataset::query()
                    ->with(['category', 'organization', 'creator'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisasi'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'review',
                        'success' => 'published',
                        'danger' => 'archived',
                    ]),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Dataset $record): string => DatasetResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}