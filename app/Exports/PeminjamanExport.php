<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Peminjaman::query()->with('ruangan');
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'NIP/NISN',
            'Jabatan/Kelas',
            'Unit Organisasi',
            'No HP',
            'Nama Kegiatan',
            'Tujuan Kegiatan',
            'Ruangan',
            'Jumlah Peserta',
            'Tanggal',
            'Jam Mulai',
            'Jam Selesai',
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
            $peminjaman->nama_kegiatan,
            $peminjaman->tujuan_kegiatan,
            $peminjaman->ruangan->name ?? '-',
            $peminjaman->jumlah_peserta,
            $peminjaman->tanggal,
            $peminjaman->jam_mulai,
            $peminjaman->jam_selesai,
            $peminjaman->status,
        ];
    }
}