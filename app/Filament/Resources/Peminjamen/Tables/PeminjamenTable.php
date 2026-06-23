<?php

namespace App\Filament\Resources\Peminjamen\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;             // ← tambahkan
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;           // ← tambahkan
use Filament\Notifications\Notification;        // ← tambahkan
use App\Models\Peminjaman;                      // ← pastikan import model

class PeminjamenTable
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
                    ->toggleable(),

                TextColumn::make('jabatan_kelas')
                    ->label('Jabatan/Kelas')
                    ->toggleable(),

                TextColumn::make('unit_kerja_organisasi')
                    ->label('Unit/Organisasi')
                    ->toggleable(),

                TextColumn::make('no_hp')
                    ->label('No HP')
                    ->toggleable(),

                TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->searchable(),

                TextColumn::make('tujuan_kegiatan')
                    ->label('Tujuan')
                    ->limit(20)->toggleable(),

                TextColumn::make('ruangan.name')
                    ->label('Ruangan')
                    ->sortable(),
                
                TextColumn::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->searchable(),

                TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->time('H:i'),

                TextColumn::make('jam_selesai')
                    ->time('H:i'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending'   => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak'   => 'danger',
                        'Selesai'   => 'gray',
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
                // Action ubah status
                Action::make('ubahStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pending'   => 'Pending',
                                'Disetujui' => 'Disetujui',
                                'Ditolak'   => 'Ditolak',
                                'Selesai'   => 'Selesai',
                            ])
                            ->required(),
                    ])
                    ->action(function (Peminjaman $record, array $data) {
                        $record->update(['status' => $data['status']]);
                        Notification::make()
                            ->title('Status berhasil diubah!')
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->label("Hapus Data"),
                ]),
            ])

            ->headerActions([
                Action::make('exportExcel')
                    ->label('Export Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(route('export.peminjaman-ruangan'))
                    ->openUrlInNewTab(),
            ]);
    }
}