<?php

namespace App\Controllers;

use App\Models\Modelkelulusan;
use App\Models\Modelsiswa;

class Kelulusan extends BaseController
{
    public function admin_index()
    {
        $data = ['title' => 'Kelola Data Kelulusan'];
        return view('auth/kelulusan/index', $data);
    }

    public function getdata()
    {
        if ($this->request->isAJAX()) {
            $datamodel = new Modelkelulusan();
            
            if ($this->request->getPost('draw')) {
                $lists = $datamodel->get_datatables($this->request);
                $data = [];
                $no = $this->request->getPost("start");

                foreach ($lists as $list) {
                    $no++;
                    $row = [];
                    $row[] = "<input type=\"checkbox\" name=\"kelulusan_id[]\" class=\"centangItem\" value=\"$list->kelulusan_id\">";
                    $row[] = $no;
                    $row[] = $list->nama;
                    $row[] = $list->nisn;
                    $row[] = $list->no_ujian ?? '-';
                    $row[] = $list->keterangan;
                    $row[] = "<button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"edit('$list->kelulusan_id')\"><i class=\"fa fa-edit\"></i></button>
                              <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"hapus('$list->kelulusan_id')\"><i class=\"fa fa-trash\"></i></button>";
                    $data[] = $row;
                }

                $output = [
                    "draw" => (int)$this->request->getPost('draw'),
                    "recordsTotal" => $datamodel->count_all(),
                    "recordsFiltered" => $datamodel->count_filtered($this->request),
                    "data" => $data,
                ];
                return $this->response->setJSON($output);
            } else {
                return $this->response->setJSON([
                    'data' => view('auth/kelulusan/list')
                ]);
            }
        }
    }

    public function formtambah()
    {
        if ($this->request->isAJAX()) {
            $siswaModel = new Modelsiswa();
            $data = [
                'title' => 'Tambah Data Kelulusan Manual',
                'siswa' => $siswaModel->findAll()
            ];
            return $this->response->setJSON([
                'data' => view('auth/kelulusan/tambah', $data)
            ]);
        }
    }

    // ✅ METHOD #1 - FORM EDIT (GET)
    public function formedit($id)
    {
        if ($this->request->isAJAX()) {
            $model = new Modelkelulusan();
            $siswaModel = new Modelsiswa();
            
            $kelulusan = $model->find($id);
            
            if ($kelulusan) {
                $data = [
                    'title' => 'Edit Data Kelulusan',
                    'kelulusan_id'  => $kelulusan['kelulusan_id'],
                    'siswa_id'      => $kelulusan['siswa_id'],
                    'nama'          => $kelulusan['nama'],
                    'nisn'          => $kelulusan['nisn'],
                    'no_ujian'      => $kelulusan['no_ujian'],
                    'jurusan'       => $kelulusan['jurusan'],
                    'mapel'         => $kelulusan['mapel'],
                    'keterangan'    => $kelulusan['keterangan'],
                    'siswa'         => $siswaModel->findAll()
                ];
                return $this->response->setJSON([
                    'data' => view('auth/kelulusan/edit', $data)
                ]);
            }
            return $this->response->setJSON(['error' => 'Data tidak ditemukan']);
        }
    }

    // ✅ METHOD #2 - UPDATE DATA (POST)
    public function updatekelulusan()
    {
        if ($this->request->isAJAX()) {
            $model = new Modelkelulusan();
            $siswaModel = new Modelsiswa();
            
            $kelulusan_id = $this->request->getPost('kelulusan_id');
            $siswa_id = $this->request->getPost('siswa_id');
            $siswa = $siswaModel->find($siswa_id);
    
            if ($siswa) {
                try {
                    $model->update($kelulusan_id, [
                        'siswa_id'      => $siswa_id,
                        'nama'          => $siswa['nama'],
                        'nisn'          => $siswa['nisn'],
                        'tempat_lahir'  => $siswa['tmp_lahir'] ?? '',
                        'tanggal_lahir' => $siswa['tgl_lahir'] ?? '',
                        'kurikulum'     => $this->request->getPost('mapel') ?? 'K-13',
                        'no_ujian'      => $this->request->getPost('no_ujian'),
                        'jurusan'       => $this->request->getPost('jurusan'),
                        'mapel'         => $this->request->getPost('mapel'),
                        'keterangan'    => $this->request->getPost('keterangan'),
                    ]);
    
                    return $this->response->setJSON(['sukses' => 'Data berhasil diperbarui']);
                } catch (\Exception $e) {
                    return $this->response->setJSON(['error' => 'Error: ' . $e->getMessage()]);
                }
            }
            return $this->response->setJSON(['error' => 'Siswa tidak ditemukan']);
        }
    }

    // ✅ METHOD #3 - HAPUS SINGLE (POST)
    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $model = new Modelkelulusan();
            $id = $this->request->getPost('kelulusan_id');
            
            if ($model->delete($id)) {
                return $this->response->setJSON(['sukses' => 'Data berhasil dihapus']);
            }
            return $this->response->setJSON(['error' => 'Gagal menghapus data']);
        }
    }

    // ✅ METHOD #4 - SIMPAN DATA (POST)
   public function simpankelulusan()
    {
        if ($this->request->isAJAX()) {
            $model = new Modelkelulusan();
            $siswaModel = new Modelsiswa();
            
            $siswa_id = $this->request->getPost('siswa_id');
            $no_ujian = $this->request->getPost('no_ujian');
            $jurusan = $this->request->getPost('jurusan');
            $mapel = $this->request->getPost('mapel');
            $keterangan = $this->request->getPost('keterangan');
    
            // Debug log
            log_message('debug', 'Simpan Kelulusan - Input: ' . json_encode([
                'siswa_id' => $siswa_id,
                'no_ujian' => $no_ujian,
                'jurusan' => $jurusan,
                'mapel' => $mapel,
                'keterangan' => $keterangan
            ]));
    
            if (!$siswa_id) {
                return $this->response->setJSON(['error' => 'Siswa harus dipilih'], 400);
            }
    
            $siswa = $siswaModel->find($siswa_id);
    
            if (!$siswa) {
                return $this->response->setJSON(['error' => 'Siswa tidak ditemukan'], 400);
            }
    
            try {
                $data_insert = [
                    'siswa_id'      => $siswa_id,
                    'nama'          => $siswa['nama'] ?? '',
                    'nisn'          => $siswa['nisn'] ?? '',
                    'tempat_lahir'  => $siswa['tmp_lahir'] ?? '',
                    'tanggal_lahir' => $siswa['tgl_lahir'] ?? '',
                    'kurikulum'     => $mapel ?? 'K-13',
                    'no_ujian'      => $no_ujian,
                    'jurusan'       => $jurusan,
                    'mapel'         => $mapel,
                    'keterangan'    => $keterangan,
                ];
    
                log_message('debug', 'Data to insert: ' . json_encode($data_insert));
    
                $result = $model->save($data_insert);
    
                if ($result) {
                    return $this->response->setJSON([
                        'sukses' => 'Data berhasil ditambahkan',
                        'id' => $result
                    ]);
                } else {
                    $errors = $model->errors();
                    log_message('error', 'Model errors: ' . json_encode($errors));
                    return $this->response->setJSON([
                        'error' => 'Gagal menyimpan data. Errors: ' . json_encode($errors)
                    ], 400);
                }
            } catch (\Exception $e) {
                log_message('error', 'Exception: ' . $e->getMessage());
                return $this->response->setJSON([
                    'error' => 'Exception: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function importdata()
    {
        if ($this->request->isAJAX()) {
            $file = $this->request->getFile('filecsv');
            if ($file) {
                $fileHandle = fopen($file->getTempName(), 'r');
                $row = 0;
                $model = new Modelkelulusan();
                $dataBatch = [];

                while (($data = fgetcsv($fileHandle, 1000, ",")) !== FALSE) {
                    $row++;
                    if ($row == 1) continue;

                    $dataBatch[] = [
                        'nama'          => $data[0],
                        'nisn'          => $data[1],
                        'tempat_lahir'  => $data[2],
                        'tanggal_lahir' => $data[3],
                        'kurikulum'     => $data[4],
                        'no_ujian'      => $data[5],
                        'keterangan'    => $data[6],
                        'tgl_upload'    => date('Y-m-d H:i:s')
                    ];
                }
                fclose($fileHandle);

                if (count($dataBatch) > 0) {
                    $model->import_batch($dataBatch);
                    return $this->response->setJSON(['sukses' => count($dataBatch) . ' data berhasil diimport']);
                }
            }
        }
    }

    public function hapusmassal()
    {
        if ($this->request->isAJAX()) {
            $ids = $this->request->getPost('kelulusan_id');
            if ($ids) {
                $model = new Modelkelulusan();
                $model->hapus_massal($ids);
                return $this->response->setJSON(['sukses' => count($ids) . ' data berhasil dihapus']);
            }
        }
    }
}