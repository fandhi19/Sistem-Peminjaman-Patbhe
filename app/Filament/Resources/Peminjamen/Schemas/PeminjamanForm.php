<?php

namespace App\Filament\Resources\Peminjamen\Schemas;

use App\Models\Peminjaman;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PeminjamanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('code')
                    ->dehydrated()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama Peminjam')
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
                
                TextInput::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->required()
                    ->maxLength(150),

                Textarea::make('tujuan_kegiatan')
                    ->label('Tujuan Kegiatan')
                    ->rows(3),

                Select::make('ruangan_id')
                    ->label('Ruangan')
                    ->relationship('ruangan', 'name')
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
                    ->after('jam_mulai')
                    ->rules([
                        function ($get, $record) {
                            return function (
                                string $attribute,
                                $value,
                                Closure $fail
                            ) use ($get, $record) {

                                $ruanganId = $get('ruangan_id');
                                $tanggal = $get('tanggal');
                                $jamMulai = $get('jam_mulai');
                                $jamSelesai = $value;

                                if (
                                    empty($ruanganId) ||
                                    empty($tanggal) ||
                                    empty($jamMulai) ||
                                    empty($jamSelesai)
                                ) {
                                    return;
                                }

                                $query = Peminjaman::query()
                                    ->where('ruangan_id', $ruanganId)
                                    ->whereDate('tanggal', $tanggal)
                                    ->whereIn('status', [
                                        'Pending',
                                        'Disetujui',
                                    ])
                                    ->where(function ($q) use ($jamMulai, $jamSelesai) {
                                        $q->where('jam_mulai', '<', $jamSelesai)
                                            ->where('jam_selesai', '>', $jamMulai);
                                    });

                                // Abaikan record yang sedang diedit
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }

                                if ($query->exists()) {
                                    $fail(
                                        'Ruangan sudah digunakan pada tanggal dan jam tersebut.'
                                    );
                                }
                            };
                        },
                    ]),
                
                TextInput::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->numeric()
                    ->minValue(1),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                        'Selesai' => 'Selesai',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}