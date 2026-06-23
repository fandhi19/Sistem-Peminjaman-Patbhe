<?php

namespace App\Filament\Resources\Ruangans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class RuangansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                ->label('Kode')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->label('Nama Ruangan')
                ->searchable()
                ->sortable(),
            TextColumn::make('capacity')
                ->label('Kapasitas')
                ->sortable(),
            TextColumn::make('facilities')
                ->label('Fasilitas')
                ->limit(30)
                ->toggleable(),
            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                ->label("Hapus"),
                ViewAction::make()
                ->label("Lihat"),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->label("Hapus"),
                ]),
            ]);
    }
}
