<?php

namespace App\Filament\Resources\Ruangans\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class RuanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('code')
                ->default(fn () => \App\Models\Ruangan::generateCode())
                ->required()
                ->unique(ignoreRecord: true), 

                TextInput::make('name')
                    ->label('Nama Ruangan')
                    ->required()
                    ->maxLength(100),

                TextInput::make('capacity')
                    ->label('Kapasitas')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                Textarea::make('facilities')
                    ->label('Fasilitas')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
