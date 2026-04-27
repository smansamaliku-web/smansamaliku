<?= $this->extend('layout/script') ?>

<?= $this->section('judul') ?>
<div class="col-sm-6">
    <h4 class="page-title"><?= $title ?></h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Data Sekolah</a></li>
        <li class="breadcrumb-item active">Kelulusan</li>
    </ol>
</div>
<?= $this->endSection('judul') ?>

<?= $this->section('isi') ?>
<p class="sub-title"> 
    <button type="button" class="btn btn-primary btn-sm tomboltambah">
        <i class="fa fa-plus-circle"></i> Tambah Data Kelulusan
    </button>
    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalimport">
        <i class="fa fa-upload"></i> Import CSV
    </button>
</p>
<div class="viewdata">
</div>

<div class="viewmodal">
</div>

<script>
    function listkelulusan() {
        $.ajax({
            url: "<?= site_url('auth/kelulusan/getdata') ?>",
            dataType: "json",
            success: function(response) {
                $('.viewdata').html(response.data);
            },
            error: function(xhr, status, error) {
                console.log('Error loading kelulusan data:', error);
            }
        });
    }

    $(document).ready(function() {
        listkelulusan();
        
        // Tombol Tambah Data
        $('.tomboltambah').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= site_url('auth/kelulusan/formtambah') ?>",
                dataType: "json",
                success: function(response) {
                    $('.viewmodal').html(response.data).show();
                    $('#modaltambah').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log('Error loading form:', error);
                }
            });
        });
    });
</script>
<?= $this->endSection('isi') ?>