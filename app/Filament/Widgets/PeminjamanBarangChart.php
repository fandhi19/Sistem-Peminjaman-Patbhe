<?php

namespace App\Filament\Widgets;

use App\Models\PeminjamanBarang;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PeminjamanBarangChart extends ChartWidget
{
    protected static ?int $sort =  3;
    public function getHeading(): string
    {
        return 'Peminjaman Barang per Bulan';
    }

    protected function getData(): array
    {
        $data = PeminjamanBarang::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
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
        return 'bar';
    }
}