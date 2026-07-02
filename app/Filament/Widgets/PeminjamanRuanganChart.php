<?php

namespace App\Filament\Widgets;

use App\Models\Peminjaman;
use Filament\Widgets\ChartWidget;

class PeminjamanRuanganChart extends ChartWidget
{
    protected static ?int $sort =  4;
    public function getHeading(): ?string
    {
        return 'Peminjaman Ruangan per Bulan';
    }

    protected function getData(): array
    {
        $data = Peminjaman::selectRaw(
                'MONTH(created_at) as bulan, COUNT(*) as total'
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = [];
        $values = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 1));
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peminjaman',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // bisa juga 'bar'
    }
}