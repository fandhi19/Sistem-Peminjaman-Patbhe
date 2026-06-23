<?php

namespace App\Filament\Resources\PeminjamanBarangs\Schemas;

use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PeminjamanBarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('code')
                    ->dehydrated()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100),

                TextInput::make('nip_nisn')
                    ->label('NIP / NISN')
                    ->maxLength(30),

                TextInput::make('jabatan_kelas')
                    ->label('Jabatan / Kelas')
                    ->maxLength(50),

                TextInput::make('unit_kerja_organisasi')
                    ->label('Unit Kerja / Organisasi')
                    ->maxLength(100),

                TextInput::make('no_hp')
                    ->label('No HP')
                    ->tel()
                    ->maxLength(15),

                TextInput::make('kegiatan')
                    ->label('Kegiatan')
                    ->required()
                    ->maxLength(150),

                Textarea::make('tujuan')
                    ->label('Tujuan')
                    ->rows(3),

                Select::make('barang_id')
                    ->label('Barang')
                    ->relationship('barang', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required()
                    ->displayFormat('d/m/Y'),

                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required()
                    ->withoutSeconds(),

                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->required()
                    ->withoutSeconds()
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            $barangId = $get('barang_id');
                            $tanggal = $get('tanggal');
                            $jamMulai = $get('jam_mulai');

                            if (!$barangId || !$tanggal || !$jamMulai || !$value) {
                                return;
                            }

                            if ($jamMulai >= $value) {
                                $fail('Jam selesai harus lebih besar dari jam mulai.');
                                return;
                            }

                            $exists = \App\Models\PeminjamanBarang::where('barang_id', $barangId)
                                ->where('tanggal', $tanggal)
                                ->where(function ($query) use ($jamMulai, $value) {
                                    $query->whereBetween('jam_mulai', [$jamMulai, $value])
                                        ->orWhereBetween('jam_selesai', [$jamMulai, $value])
                                        ->orWhere(function ($q) use ($jamMulai, $value) {
                                            $q->where('jam_mulai', '<=', $jamMulai)
                                                ->where('jam_selesai', '>=', $value);
                                        });
                                })
                                ->when(
                                    request()->route('record')?->id,
                                    fn ($q, $id) => $q->where('id', '!=', $id)
                                )
                                ->exists();

                            if ($exists) {
                                $fail('Barang ini sudah dipinjam di jam tersebut.');
                            }
                        },
                    ]),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending'      => 'Pending',
                        'Disetujui'    => 'Disetujui',
                        'Ditolak'      => 'Ditolak',
                        'Dikembalikan' => 'Dikembalikan',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
