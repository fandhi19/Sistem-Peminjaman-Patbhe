<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\PeminjamanBarang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PeminjamanBarangStatsWidget extends BaseWidget
{
    protected function getHeading(): ?string
    {
        return '📦 Statistik Peminjaman Barang';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Barang', Barang::count())
                ->description('Barang tersedia')
                ->descriptionIcon('heroicon-o-cube')
                ->color('success'),

            Stat::make('Total Peminjaman', PeminjamanBarang::count())
                ->description('Semua pengajuan')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('primary'),

            Stat::make(
                'Pending',
                PeminjamanBarang::where('status', 'Pending')->count()
            )
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make(
                'Disetujui',
                PeminjamanBarang::where('status', 'Disetujui')->count()
            )
                ->description('Sedang dipinjam')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(
                'Ditolak',
                PeminjamanBarang::where('status', 'Ditolak')->count()
            )
                ->description('Pengajuan ditolak')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make(
                'Dikembalikan',
                PeminjamanBarang::where('status', 'Dikembalikan')->count()
            )
                ->description('Barang telah kembali')
                ->descriptionIcon('heroicon-o-arrow-uturn-left')
                ->color('gray'),
        ];
    }
}