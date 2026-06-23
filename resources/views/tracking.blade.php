<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Peminjaman Ruangan - SIPERU PATBHE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/Logo Patbhe.png') }}" type="image/x-icon">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <a href="/" class="flex items-center space-x-3">
                <img src="{{ asset('images/Logo Patbhe.png') }}" alt="Logo SMAN 4 Yogyakarta" class="w-10 h-10">
                <div>
                    <span class="font-bold text-lg text-gray-900 block leading-none">SIPERU PATBHE</span>
                    <span class="text-xs text-gray-500">SMAN 4 Yogyakarta</span>
                </div>
            </a>
            <a href="/" class="text-green-700 hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda</a>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main class="flex-grow py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2 text-center">Cek Status Peminjaman Ruangan</h1>
            <p class="text-center text-gray-500 mb-8">Masukkan kode peminjaman yang Anda dapatkan saat pengajuan.</p>

            {{-- Form Input Kode --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-8">
                <form action="{{ route('tracking.process') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input 
                        type="text" 
                        name="kode" 
                        value="{{ $kode ?? old('kode') }}"
                        placeholder="Masukkan kode peminjaman, contoh: PR4B-220626-R4B001"
                        class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-3 border"
                        required
                    >
                    <button type="submit" 
                            class="bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-3 rounded-lg shadow transition hover:-translate-y-0.5">
                        <i class="fa-solid fa-search mr-2"></i>Cek Status
                    </button>
                </form>
                @error('kode')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                @if(session('error'))
                    <p class="text-red-500 text-sm mt-2">{{ session('error') }}</p>
                @endif
            </div>

            {{-- Hasil Pencarian --}}
            @if(isset($peminjaman) && $peminjaman)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Detail Peminjaman Ruangan</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            @if($peminjaman->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($peminjaman->status == 'disetujui') bg-green-100 text-green-700
                            @elseif($peminjaman->status == 'ditolak') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500 font-medium">Kode Peminjaman</dt>
                            <dd class="text-gray-900 font-mono font-bold">{{ $peminjaman->code }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Nama</dt>
                            <dd class="text-gray-900">{{ $peminjaman->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">NIP/NISN</dt>
                            <dd class="text-gray-900">{{ $peminjaman->nip_nisn ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Jabatan/Kelas</dt>
                            <dd class="text-gray-900">{{ $peminjaman->jabatan_kelas ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Unit/Organisasi</dt>
                            <dd class="text-gray-900">{{ $peminjaman->unit_kerja_organisasi ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">No HP</dt>
                            <dd class="text-gray-900">{{ $peminjaman->no_hp ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Ruangan</dt>
                            <dd class="text-gray-900">{{ $peminjaman->ruangan->name ?? '-' }} ({{ $peminjaman->ruangan->code ?? '' }})</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Tanggal</dt>
                            <dd class="text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Jam</dt>
                            <dd class="text-gray-900">{{ $peminjaman->jam_mulai }} - {{ $peminjaman->jam_selesai }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Kegiatan</dt>
                            <dd class="text-gray-900">{{ $peminjaman->nama_kegiatan }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 font-medium">Jumlah Peserta</dt>
                            <dd class="text-gray-900">{{ $peminjaman->jumlah_peserta ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500 font-medium">Tujuan Kegiatan</dt>
                            <dd class="text-gray-900">{{ $peminjaman->tujuan_kegiatan ?? '-' }}</dd>
                        </div>
                    </dl>

                    {{-- Tombol Unduh Surat --}}
                    <div class="mt-6 text-center">
                        <a href="{{ route('unduh.surat', ['kode' => $peminjaman->code]) }}" 
                           class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-3 rounded-lg transition">
                            <i class="fa-solid fa-download mr-2"></i> Unduh Surat Permohonan (PDF)
                        </a>
                    </div>

                    {{-- Notifikasi Status --}}
                    @if($peminjaman->status == 'Disetujui')
                        <div class="mt-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                            <i class="fa-solid fa-circle-check mr-2"></i> Peminjaman Anda telah <strong>Disetujui</strong>. Silakan gunakan ruangan sesuai jadwal.
                        </div>
                    @elseif($peminjaman->status == 'Ditolak')
                        <div class="mt-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                            <i class="fa-solid fa-circle-xmark mr-2"></i> Maaf, pengajuan Anda <strong>Ditolak</strong>. Hubungi admin untuk informasi lebih lanjut.
                        </div>
                    @elseif($peminjaman->status == 'Selesai')
                        <div class="mt-6 bg-gray-50 border border-gray-200 text-gray-800 rounded-lg p-4">
                            <i class="fa-solid fa-flag-checkered mr-2"></i> Peminjaman ini telah <strong>Selesai</strong>.
                        </div>
                    @else
                        <div class="mt-6 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4">
                            <i class="fa-solid fa-hourglass-half mr-2"></i> Pengajuan Anda masih <strong>Pending</strong>. Admin akan segera meninjaunya.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-400 py-6 text-center text-sm">
        © 2026 Tim IT SMAN 4 Yogyakarta. All Rights Reserved.
    </footer>

</body>
</html>