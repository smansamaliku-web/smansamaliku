<!-- Modal -->
<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('auth/kelulusan/simpankelulusan', ['class' => 'formtambah']) ?>
            <?= csrf_field(); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Siswa</label>
                    <div class="col-sm-8">
                        <select name="siswa_id" id="siswa_id" class="js-example-basic-single form-control">
                            <option disabled selected>Pilih Siswa</option>
                            <?php foreach ($siswa as $key => $data) { ?>
                                <option value="<?= $data['siswa_id'] ?>"><?= $data['nis'] ?> - <?= $data['nama'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback errorSiswa"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nomor Ujian</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="no_ujian" name="no_ujian">
                        <div class="invalid-feedback errorNoujian"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Jurusan</label>
                    <div class="col-sm-8">
                        <select name="jurusan" id="jurusan" class="form-control">
                            <option disabled selected>Pilih</option>
                            <option value="IPA">IPA</option>
                            <option value="IPS">IPS</option>
                        </select>
                        <div class="invalid-feedback errorJurusan"></div>
                    </div>
                </div>

                <!-- ✅ PENTING: Field "mapel" ini untuk "Kurikulum" -->
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Kurikulum</label>
                    <div class="col-sm-8">
                        <select name="mapel" id="mapel" class="form-control">
                            <option disabled selected>Pilih</option>
                            <option value="K-13">K-13</option>
                            <option value="Merdeka">Merdeka</option>
                        </select>
                        <div class="invalid-feedback errorMapel"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Keterangan</label>
                    <div class="col-sm-8">
                        <select name="keterangan" id="keterangan" class="form-control">
                            <option disabled selected>Pilih</option>
                            <option value="LULUS">LULUS</option>
                            <option value="TIDAK LULUS">TIDAK LULUS</option>
                            <option value="TUNDA">TUNDA</option>
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
    <script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            theme: "bootstrap4"
        });
        
        $('.formtambah').submit(function(e) {
            e.preventDefault();
            
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
                    console.log('Success Response:', response);
                    
                    if (response.sukses) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.sukses,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        $('#modaltambah').modal('hide');
                        $('.formtambah')[0].reset();
                        
                        // ✅ Call function dengan error handling
                        if (typeof listkelulusan === 'function') {
                            listkelulusan();
                        } else {
                            console.error('Function listkelulusan tidak ditemukan');
                            location.reload();
                        }
                    } else if (response.error) {
                        Swal.fire({
                            title: 'Error!',
                            text: response.error,
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        response: xhr.responseText,
                        error: error
                    });
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal menyimpan data. Error: ' + xhr.statusText,
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
</script>