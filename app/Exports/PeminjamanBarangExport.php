<?php

namespace App\Exports;

use App\Models\PeminjamanBarang;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanBarangExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return PeminjamanBarang::query()->with('barang');
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'NIP/NISN',
            'Jabatan/Kelas',
            'Unit/Organisasi',
            'No HP',
            'Barang',
            'Tanggal',
            'Jam Mulai',
            'Jam Selesai',
            'Kegiatan',
            'Tujuan',
            'Status',
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->code,
            $peminjaman->nama,
            $peminjaman->nip_nisn,
            $peminjaman->jabatan_kelas,
            $peminjaman->unit_kerja_organisasi,
            $peminjaman->no_hp,
            $peminjaman->barang->name ?? '-',
            $peminjaman->tanggal,
            $peminjaman->jam_mulai,
            $peminjaman->jam_selesai,
            $peminjaman->kegiatan,
            $peminjaman->tujuan,
            $peminjaman->status,
        ];
    }
}