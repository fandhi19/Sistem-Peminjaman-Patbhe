<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Permohonan Peminjaman Ruang</title>

<style>
    body{
        font-family: Arial, sans-serif;
        font-size:12pt;
        margin:30px;
        line-height:1.4;
    }

    .header{
        width:100%;
        border-bottom:3px solid #000;
        padding-bottom:10px;
        margin-bottom:20px;
    }

    .header table{
        width:100%;
        border-collapse:collapse;
    }

    .header td{
        vertical-align:middle;
    }

    .logo{
        width:90px;
    }

    .kop{
        text-align:center;
    }

    .kop .instansi{
        font-size:16px;
        font-weight:bold;
    }

    .kop .sekolah{
        font-size:24px;
        font-weight:bold;
        margin:5px 0;
    }

    .kop .alamat{
        font-size:12px;
    }

    .judul{
        text-align:center;
        font-size:16px;
        font-weight:bold;
        margin:25px 0;
    }

    table.identitas{
        width:100%;
        border-collapse:collapse;
        margin-bottom:20px;
    }

    table.identitas td{
        padding:2px 0;
        vertical-align:top;
    }

    .label{
        width:220px;
    }

    .titik{
        width:15px;
    }

    ol{
        margin-top:5px;
    }

    .ttd{
        margin-top:40px;
        width:100%;
    }

    .ttd table{
        width:100%;
    }

    .ttd td{
        width:50%;
        vertical-align:top;
    }

    .garis-ttd{
        margin-top:80px;
        font-weight: bold;
    }
</style>
</head>

<body>

<div class="header">
    <table>
        <tr>
            <td width="12%">
                <img src="{{ public_path('images/logo-diy.png') }}" class="logo">
            </td>

            <td width="80%" class="kop">
                <div class="instansi">PEMERINTAH DAERAH DAERAH ISTIMEWA YOGYAKARTA</div>
                <div class="instansi">DINAS PENDIDIKAN, PEMUDA, DAN OLAHRAGA</div>
                <div class="instansi">BALAI PENDIDIKAN MENENGAH KOTA YOGYAKARTA</div>
                <div class="sekolah">SMAN 4 YOGYAKARTA</div>

                <div class="alamat">
                    Jalan Magelang Karangwaru Lor No.7 Yogyakarta, Kode Pos 55241
                    <br>
                    Telepon (0274) 513245 Faksimile 0274-582286
                    <br>
                    Pos-el: 4bheyogyakarta@gmail.com
                    Laman: http://www.patbhe-jogja.sch.id
                </div>
            </td>

            <td width="12%" align="right">
                <img src="{{ public_path('images/Logo Patbhe.png') }}" class="logo">
            </td>
        </tr>
    </table>
</div>

<div class="judul">
    PERMOHONAN PEMINJAMAN RUANG
</div>

<p>Saya yang bertanda tangan di bawah ini :</p>

<table class="identitas">
    <tr>
        <td class="label">Nama</td>
        <td class="titik">:</td>
        <td>{{ $peminjaman->nama }}</td>
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
Dengan ini mengajukan permohonan peminjaman ruangan untuk pelaksanaan
kegiatan sebagai berikut :
</p>

<table class="identitas">
    <tr>
        <td class="label">Nama Kegiatan</td>
        <td>:</td>
        <td>{{ $peminjaman->nama_kegiatan }}</td>
    </tr>

    <tr>
        <td>Tujuan Kegiatan</td>
        <td>:</td>
        <td>{{ $peminjaman->tujuan_kegiatan ?? '-' }}</td>
    </tr>

    <tr>
        <td>Ruangan yang dipinjam</td>
        <td>:</td>
        <td>
            {{ $peminjaman->ruangan->name }}
            ({{ $peminjaman->ruangan->code }}) 
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

    <tr>
        <td>Jumlah Peserta</td>
        <td>:</td>
        <td>{{ $peminjaman->jumlah_peserta ?? '-' }} orang</td>
    </tr>
</table>

<p>Sebagai pemohon saya bersedia :</p>

<ol>
    <li>Menggunakan ruangan sesuai jadwal yang telah disetujui.</li>
    <li>Menjaga kebersihan, keamanan, dan ketertiban selama kegiatan berlangsung.</li>
    <li>Bertanggung jawab atas penggunaan seluruh fasilitas yang dipinjam.</li>
    <li>Mengembalikan ruangan beserta fasilitas dalam keadaan bersih, rapi dan baik setelah kegiatan selesai.</li>
    <li>Bersedia mengganti atau memperbaiki apabila terjadi kerusakan akibat kelalaian selama penggunaan.</li>
</ol>

<p>
Demikian surat permohonan ini saya sampaikan. Besar harapan saya agar
permohonan ini dapat disetujui. Atas perhatian dan kerja sama Bapak/Ibu,
saya ucapkan terima kasih.
</p>

<div class="ttd">
    <table>
        <tr>
            <td>
                Wakil Kepala Sekolah<br>
                Bidang Sarpras

                <div class="garis-ttd">
                    ................................
                </div>
            </td>

            <td align="center">
                Yogyakarta,
                {{ \Carbon\Carbon::parse($peminjaman->created_at)
                    ->locale('id')
                    ->translatedFormat('d F Y') }} <br>
                Pemohon

                <div class="garis-ttd">
                    {{ $peminjaman->nama }}
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>