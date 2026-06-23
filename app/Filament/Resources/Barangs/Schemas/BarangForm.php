<?php

namespace App\Filament\Resources\Barangs\Schemas;

use App\Models\Barang;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;

class BarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('code')
                    ->default(fn () => Barang::generateCode())
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('name')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(100),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(1),
            ]);
    }
}
