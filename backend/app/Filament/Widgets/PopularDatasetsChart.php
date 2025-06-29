<?php

namespace App\Filament\Widgets;

use App\Models\Dataset;
use Filament\Widgets\ChartWidget;

class PopularDatasetsChart extends ChartWidget
{
    protected static ?string $heading = 'Dataset Terpopuler (Top 10)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $datasets = Dataset::withCount('downloads')
            ->orderBy('downloads_count', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Downloads',
                    'data' => $datasets->pluck('downloads_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(245, 101, 101, 0.8)',
                        'rgba(248, 113, 113, 0.8)',
                        'rgba(252, 165, 165, 0.8)',
                        'rgba(254, 202, 202, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(96, 165, 250, 0.8)',
                        'rgba(147, 197, 253, 0.8)',
                        'rgba(191, 219, 254, 0.8)',
                        'rgba(219, 234, 254, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(239, 68, 68, 1)',
                        'rgba(245, 101, 101, 1)',
                        'rgba(248, 113, 113, 1)',
                        'rgba(252, 165, 165, 1)',
                        'rgba(254, 202, 202, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(96, 165, 250, 1)',
                        'rgba(147, 197, 253, 1)',
                        'rgba(191, 219, 254, 1)',
                        'rgba(219, 234, 254, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $datasets->map(function ($dataset) {
                return strlen($dataset->title) > 20 
                    ? substr($dataset->title, 0, 20) . '...' 
                    : $dataset->title;
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}