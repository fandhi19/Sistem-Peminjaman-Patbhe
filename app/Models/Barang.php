<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'code',
        'name',
        'jumlah',
    ];

    public static function generateCode(): string
    {
        $prefix = 'B4B-';
        $last = self::where('code', 'like', $prefix . '%')
                    ->orderBy('code', 'desc')
                    ->first();

        if ($last) {
            $lastNumber = (int) substr($last->code, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}