<div class="modal fade" id="modalimport" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Kelulusan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?= form_open_multipart('auth/kelulusan/importdata', ['class' => 'formimport']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pilih File CSV</label>
                    <input type="file" name="filecsv" class="form-control" accept=".csv" required>
                    <small class="text-muted">Format kolom CSV: Nama, NISN, NIS, Tempat Lahir, Tgl Lahir, Kurikulum, No Ujian, Keterangan</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btnimport">Proses Import</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
$('.formimport').submit(function(e) {
    e.preventDefault();
    $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: new FormData(this),
        contentType: false, processData: false, dataType: "json",
        success: function(response) {
            if (response.sukses) {
                Swal.fire('Berhasil', response.sukses, 'success');
                $('#modalimport').modal('hide');
                listkelulusan();
            }
        }
    });
});
</script>