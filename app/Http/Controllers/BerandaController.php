<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Barang;
use App\Models\PeminjamanBarang;


class BerandaController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::all();
        $barangs = Barang::all();
        return view('index', compact('ruangans', 'barangs'));
    }

    // Tampilkan form pengajuan
    public function showForm()
    {
        $ruangans = Ruangan::all();
        return view('form', compact('ruangans'));
    }

    // Proses penyimpanan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                  => 'required|string|max:100',
            'nip_nisn'             => 'nullable|string|max:30',
            'jabatan_kelas'        => 'nullable|string|max:50',
            'unit_kerja_organisasi'=> 'nullable|string|max:100',
            'no_hp'                => 'nullable|string|max:15',
            'nama_kegiatan'        => 'required|string|max:150',
            'tujuan_kegiatan'      => 'nullable|string|max:500',
            'ruangan_id'           => 'required|exists:ruangans,id',
            'tanggal'              => 'required|date|after_or_equal:today',
            'jam_mulai'            => 'required|date_format:H:i',
            'jam_selesai'          => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_peserta'       => 'nullable|integer|min:1',
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
        ]);

        // Cek bentrok jadwal
        $ruanganId  = $validated['ruangan_id'];
        $tanggal    = $validated['tanggal'];
        $jamMulai   = $validated['jam_mulai'];
        $jamSelesai = $validated['jam_selesai'];

        $exists = Peminjaman::where('ruangan_id', $ruanganId)
                    ->where('tanggal', $tanggal)
                    ->where(function ($query) use ($jamMulai, $jamSelesai) {
                        $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                              ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                              ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                                  $q->where('jam_mulai', '<=', $jamMulai)
                                    ->where('jam_selesai', '>=', $jamSelesai);
                              });
                    })
                    ->exists();

        if ($exists) {
            return back()->withErrors(['jam_selesai' => 'Jadwal ruangan ini sudah terpakai di jam tersebut.'])->withInput();
        }

        // Buat record baru (kode otomatis dari model event `creating`)
        $peminjaman = Peminjaman::create([
            'nama'                  => $validated['nama'],
            'nip_nisn'              => $validated['nip_nisn'] ?? null,
            'jabatan_kelas'         => $validated['jabatan_kelas'] ?? null,
            'unit_kerja_organisasi' => $validated['unit_kerja_organisasi'] ?? null,
            'no_hp'                 => $validated['no_hp'] ?? null,
            'nama_kegiatan'         => $validated['nama_kegiatan'],
            'tujuan_kegiatan'       => $validated['tujuan_kegiatan'] ?? null,
            'ruangan_id'            => $validated['ruangan_id'],
            'tanggal'               => $validated['tanggal'],
            'jam_mulai'             => $validated['jam_mulai'],
            'jam_selesai'           => $validated['jam_selesai'],
            'jumlah_peserta'        => $validated['jumlah_peserta'] ?? null,
            'status'                => 'Pending',
        ]);

        // Kirim notifikasi ke admin
        // Generate PDF dari view
        $pdf = Pdf::loadView('surat_permohonan', compact('peminjaman'));
        // Atur ukuran kertas (opsional)
        $pdf->setPaper('a4');

        // Kirim email dengan lampiran PDF
        Mail::raw(
            "Halo Admin Sarpras SMAN 4 Yogyakarta,\n\n" .
            "Ada pengajuan peminjaman ruangan baru:\n\n" .
            "🔹 Kode Peminjaman : {$peminjaman->code}\n" .
            "🔹 Nama            : {$peminjaman->nama}\n" .
            "🔹 Ruangan         : {$peminjaman->ruangan->name}\n" .
            "🔹 Tanggal         : " . Carbon::parse($peminjaman->tanggal)->format('d/m/Y') . "\n" .
            "🔹 Jam             : {$peminjaman->jam_mulai} - {$peminjaman->jam_selesai}\n" .
            "🔹 Kegiatan        : {$peminjaman->nama_kegiatan}\n\n" .
            "Silakan cek panel admin untuk menindaklanjuti.\n" .
            "Surat permohonan terlampir dalam PDF.\n\n" .
            "SISTEM PEMINJAMAN PATBHE - SMAN 4 Yogyakarta",
            function ($message) use ($peminjaman, $pdf) {
                $message->to('cekerckc@gmail.com', 'Admin SARPRAS PATBHE')
                        ->subject('🔔 Pengajuan Peminjaman Baru - ' . now()->format('d/m/Y H:i'))
                        ->attachData($pdf->output(), 'Surat-Permohonan-' . $peminjaman->code . '.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            }
        );

        return redirect()->route('form.peminjaman')
            ->with('success', true)
            ->with('kode_peminjaman', $peminjaman->code);
    }

    // Tampilkan form tracking (kosong)
    public function showTracking()
    {
        return view('tracking', ['peminjaman' => null, 'kode' => null]);
    }

    // Proses input kode, redirect ke halaman hasil
    public function processTracking(Request $request)
    {
        $request->validate(['kode' => 'required|string|max:20']);
        return redirect()->route('tracking.result', ['kode' => $request->kode]);
    }

    // Tampilkan hasil tracking berdasarkan kode
    public function showTrackingResult($kode)
    {
        $peminjaman = Peminjaman::where('code', $kode)->with('ruangan')->first();

        if (!$peminjaman) {
            return redirect()->route('tracking.form')
                ->with('error', 'Kode peminjaman tidak ditemukan. Pastikan kode yang Anda masukkan benar.');
        }

        return view('tracking', ['peminjaman' => $peminjaman, 'kode' => $kode]);
    }

    public function unduhSurat($kode)
    {
        $peminjaman = Peminjaman::where('code', $kode)->with('ruangan')->firstOrFail();

        $pdf = Pdf::loadView('surat_permohonan', compact('peminjaman'));
        $pdf->setPaper('a4');

        return $pdf->download('Surat-Permohonan-' . $peminjaman->code . '.pdf');
    }

    //FORM PEMINJAMAN BARANG
    // Tampilkan form peminjaman barang
    public function showFormBarang()
    {
        $barangs = Barang::all();
        return view('form-barang', compact('barangs'));
    }

    // Proses penyimpanan peminjaman barang
    public function storeBarang(Request $request)
    {
        $validated = $request->validate([
            'nama'                  => 'required|string|max:100',
            'nip_nisn'             => 'nullable|string|max:30',
            'jabatan_kelas'        => 'nullable|string|max:50',
            'unit_kerja_organisasi'=> 'nullable|string|max:100',
            'no_hp'                => 'nullable|string|max:15',
            'kegiatan'             => 'required|string|max:150',
            'tujuan'               => 'nullable|string|max:500',
            'barang_id'            => 'required|exists:barangs,id',
            'tanggal'              => 'required|date|after_or_equal:today',
            'jam_mulai'            => 'required|date_format:H:i',
            'jam_selesai'          => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
        ]);

        // Cek bentrok jadwal
        $barangId  = $validated['barang_id'];
        $tanggal   = $validated['tanggal'];
        $jamMulai  = $validated['jam_mulai'];
        $jamSelesai= $validated['jam_selesai'];

        $exists = PeminjamanBarang::where('barang_id', $barangId)
                    ->where('tanggal', $tanggal)
                    ->where(function ($query) use ($jamMulai, $jamSelesai) {
                        $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                            ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                            ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                                $q->where('jam_mulai', '<=', $jamMulai)
                                    ->where('jam_selesai', '>=', $jamSelesai);
                            });
                    })
                    ->exists();

        if ($exists) {
            return back()->withErrors(['jam_selesai' => 'Barang ini sudah dipinjam di jam tersebut.'])->withInput();
        }

        // Buat record baru (kode otomatis dari model event)
        $peminjaman = PeminjamanBarang::create([
            'nama'                  => $validated['nama'],
            'nip_nisn'              => $validated['nip_nisn'] ?? null,
            'jabatan_kelas'         => $validated['jabatan_kelas'] ?? null,
            'unit_kerja_organisasi' => $validated['unit_kerja_organisasi'] ?? null,
            'no_hp'                 => $validated['no_hp'] ?? null,
            'kegiatan'              => $validated['kegiatan'],
            'tujuan'                => $validated['tujuan'] ?? null,
            'barang_id'             => $validated['barang_id'],
            'tanggal'               => $validated['tanggal'],
            'jam_mulai'             => $validated['jam_mulai'],
            'jam_selesai'           => $validated['jam_selesai'],
            'status'                => 'Pending',
        ]);

        // Kirim notifikasi ke admin (opsional, bisa ditambahkan nanti)
        // Generate PDF surat permohonan barang
        $pdf = Pdf::loadView('surat_permohonan_barang', compact('peminjaman'));
        $pdf->setPaper('a4');

        // Kirim notifikasi ke admin
        Mail::raw(
            "Halo Admin Sarpras SMAN 4 Yogyakarta,\n\n" .
            "Ada pengajuan peminjaman barang baru:\n\n" .
            "🔹 Kode Peminjaman : {$peminjaman->code}\n" .
            "🔹 Nama            : {$peminjaman->nama}\n" .
            "🔹 Barang          : {$peminjaman->barang->name} ({$peminjaman->barang->code})\n" .
            "🔹 Tanggal         : " . Carbon::parse($peminjaman->tanggal)->format('d/m/Y') . "\n" .
            "🔹 Jam             : {$peminjaman->jam_mulai} - {$peminjaman->jam_selesai}\n" .
            "🔹 Kegiatan        : {$peminjaman->kegiatan}\n\n" .
            "Silakan cek panel admin untuk menindaklanjuti.\n" .
            "Surat permohonan terlampir dalam PDF.\n\n" .
            "SIPERU PATBHE - SMAN 4 Yogyakarta",
            function ($message) use ($peminjaman, $pdf) {
                $message->to('cekerckc@gmail.com', 'Admin SARPRAS PATBHE')
                        ->subject('🔔 Pengajuan Peminjaman Barang Baru - ' . now()->format('d/m/Y H:i'))
                        ->attachData($pdf->output(), 'Surat-Permohonan-Barang-' . $peminjaman->code . '.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            }
        );

        return redirect()->route('form.barang')
            ->with('success', true)
            ->with('kode_peminjaman', $peminjaman->code);
    }

    // --- TRACKING BARANG ---
    public function showTrackingBarang()
    {
        return view('tracking-barang', ['peminjaman' => null, 'kode' => null]);
    }

    public function processTrackingBarang(Request $request)
    {
        $request->validate(['kode' => 'required|string|max:20']);
        return redirect()->route('tracking.barang.result', ['kode' => $request->kode]);
    }

    public function showTrackingResultBarang($kode)
    {
        $peminjaman = PeminjamanBarang::where('code', $kode)->with('barang')->first();

        if (!$peminjaman) {
            return redirect()->route('tracking.barang.form')
                ->with('error', 'Kode peminjaman tidak ditemukan. Pastikan kode yang Anda masukkan benar.');
        }

        return view('tracking-barang', ['peminjaman' => $peminjaman, 'kode' => $kode]);
    }

    // --- UNDUH SURAT BARANG ---
    public function unduhSuratBarang($kode)
    {
        $peminjaman = PeminjamanBarang::where('code', $kode)->with('barang')->firstOrFail();

        $pdf = Pdf::loadView('surat_permohonan_barang', compact('peminjaman'));
        $pdf->setPaper('a4');

        return $pdf->download('Surat-Permohonan-Barang-' . $peminjaman->code . '.pdf');
    }
}