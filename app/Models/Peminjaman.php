<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';

    protected $fillable = [
        'code',
        'nama',                        // ganti dari nama_peminjam
        'nip_nisn',
        'jabatan_kelas',
        'unit_kerja_organisasi',
        'no_hp',
        'nama_kegiatan',               // ganti dari keperluan
        'tujuan_kegiatan',
        'ruangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jumlah_peserta',
        'status',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    protected static function booted()
    {
        static::creating(function (Peminjaman $peminjaman) {
            $tanggal = Carbon::parse($peminjaman->tanggal)->format('dmy');
            $ruangan = Ruangan::find($peminjaman->ruangan_id);
            $kodeRuangan = $ruangan?->code ?? 'XXX';

            $baseCode = "PR4B-{$tanggal}-{$kodeRuangan}";

            // Cek apakah kode dasar sudah ada
            $existing = self::where('code', 'like', $baseCode . '%')
                            ->orderBy('code', 'desc')
                            ->first();

            if ($existing) {
                // Ambil suffix angka jika ada
                $lastSuffix = Str::after($existing->code, $baseCode . '-');
                $next = $lastSuffix ? (int)$lastSuffix + 1 : 1;
                $peminjaman->code = $baseCode . '-' . $next;
            } else {
                $peminjaman->code = $baseCode;
            }
        });
    }
}
