<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                          // PB4B-{tgl}-{kode_barang}
            $table->string('nama');
            $table->string('nip_nisn')->nullable();
            $table->string('jabatan_kelas')->nullable();
            $table->string('unit_kerja_organisasi')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('kegiatan');
            $table->text('tujuan')->nullable();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('status')->default('Pending');  // pending, disetujui, ditolak, dikembalikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barangs');
    }
};
