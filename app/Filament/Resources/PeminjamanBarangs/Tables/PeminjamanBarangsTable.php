<?php

namespace App\Filament\Resources\PeminjamanBarangs\Tables;

use App\Models\PeminjamanBarang;
use Filament\Actions\Action; 
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamanBarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Peminjam')
                    ->searchable(),

                TextColumn::make('nip_nisn')
                    ->label('NIP/NISN')
                    ->searchable(),

                TextColumn::make('jabatan_kelas')
                    ->label('Jabatan/Kelas')
                    ->searchable(),

                TextColumn::make('unit_kerja_organisasi')
                    ->label('Unit Kerja/Organisasi')
                    ->searchable(),

                TextColumn::make('barang.name')
                    ->label('Barang')
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->time('H:i'),

                TextColumn::make('jam_selesai')
                    ->time('H:i'),

                TextColumn::make('kegiatan')
                    ->label('Kegiatan'),
                
                TextColumn::make('tujuan')
                    ->label('Tujuan')
                    ->limit(20)->toggleable(),

                TextColumn::make('no_hp')
                    ->label('No Hp'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending'      => 'warning',
                        'Disetujui'    => 'success',
                        'Ditolak'      => 'danger',
                        'Dikembalikan' => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('ubahStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pending'      => 'Pending',
                                'Disetujui'    => 'Disetujui',
                                'Ditolak'      => 'Ditolak',
                                'Dikembalikan' => 'Dikembalikan',
                            ])
                            ->required(),
                    ])
                    ->action(function (PeminjamanBarang $record, array $data) {
                        $record->update(['status' => $data['status']]);
                        Notification::make()
                            ->title('Status berhasil diubah!')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make()
                ->label("Hapus"),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->headerActions([
                Action::make('exportExcel')
                    ->label('Export Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(route('export.peminjaman-barang'))
                    ->openUrlInNewTab(),
            ]);
    }
}
