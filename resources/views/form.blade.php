<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman - SIPERU PATBHE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/Logo Patbhe.png') }}" type="image/x-icon">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800 min-h-screen flex flex-col">

    {{-- Header / Navbar --}}
    <nav class="bg-[#0f213d] shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <a href="/" class="flex items-center space-x-3">
                <div class="rounded-full flex items-center justify-center font-bold text-xl shadow-md">
                    <img src="{{ asset('images/Logo Patbhe.png') }}" alt="Logo SMAN 4 Yogyakarta" class="w-10 h-10">
                </div>
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

    {{-- Konten Form --}}
    <main class="flex-grow py-12 px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-extrabold text-[#142b52] mb-2 text-center">Form Pengajuan Peminjaman Ruangan</h1>
            <p class="text-center text-[#555555] mb-8">Lengkapi data di bawah untuk mengajukan peminjaman.</p>

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
            <div class="bg-[#f4f6f9] border-l-4 border-[#eab308] text-gray-800 rounded-xl p-6 mb-8 shadow-sm">
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-circle-check text-[#eab308] text-3xl"></i>
                    <div>
                        <h3 class="font-bold text-lg text-[#0f213d]">Pengajuan Berhasil!</h3>
                        <p class="mt-1 text-[#333333]">Kode peminjaman Anda: <span class="font-mono bg-[#eab308] text-[#0f213d] px-2 py-1 rounded font-bold text-lg">{{ session('kode_peminjaman') }}</span></p>
                        <p class="text-sm mt-2 text-[#555555]">Silakan simpan kode ini untuk mengecek status peminjaman Anda.</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="{{ route('tracking.result', ['kode' => session('kode_peminjaman')]) }}"
                                class="inline-block bg-[#0f213d] hover:bg-[#142b52] text-white font-semibold px-4 py-2 rounded-lg text-sm transition border border-[#eab308]">
                                <i class="fa-solid fa-search mr-1"></i> Lihat Status
                            </a>
                            <a href="{{ route('unduh.surat', ['kode' => session('kode_peminjaman')]) }}"
                                class="inline-block bg-[#eab308] hover:bg-[#facc15] text-[#0f213d] font-semibold px-4 py-2 rounded-lg text-sm transition">
                                <i class="fa-solid fa-download mr-1"></i> Unduh Surat Permohonan
                            </a>
                        </div>
                    </div>
                </div>
                {{-- TAMBAHAN INFORMASI KEBIJAKAN --}}
                <div class="mt-4 bg-[#f4f6f9] border border-[#d1d5db] text-[#333333] rounded-lg p-4 text-sm">
                    <i class="fa-solid fa-circle-info mr-2 text-[#eab308]"></i>
                    <strong>TAMBAHAN INFORMASI :</strong> Apabila pada hari pelaksanaan terdapat peminjaman ruangan dengan lokasi yang sama dan kebutuhan penggunaan ruangan oleh guru dinilai lebih mendesak/urgen, maka pihak pengelola akan menginformasikan kepada siswa yang juga telah meminjam ruangan tersebut paling lambat 1 (satu) hari sebelum pelaksanaan, untuk dilakukan penyesuaian lokasi atau jadwal penggunaan ruangan.
                </div>
            </div>
            @endif

            {{-- Card Form --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#d1d5db] p-6 sm:p-8">
                <form action="{{ route('form.peminjaman.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label for="nama" class="block text-sm font-medium text-[#333333]">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIP/NISN --}}
                    <div>
                        <label for="nip_nisn" class="block text-sm font-medium text-[#333333]">NIP / NISN <span class="text-gray-400">(opsional)</span></label>
                        <input type="text" name="nip_nisn" id="nip_nisn" value="{{ old('nip_nisn') }}"
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('nip_nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jabatan / Kelas --}}
                    <div>
                        <label for="jabatan_kelas" class="block text-sm font-medium text-[#333333]">Jabatan / Kelas <span class="text-gray-400">(opsional)</span></label>
                        <input type="text" name="jabatan_kelas" id="jabatan_kelas" value="{{ old('jabatan_kelas') }}"
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('jabatan_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Unit Kerja / Organisasi --}}
                    <div>
                        <label for="unit_kerja_organisasi" class="block text-sm font-medium text-[#333333]">Unit Kerja / Organisasi <span class="text-gray-400">(opsional)</span></label>
                        <input type="text" name="unit_kerja_organisasi" id="unit_kerja_organisasi" value="{{ old('unit_kerja_organisasi') }}"
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('unit_kerja_organisasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-[#333333]">No HP <span class="text-gray-400">(opsional)</span></label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Kegiatan --}}
                    <div>
                        <label for="nama_kegiatan" class="block text-sm font-medium text-[#333333]">Nama Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('nama_kegiatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tujuan Kegiatan --}}
                    <div>
                        <label for="tujuan_kegiatan" class="block text-sm font-medium text-[#333333]">Tujuan Kegiatan <span class="text-gray-400">(opsional)</span></label>
                        <textarea name="tujuan_kegiatan" id="tujuan_kegiatan" rows="3"
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">{{ old('tujuan_kegiatan') }}</textarea>
                        @error('tujuan_kegiatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ruangan --}}
                    <div>
                        <label for="ruangan_id" class="block text-sm font-medium text-[#333333]">Pilih Ruangan <span class="text-red-500">*</span></label>
                        <select name="ruangan_id" id="ruangan_id" required
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" @selected(old('ruangan_id') == $ruangan->id)>
                                {{ $ruangan->name }} ({{ $ruangan->code }}) - Kapasitas {{ $ruangan->capacity }}
                            </option>
                            @endforeach
                        </select>
                        @error('ruangan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-[#333333]">Tanggal Pemakaian <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" required
                            min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}"
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jam Mulai & Selesai --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="jam_mulai" class="block text-sm font-medium text-[#333333]">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_mulai" id="jam_mulai" required value="{{ old('jam_mulai') }}"
                                class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                            @error('jam_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="jam_selesai" class="block text-sm font-medium text-[#333333]">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_selesai" id="jam_selesai" required value="{{ old('jam_selesai') }}"
                                class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                            @error('jam_selesai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Jumlah Peserta --}}
                    <div>
                        <label for="jumlah_peserta" class="block text-sm font-medium text-[#333333]">Jumlah Peserta <span class="text-gray-400">(opsional)</span></label>
                        <input type="number" name="jumlah_peserta" id="jumlah_peserta" value="{{ old('jumlah_peserta') }}" min="1"
                            class="mt-1 block w-full rounded-lg border-[#d1d5db] shadow-sm focus:border-[#eab308] focus:ring-[#eab308] sm:text-sm p-3 border">
                        @error('jumlah_peserta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-[#0f213d] hover:bg-[#142b52] text-white font-bold py-3 px-6 rounded-lg shadow-md transition hover:-translate-y-0.5 text-lg border border-[#eab308]">
                            <i class="fa-solid fa-paper-plane mr-2 text-[#eab308]"></i> Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-[#0f213d] text-[#d1d5db] py-6 text-center text-sm">
        © 2026 Tim IT SMAN 4 Yogyakarta. All Rights Reserved.
    </footer>

</body>
</html>