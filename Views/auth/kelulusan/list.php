<div class="table-responsive">
    <?= form_open('auth/kelulusan/hapusmassal', ['class' => 'formhapusmassal']) ?>
    <div class="mb-3">
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="fa fa-trash"></i> Hapus yang Dicentang
        </button>
    </div>
    <table id="datakelulusan" class="table table-bordered table-striped display nowrap" style="width:100%">
        <thead>
            <tr>
                <th width="10px">
                    <input type="checkbox" id="centangSemua">
                </th>
                <th width="10px">No</th>
                <th>Nama Lengkap</th>
                <th>NISN</th>
                <th>No. Ujian</th>
                <th>Keterangan</th>
                <th width="80px">Aksi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <?= form_close() ?>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $('#datakelulusan').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= site_url('auth/kelulusan/getdata') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 1, 6],
                "orderable": false,
            }],
        });

        // Fitur Pilih Semua
        $('#centangSemua').click(function(e) {
            if ($(this).is(':checked')) {
                $('.centangItem').prop('checked', true);
            } else {
                $('.centangItem').prop('checked', false);
            }
        });

        // Proses Hapus Massal
        $('.formhapusmassal').submit(function(e) {
            e.preventDefault();
            let jumlahData = $('.centangItem:checked').length;

            if (jumlahData === 0) {
                Swal.fire('Perhatian', 'Silakan pilih data yang ingin dihapus!', 'warning');
                return false;
            }

            Swal.fire({
                title: 'Hapus Massal',
                text: `Apakah Anda yakin ingin menghapus ${jumlahData} data?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses) {
                                Swal.fire('Berhasil', response.sukses, 'success');
                                table.ajax.reload();
                            }
                        }
                    });
                }
            });
        });
    });

    // Fungsi Edit
    function edit(id) {
        $.ajax({
            type: "get",
            url: "<?= site_url('auth/kelulusan/formedit/') ?>" + id,
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modaledit').modal('show');
                }
            }
        });
    }

    // Fungsi Hapus
    function hapus(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('auth/kelulusan/hapus') ?>",
                    data: { kelulusan_id: id },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire('Berhasil', response.sukses, 'success');
                            $('#datakelulusan').DataTable().ajax.reload();
                        }
                    }
                });
            }
        });
    }
</script>