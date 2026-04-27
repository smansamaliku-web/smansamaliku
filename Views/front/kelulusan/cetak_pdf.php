<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKL - <?= $value['nama'] ?></title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.3; margin: 0.5cm; }
        
        /* Container Kop Surat */
        .kop-container { width: 100%; border-bottom: 3px double #000; padding-bottom: 5px; margin-bottom: 15px; position: relative; }
        .logo-sekolah { position: absolute; top: 0; right: 0; width: 80px; } /* Atur posisi logo di kanan atas */
        .header-text { text-align: center; margin-right: 80px; } /* Kasih ruang agar teks tidak tertabrak logo */
        
        .header-text h2, .header-text h3 { margin: 0; padding: 0; }
        .header-text p { font-size: 10pt; margin: 2px 0; }
        
        .judul-surat { text-align: center; margin-bottom: 20px; }
        .judul-surat h4 { text-decoration: underline; margin-bottom: 0; }
        
        .data-diri { margin-bottom: 15px; width: 100%; }
        .data-diri td { vertical-align: top; }
        
        .tabel-nilai { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11pt; }
        .tabel-nilai th, .tabel-nilai td { border: 1px solid black; padding: 4px 8px; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        
        .footer-table { width: 100%; margin-top: 20px; }
        .footer-table td { width: 50%; vertical-align: top; }
    </style>
</head>
<body>

    <div class="kop-container" style="width: 100%; position: relative; border-bottom: 3px double #000; padding-bottom: 5px; margin-bottom: 15px; min-height: 100px;">
        <?php if(!empty($logo_provinsi)): ?>
            <img src="<?= $logo_provinsi ?>" style="position: absolute; left: 0; top: 0; width: 83px; height: auto;">
        <?php endif; ?>
    
        <?php if(!empty($logo_sekolah)): ?>
            <img src="<?= $logo_sekolah ?>" style="position: absolute; right: 0; top: 0; width: 80px; height: auto;">
        <?php endif; ?>
        
        <div class="header-text" style="text-align: center; margin-left: 85px; margin-right: 85px;">
            <h3 style="margin: 0; font-size: 14pt;">PEMERINTAH PROVINSI KALIMANTAN TENGAH</h3>
            <h3 style="margin: 0; font-size: 14pt;">DINAS PENDIDIKAN</h3>
            <h2 style="margin: 0; font-size: 16pt;">SMA NEGERI 1 MALIKU</h2>
            <p style="font-size: 10pt; margin: 2px 0;">Jl. Poros Pangkoh VI Desa Garantung Telp: 085251223204 Kec. Maliku 73573</p>
            <p style="font-size: 10pt; margin: 2px 0;">Email: smansatumaliku@gmail.com | Website: www.smansamaliku.sch.id</p>
        </div>
    </div>

    <div class="judul-surat">
        <h4>SURAT KETERANGAN LULUS</h4>
        <span>Nomor: 422 / <?= $value['no_ujian'] ?> / SMAN-1 MLK / 04 / DISDIK / V / 2025</span>
    </div>

    <p>Yang bertanda tangan di bawah ini Kepala SMA Negeri 1 Maliku menerangkan bahwa:</p>

    <table class="data-diri">
        <tr>
            <td width="30%">Nama</td>
            <td width="2%">:</td>
            <td class="bold"><?= strtoupper($value['nama']) ?></td>
        </tr>
        <tr>
            <td>Tempat dan Tanggal Lahir</td>
            <td>:</td>
            <td>Maliku, <?= date('d F Y') ?></td> 
        </tr>
        <tr>
            <td>Nomor Induk Siswa (NIS)</td>
            <td>:</td>
            <td><?= $value['nis'] ?></td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>:</td>
            <td><?= $value['nisn'] ?? $value['nis'] ?></td> 
        </tr>
        <tr>
            <td>Peminatan/Jurusan</td>
            <td>:</td>
            <td><?= $value['jurusan'] ?></td>
        </tr>
    </table>

    <p>Telah dinyatakan <strong>LULUS</strong> dari satuan pendidikan berdasarkan hasil rapat dewan guru dengan perolehan nilai sebagai berikut:</p>

    <table class="tabel-nilai">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="5%">No</th>
                <th>Mata Pelajaran</th>
                <th width="15%">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="3" class="bold">Kelompok A (Umum)</td></tr>
            <tr><td class="text-center">1</td><td>Pendidikan Agama dan Budi Pekerti</td><td class="text-center">80.00</td></tr>
            <tr><td class="text-center">2</td><td>Pendidikan Pancasila dan Kewarganegaraan</td><td class="text-center">82.00</td></tr>
            <tr><td class="text-center">3</td><td>Bahasa Indonesia</td><td class="text-center">85.00</td></tr>
            
            <tr><td colspan="3" class="bold">Kelompok B (Umum)</td></tr>
            <tr><td class="text-center">1</td><td>Seni Budaya</td><td class="text-center">88.00</td></tr>
            <tr><td class="text-center">2</td><td>Pendidikan Jasmani Olahraga dan Kesehatan</td><td class="text-center">84.00</td></tr>
            
            <tr class="bold">
                <td colspan="2" class="text-center">RATA-RATA</td>
                <td class="text-center"><?= $rata_rata ?></td>
            </tr>
        </tbody>
    </table>

    <p style="font-size: 10pt; font-style: italic;">*Keterangan: Surat ini tidak berlaku jika Ijazah asli telah diterima.</p>

    <table class="footer-table">
        <tr>
            <td></td>
            <td class="text-center">
                Maliku, 5 Mei 2025<br>
                Kepala SMA Negeri 1 Maliku,<br>
                <br><br><br><br>
                <strong>RACHMAN HAKIM, S.Pd</strong><br>
                NIP. 19710509 199401 1 001
            </td>
        </tr>
    </table>

</body>
</html>