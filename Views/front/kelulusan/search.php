<?= $this->extend('front/layout/main') ?>

<?= $this->section('navbar') ?>
<nav class="nav-menu d-none d-lg-block">
    <ul>
        <li><a href="<?= base_url() ?>">Home</a></li>
        <li><a href="<?= base_url() ?>#visimisi">Visi Misi</a></li>
        <li><a href="<?= base_url() ?>#staf">Staf</a></li>
        <li><a href="<?= base_url() ?>#berita">Berita</a></li>
        <li><a href="<?= base_url() ?>#gallery">Gallery</a></li>
        <li><a href="<?= base_url() ?>#footer">Contact</a></li>
        <li><a href="<?= base_url('home/cekspp') ?>">Cek SPP</a></li>
    </ul>
</nav>
<a href="<?= base_url('kelulusan') ?>" class="get-started-btn">Cari NISN Lain</a>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="container">
    <br>
    <div class="container text-center">
        <h4><?= $title ?></h4>
        <hr>
    </div>

    <?php if (!empty($kelulusan)) : ?>
        <?php foreach ($kelulusan as $value) : ?>
            <div class="container d-flex justify-content-center">
                <?php if ($value['keterangan'] == 'LULUS') : ?>
                    <?php if ($value['keterangan'] == 'LULUS') : ?>
                    <div class="card text-white bg-success mb-3 shadow" style="max-width: 28rem; border-radius: 15px;">
                        <div class="card-header bg-success text-center font-weight-bold">SELAMAT! ANDA DINYATAKAN LULUS</div>
                        <div class="card-body">
                            <h5 class="card-title text-center"><?= $value['nama'] ?></h5>
                            <hr style="border-color: rgba(255,255,255,0.3);">
                            
                            <table class="table text-white table-sm" style="border: none; font-size: 0.9rem;">
                                <tbody>
                                    <tr><th style="border: none; width: 40%;">NIS / NISN</th><td style="border: none;">: <?= $value['nis'] ?> / <?= $value['nisn'] ?></td></tr>
                                    <tr><th style="border: none;">Tempat, Tgl Lahir</th><td style="border: none;">: <?= $value['tempat_lahir'] ?>, <?= $value['tanggal_lahir'] ?></td></tr>
                                    <tr><th style="border: none;">Kurikulum</th><td style="border: none;">: <?= $value['kurikulum'] ?></td></tr>
                                </tbody>
                            </table>
                            <div class="d-grid gap-2">
                                <a href="<?= base_url('kelulusan/download_skl/' . $value['nis']) ?>" target="_blank" class="btn btn-light btn-block btn-sm text-success font-weight-bold mb-2">
                                    <i class="mdi mdi-file-document"></i> Download SKL (PDF)
                                </a>
                                
                                <a href="<?= base_url('kelulusan/download_transkrip/' . $value['nis']) ?>" target="_blank" class="btn btn-warning btn-block btn-sm text-dark font-weight-bold">
                                    <i class="mdi mdi-file-table"></i> Download Transkrip Nilai (PDF)
                                </a>
                            </div>
                        </div>
                    </div>
                <?php elseif ($value['keterangan'] == 'TUNDA') : ?>
                    <div class="alert alert-warning text-center col-lg-6 shadow">
                        <i class="mdi mdi-clock-outline mdi-24px"></i><br>
                        <strong>Mohon Maaf, <?= $value['nama'] ?></strong><br> Status kelulusan Anda saat ini sedang <strong>DITUNDA</strong>. Silakan hubungi pihak sekolah.
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger text-center col-lg-6 shadow">
                        <i class="mdi mdi-close-circle-outline mdi-24px"></i><br>
                        <strong>Maaf, <?= $value['nama'] ?></strong><br> Anda dinyatakan Tidak Lulus. Tetap semangat dan jangan putus asa.
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="text-center">
            <div class="alert alert-danger d-inline-block">Data tidak ditemukan. Silakan cek kembali NISN Anda.</div>
            <br>
            <a href="<?= base_url('kelulusan') ?>" class="btn btn-primary">Kembali</a>
        </div>
    <?php endif; ?>
</div>
<br><br>
<?= $this->endSection() ?>