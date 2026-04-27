<!-- Modal -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('auth/kelulusan/updatekelulusan', ['class' => 'formtambah']) ?>
            <?= csrf_field(); ?>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="kelulusan_id" value="<?= $kelulusan_id ?>" name="kelulusan_id" readonly>
                
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Siswa</label>
                    <div class="col-sm-8">
                        <select name="siswa_id" id="siswa_id" class="js-example-basic-single form-control">
                            <option disabled>Pilih Siswa</option>
                            <?php foreach ($siswa as $key => $data) { ?>
                                <option value="<?= $data['siswa_id'] ?>" <?php if ($data['siswa_id'] == $siswa_id) echo "selected"; ?>>
                                    <?= $data['nis'] ?> - <?= $data['nama'] ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback errorSiswa"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nomor Ujian</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="no_ujian" value="<?= $no_ujian ?>" name="no_ujian">
                        <div class="invalid-feedback errorNoujian"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Jurusan</label>
                    <div class="col-sm-8">
                        <select name="jurusan" id="jurusan" class="form-control">
                            <option value="IPA" <?php if ($jurusan == 'IPA') echo "selected"; ?>>IPA</option>
                            <option value="IPS" <?php if ($jurusan == 'IPS') echo "selected"; ?>>IPS</option>
                        </select>
                        <div class="invalid-feedback errorJurusan"></div>
                    </div>
                </div>

                <!-- ✅ PENTING: Field "mapel" ini untuk "Kurikulum" -->
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Kurikulum</label>
                    <div class="col-sm-8">
                        <select name="mapel" id="mapel" class="form-control">
                            <option value="K-13" <?php if ($mapel == 'K-13') echo "selected"; ?>>K-13</option>
                            <option value="Merdeka" <?php if ($mapel == 'Merdeka') echo "selected"; ?>>Merdeka</option>
                        </select>
                        <div class="invalid-feedback errorMapel"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Keterangan</label>
                    <div class="col-sm-8">
                        <select name="keterangan" id="keterangan" class="form-control">
                            <option value="LULUS" <?php if ($keterangan == 'LULUS') echo "selected"; ?>>LULUS</option>
                            <option value="TIDAK LULUS" <?php if ($keterangan == 'TIDAK LULUS') echo "selected"; ?>>TIDAK LULUS</option>
                            <option value="TUNDA" <?php if ($keterangan == 'TUNDA') echo "selected"; ?>>TUNDA</option>
                        </select>
                        <div class="invalid-feedback errorKeterangan"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnsimpan"><i class="fa fa-share-square"></i> Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            theme: "bootstrap4"
        }).prop('disabled', true);
        
        $('.formtambah').submit(function(e) {
            e.preventDefault();
            $('.js-example-basic-single').prop('disabled', false);
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnsimpan').prop('disabled', true);
                    $('.btnsimpan').html('<span class="spinner-border spinner-border-sm"></span> Loading...');
                },
                complete: function() {
                    $('.btnsimpan').prop('disabled', false);
                    $('.btnsimpan').html('<i class="fa fa-share-square"></i> Simpan');
                },
                success: function(response) {
                    console.log(response);
                    if (response.sukses) {
                        Swal.fire('Berhasil!', response.sukses, 'success');
                        $('#modaledit').modal('hide');
                        listkelulusan();
                    } else if (response.error) {
                        Swal.fire('Error!', response.error, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    Swal.fire('Error!', 'Gagal update data: ' + error, 'error');
                }
            });
        });
    });
</script>