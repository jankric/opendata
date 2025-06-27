<?php

namespace App\Filament\Widgets;

use App\Models\Dataset;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DatasetChart extends ChartWidget
{
    protected static ?string $heading = 'Dataset per Bulan';

    protected function getData(): array
    {
        $data = Dataset::select(
            DB::raw('DATE_TRUNC(\'month\', created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return [
            'datasets' => [
                'label' => 'Datasets',
                'data' => $data->pluck('count')->toArray(),
                'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                'borderColor' => 'rgb(59, 130, 246)',
            ],
            'labels' => $data->map(fn ($item) => 
                \Carbon\Carbon::parse($item->month)->format('M Y')
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}