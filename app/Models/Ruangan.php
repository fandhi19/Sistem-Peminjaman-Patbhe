<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';

    protected $fillable = [
        'code',
        'name',
        'capacity',
        'facilities',
    ];

    public static function generateCode(): string
    {
        $prefix = 'R4B-';
        
        // Ambil kode terakhir yang ada di database
        $last = self::where('code', 'like', $prefix . '%')
                    ->orderBy('code', 'desc')
                    ->first();

        if ($last) {
            // Ambil angka belakang, misal 'R4B-005' -> 5
            $lastNumber = (int) substr($last->code, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format jadi tiga digit, contoh: R4B-001
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}


