<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Peminjaman Barang</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 25px 35px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .kop {
            text-align: center;
        }

        .kop .instansi {
            font-size: 12pt;
            font-weight: bold;
        }

        .kop .sekolah {
            font-size: 18pt;
            font-weight: bold;
            margin-top: 3px;
            margin-bottom: 3px;
        }

        .kop .alamat {
            font-size: 10pt;
            line-height: 1.4;
        }

        .judul {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .identitas td,
        .kegiatan td {
            padding: 3px 0;
            vertical-align: top;
        }

        .label {
            width: 220px;
        }

        .separator {
            width: 15px;
        }

        .kesediaan {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .kesediaan ol {
            margin: 0;
            padding-left: 20px;
        }

        .kesediaan li {
            margin-bottom: 5px;
        }

        .ttd {
            margin-top: 40px;
        }

        .ttd td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .nama-ttd {
            margin-top: 80px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- KOP SURAT -->
    <table class="header-table">
        <tr>
            <td width="12%" align="left">
                <img src="{{ public_path('images/logo-diy.png') }}" class="logo">
            </td>

            <td width="80%" class="kop">
                <div class="instansi">
                    PEMERINTAH DAERAH DAERAH ISTIMEWA YOGYAKARTA
                </div>

                <div class="instansi">
                    DINAS PENDIDIKAN, PEMUDA, DAN OLAHRAGA
                </div>

                <div class="instansi">
                    BALAI PENDIDIKAN MENENGAH KOTA YOGYAKARTA
                </div>

                <div class="sekolah">
                    SMAN 4 YOGYAKARTA
                </div>

                <div class="alamat">
                    Jalan Magelang Karangwaru Lor No.7 Yogyakarta, Kode Pos 55241
                    <br>
                    Telepon (0274) 513245 Faksimile (0274) 582286
                    <br>
                    Pos-el: 4bheyogyakarta@gmail.com
                    &nbsp;&nbsp;
                    Laman: http://www.patbhe-jogja.sch.id
                </div>
            </td>

            <td width="12%" align="right">
                <img src="{{ public_path('images/Logo Patbhe.png') }}" class="logo">
            </td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="judul">
        PERMOHONAN PEMINJAMAN BARANG
    </div>

    <p>
        Saya yang bertanda tangan di bawah ini :
    </p>

    <!-- IDENTITAS -->
    <table class="identitas">
        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td><strong>{{ $peminjaman->nama }}</strong></td>
        </tr>

        <tr>
            <td>NIP / NIS</td>
            <td>:</td>
            <td>{{ $peminjaman->nip_nisn ?? '-' }}</td>
        </tr>

        <tr>
            <td>Jabatan / Kelas</td>
            <td>:</td>
            <td>{{ $peminjaman->jabatan_kelas ?? '-' }}</td>
        </tr>

        <tr>
            <td>Unit Kerja / Organisasi</td>
            <td>:</td>
            <td>{{ $peminjaman->unit_kerja_organisasi ?? '-' }}</td>
        </tr>

        <tr>
            <td>No. HP</td>
            <td>:</td>
            <td>{{ $peminjaman->no_hp ?? '-' }}</td>
        </tr>
    </table>

    <p>
        Dengan ini mengajukan permohonan peminjaman barang untuk pelaksanaan kegiatan sebagai berikut :
    </p>

    <!-- DATA KEGIATAN -->
    <table class="kegiatan">
        <tr>
            <td class="label">Nama Kegiatan</td>
            <td class="separator">:</td>
            <td>{{ $peminjaman->kegiatan }}</td>
        </tr>

        <tr>
            <td>Tujuan Kegiatan</td>
            <td>:</td>
            <td>{{ $peminjaman->tujuan ?? '-' }}</td>
        </tr>

        <tr>
            <td>Barang yang Dipinjam</td>
            <td>:</td>
            <td>
                {{ $peminjaman->barang->name }}
            </td>
        </tr>

        <tr>
            <td>Hari / Tanggal</td>
            <td>:</td>
            <td>
                {{ \Carbon\Carbon::parse($peminjaman->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
            </td>
        </tr>

        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>
                {{ substr($peminjaman->jam_mulai,0,5) }}
                s/d
                {{ substr($peminjaman->jam_selesai,0,5) }}
            </td>
        </tr>
    </table>

    <br>

    <p>
        Sebagai pemohon saya bersedia :
    </p>

    <div class="kesediaan">
        <ol>
            <li>Menggunakan barang sesuai jadwal yang telah disetujui.</li>
            <li>Menjaga kebersihan, keamanan, dan kondisi barang selama kegiatan berlangsung.</li>
            <li>Bertanggung jawab atas penggunaan seluruh barang yang dipinjam.</li>
            <li>Mengembalikan barang dalam keadaan bersih, rapi, dan berfungsi dengan baik setelah kegiatan selesai.</li>
            <li>Bersedia mengganti atau memperbaiki barang apabila terjadi kerusakan akibat kelalaian selama penggunaan.</li>
        </ol>
    </div>

    <p>
        Demikian surat permohonan ini saya sampaikan. Besar harapan saya agar permohonan ini dapat disetujui.
        Atas perhatian dan kerja sama Bapak/Ibu, saya ucapkan terima kasih.
    </p>

    <!-- TANDA TANGAN -->
    <table class="ttd">
        <tr>
            <td>
                Wakil Kepala Sekolah<br>
                Bidang Sarana dan Prasarana

                <div class="nama-ttd">
                    _______________________
                </div>
            </td>

            <td>
                Yogyakarta,
                {{ \Carbon\Carbon::parse($peminjaman->created_at)
                    ->locale('id')
                    ->translatedFormat('d F Y') }} <br>
                Pemohon

                <div class="nama-ttd">
                    {{ $peminjaman->nama }}
                </div>
            </td>
        </tr>
    </table>

</body>

</html>