<?php

namespace App\Filament\Widgets;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PeminjamanStatsWidget extends BaseWidget
{

    protected function getHeading(): ?string
    {
        return '🏢 Statistik Peminjaman Ruangan';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Ruangan', Ruangan::count())
                ->description('Ruangan tersedia')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('success'),

            Stat::make('Total Peminjaman', Peminjaman::count())
                ->description('Semua pengajuan')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary'),

            Stat::make('Pending', Peminjaman::where('status', 'pending')->count())
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Disetujui', Peminjaman::where('status', 'disetujui')->count())
                ->description('Peminjaman aktif')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Ditolak', Peminjaman::where('status', 'ditolak')->count())
                ->description('Ditolak')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}