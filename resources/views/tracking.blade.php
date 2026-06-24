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
<body class="bg-[#f8fafc] text-gray-800 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-[#0f213d] shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <a href="/" class="flex items-center space-x-3">
                <img src="{{ asset('images/Logo Patbhe.png') }}" alt="Logo SMAN 4 Yogyakarta" class="w-10 h-10">
                <div>
                    <span class="font-bold text-lg text-white block leading-none">SIPERU PATBHE</span>
                    <span class="text-xs text-[#d1d5db]">SMAN 4 Yogyakarta</span>
                </div>
            </a>
            <a href="/" class="text-[#eab308] hover:text-[#facc15] transition font-medium">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main class="flex-grow py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-extrabold text-[#142b52] mb-2 text-center">Cek Status Peminjaman Ruangan</h1>
            <p class="text-center text-[#555555] mb-8">Masukkan kode peminjaman yang Anda dapatkan saat pengajuan.</p>

            {{-- Form Input Kode --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#d1d5db] p-6 sm:p-8 mb-8">
                <form action="{{ route('tracking.process') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input 
                        type="text" 
                        name="kode" 
                        value="{{ $kode ?? old('kode') }}"
                        placeholder="Masukkan kode peminjaman, contoh: PR4B-220626-R4B001"
                        class="flex-1 rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] p-3 border"
                        required
                    >
                    <button type="submit" 
                            class="bg-[#0f213d] hover:bg-[#142b52] text-white font-bold px-6 py-3 rounded-lg shadow transition hover:-translate-y-0.5 border border-[#eab308]">
                        <i class="fa-solid fa-search mr-2 text-[#eab308]"></i>Cek Status
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
                <div class="bg-white rounded-2xl shadow-sm border border-[#d1d5db] p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-[#142b52]">Detail Peminjaman Ruangan</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            @if($peminjaman->status == 'pending') bg-yellow-100 text-yellow-700 border border-yellow-300
                            @elseif($peminjaman->status == 'disetujui') bg-green-100 text-green-700 border border-green-300
                            @elseif($peminjaman->status == 'ditolak') bg-red-100 text-red-700 border border-red-300
                            @else bg-gray-100 text-gray-700 border border-gray-300
                            @endif">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-[#555555] font-medium">Kode Peminjaman</dt>
                            <dd class="text-[#333333] font-mono font-bold">{{ $peminjaman->code }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Nama</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">NIP/NISN</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->nip_nisn ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Jabatan/Kelas</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->jabatan_kelas ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Unit/Organisasi</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->unit_kerja_organisasi ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">No HP</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->no_hp ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Ruangan</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->ruangan->name ?? '-' }} ({{ $peminjaman->ruangan->code ?? '' }})</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Tanggal</dt>
                            <dd class="text-[#333333]">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Jam</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->jam_mulai }} - {{ $peminjaman->jam_selesai }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Kegiatan</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->nama_kegiatan }}</dd>
                        </div>
                        <div>
                            <dt class="text-[#555555] font-medium">Jumlah Peserta</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->jumlah_peserta ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-[#555555] font-medium">Tujuan Kegiatan</dt>
                            <dd class="text-[#333333]">{{ $peminjaman->tujuan_kegiatan ?? '-' }}</dd>
                        </div>
                    </dl>

                    {{-- Tombol Unduh Surat --}}
                    <div class="mt-6 text-center">
                        <a href="{{ route('unduh.surat', ['kode' => $peminjaman->code]) }}" 
                           class="inline-block bg-[#eab308] hover:bg-[#facc15] text-[#0f213d] font-semibold px-6 py-3 rounded-lg transition shadow">
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

            {{-- TAMBAHAN INFORMASI KEBIJAKAN --}}
            <div class="mt-4 bg-[#f4f6f9] border border-[#d1d5db] text-[#333333] rounded-lg p-4 text-sm">
                <i class="fa-solid fa-circle-info mr-2 text-[#eab308]"></i>
                <strong>TAMBAHAN INFORMASI :</strong> Apabila pada hari pelaksanaan terdapat peminjaman ruangan dengan lokasi yang sama dan kebutuhan penggunaan ruangan oleh guru dinilai lebih mendesak/urgen, maka pihak pengelola akan menginformasikan kepada siswa yang juga telah meminjam ruangan tersebut paling lambat 1 (satu) hari sebelum pelaksanaan, untuk dilakukan penyesuaian lokasi atau jadwal penggunaan ruangan.
            </div>
        </div>
    </main>

    <footer class="bg-[#0f213d] text-[#d1d5db] py-6 text-center text-sm">
        © 2026 Tim IT SMAN 4 Yogyakarta. All Rights Reserved.
    </footer>

</body>
</html>