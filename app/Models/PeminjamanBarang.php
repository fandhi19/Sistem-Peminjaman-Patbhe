<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeminjamanBarang extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_barangs';

    protected $fillable = [
        'code',
        'nama',
        'nip_nisn',
        'jabatan_kelas',
        'unit_kerja_organisasi',
        'no_hp',
        'kegiatan',
        'tujuan',
        'barang_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    protected static function booted()
    {
        static::creating(function (PeminjamanBarang $peminjaman) {
            $tanggal = Carbon::parse($peminjaman->tanggal)->format('dmy');
            $barang = Barang::find($peminjaman->barang_id);
            $kodeBarang = $barang?->code ?? 'XXX';

            $baseCode = "PB4B-{$tanggal}-{$kodeBarang}";

            // Cek kode dasar sudah ada, jika ya tambahkan suffix angka
            $existing = self::where('code', 'like', $baseCode . '%')
                            ->orderBy('code', 'desc')
                            ->first();

            if ($existing) {
                $lastSuffix = \Illuminate\Support\Str::after($existing->code, $baseCode . '-');
                $next = $lastSuffix ? (int)$lastSuffix + 1 : 1;
                $peminjaman->code = $baseCode . '-' . $next;
            } else {
                $peminjaman->code = $baseCode;
            }
        });
    }
}