<?php

namespace App\Controllers;

use Config\Services;
class Pengumuman extends BaseController
{

    public function index()
    {
        $data = [
            'title' => 'Pengumuman'
        ];
        return view('auth/pengumuman/index', $data);
    }

    public function getdata()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'title' => 'Pengumuman',
                'list' => $this->pengumuman->orderBy('pengumuman_id', 'ASC')->findAll()
            ];
            $msg = [
                'data' => view('auth/pengumuman/list', $data)
            ];
            echo json_encode($msg);
        }
    }

    public function formtambah()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'title' => 'Tambah Pengumuman'
            ];
            $msg = [
                'data' => view('auth/pengumuman/tambah', $data)
            ];
            echo json_encode($msg);
        }
    }

    public function simpan()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'judul_pengumuman' => [
                    'label' => 'Judul Pengumuman',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'isi_pengumuman' => [
                    'label' => 'Isi Pengumuman',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ]
            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'judul_pengumuman' => $validation->getError('judul_pengumuman'),
                        'isi_pengumuman' => $validation->getError('isi_pengumuman'),
                    ]
                ];
            } else {
                $simpandata = [
                    'judul_pengumuman' => $this->request->getVar('judul_pengumuman'),
                    'isi_pengumuman' => $this->request->getVar('isi_pengumuman'),
                    'tanggal'        => $this->request->getVar('tanggal'),
                ];

                $this->pengumuman->insert($simpandata);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function formedit()
    {
        if ($this->request->isAJAX()) {
            $pengumuman_id = $this->request->getVar('pengumuman_id');
            $list =  $this->pengumuman->find($pengumuman_id);
            $data = [
                'title'           => 'Edit Pengumuman',
                'pengumuman_id'     => $list['pengumuman_id'],
                'judul_pengumuman'   => $list['judul_pengumuman'],
                'isi_pengumuman'   => $list['isi_pengumuman'],
            ];
            $msg = [
                'sukses' => view('auth/pengumuman/edit', $data)
            ];
            echo json_encode($msg);
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'judul_pengumuman' => [
                    'label' => 'Judul Pengumuman',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'isi_pengumuman' => [
                    'label' => 'Isi Pengumuman',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ]
            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'judul_pengumuman' => $validation->getError('isi_pengumuman'),
                        'isi_pengumuman' => $validation->getError('judul_pengumuman'),
                    ]
                ];
            } else {
                $updatedata = [
                    'judul_pengumuman' => $this->request->getVar('judul_pengumuman'),
                    'isi_pengumuman' => $this->request->getVar('isi_pengumuman'),
                    'tanggal'        => $this->request->getVar('tanggal'),
                ];

                $pengumuman_id = $this->request->getVar('pengumuman_id');
                $this->pengumuman->update($pengumuman_id, $updatedata);
                $msg = [
                    'sukses' => 'Data berhasil diupdate'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {

            $pengumuman_id = $this->request->getVar('pengumuman_id');

            $this->pengumuman->delete($pengumuman_id);
            $msg = [
                'sukses' => 'Pengumuman Berhasil Dihapus'
            ];

            echo json_encode($msg);
        }
    }

    //Start Pengumuman Kelulusan (Back-end)
    public function kelulusan()
    {
        if (session()->get('level') <> 2) {
            return redirect()->to('/dashboard');
        }
        $data = [
            'title' => 'Pengumuman Kelulusan'
        ];
        return view('auth/kelulusan/index', $data);
    }

    public function getkelulusan()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'title' => 'Pengumuman Kelulusan',
            ];
            $msg = [
                'data' => view('auth/kelulusan/list', $data)
            ];
            echo json_encode($msg);
        }
    }


    public function getdatakelulusan()
    {
        $request = Services::request();
        $datamodel = $this->kelulusan;
        if ($request->getMethod()) {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;

                $row = [];
                $edit = "<button type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"edit('" . $list->kelulusan_id . "')\">
                <i class=\"fa fa-edit\"></i>
            </button>";
                $hapus = "<button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"hapus('" . $list->kelulusan_id . "')\">
                <i class=\"fa fa-trash\"></i>
            </button>";

                $row[] = "<input type=\"checkbox\" name=\"kelulusan_id[]\" class=\"centangKelulusanid\" value=\"$list->kelulusan_id\">";
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->no_ujian;
                $row[] = $list->jurusan;
                $row[] = $list->mapel;
                if ($list->keterangan == 'LULUS') {
                    $row[] =    "<span class=\"badge badge-success\">$list->keterangan</span>";
                } elseif ($list->keterangan == 'TUNDA') {
                    $row[] =    "<span class=\"badge badge-warning\">$list->keterangan</span>";
                } else {
                    $row[] =    "<span class=\"badge badge-danger\">$list->keterangan</span>";
                }
                $row[] = $edit . " " . $hapus;
                $data[] = $row;
            }
            $output = [
                "recordTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];

            echo json_encode($output);
        }
    }

    public function formkelulusan()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'title' => 'Tambah Data Kelulusan',
                'siswa' => $this->siswa->getkelas()
            ];
            $msg = [
                'data' => view('auth/kelulusan/tambah', $data)
            ];
            echo json_encode($msg);
        }
    }

    public function simpankelulusan()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'siswa_id' => [
                    'label' => 'Nama Siswa',
                    'rules' => 'required|is_unique[kelulusan.siswa_id]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'is_unique' => '{field} tidak boleh sama',
                    ]
                ],
                'no_ujian' => [
                    'label' => 'Nomor Ujian',
                    'rules' => 'required|is_unique[kelulusan.no_ujian]|min_length[10]|alpha_dash',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'is_unique' => '{field} tidak boleh sama',
                        'alpha_dash' => '{field} harus angka!',
                        'min_length' => '{field} minimal 10 digit',
                    ]
                ],
                'jurusan' => [
                    'label' => 'Jurusan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'mapel' => [
                    'label' => 'Mapel',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'keterangan' => [
                    'label' => 'Keterangan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'siswa_id' => $validation->getError('siswa_id'),
                        'no_ujian' => $validation->getError('no_ujian'),
                        'jurusan' => $validation->getError('jurusan'),
                        'mapel' => $validation->getError('mapel'),
                        'keterangan' => $validation->getError('keterangan'),

                    ]
                ];
            } else {
                $simpandata = [
                    'siswa_id' => $this->request->getVar('siswa_id'),
                    'no_ujian'    => $this->request->getVar('no_ujian'),
                    'jurusan'    => $this->request->getVar('jurusan'),
                    'mapel'    => $this->request->getVar('mapel'),
                    'keterangan'    => $this->request->getVar('keterangan'),
                ];

                $this->kelulusan->insert($simpandata);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function formeditkelulusan()
    {
        if ($this->request->isAJAX()) {
            $kelulusan_id = $this->request->getVar('kelulusan_id');
            $list =  $this->kelulusan->find($kelulusan_id);
            $siswa =  $this->siswa->list();
            $data = [
                'title'             => 'Edit Data Kelulusan',
                'siswa'             => $siswa,
                'kelulusan_id'        => $list['kelulusan_id'],
                'siswa_id'        => $list['siswa_id'],
                'no_ujian'           => $list['no_ujian'],
                'jurusan'          => $list['jurusan'],
                'mapel'             => $list['mapel'],
                'keterangan'       => $list['keterangan'],
            ];
            $msg = [
                'sukses' => view('auth/kelulusan/edit', $data)
            ];
            echo json_encode($msg);
        }
    }

    public function updatekelulusan()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'no_ujian' => [
                    'label' => 'Nomor Ujian',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'numeric' => '{field} harus angka!',
                    ]
                ],
                'jurusan' => [
                    'label' => 'Jurusan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'mapel' => [
                    'label' => 'Mapel',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'keterangan' => [
                    'label' => 'Keterangan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'siswa_id' => $validation->getError('siswa_id'),
                        'no_ujian' => $validation->getError('no_ujian'),
                        'jurusan' => $validation->getError('jurusan'),
                        'mapel' => $validation->getError('mapel'),
                        'keterangan' => $validation->getError('keterangan'),
                    ]
                ];
            } else {
                $updatedata = [
                    'siswa_id' => $this->request->getVar('siswa_id'),
                    'no_ujian'    => $this->request->getVar('no_ujian'),
                    'jurusan'    => $this->request->getVar('jurusan'),
                    'mapel'    => $this->request->getVar('mapel'),
                    'keterangan'    => $this->request->getVar('keterangan'),
                ];

                $kelulusan_id = $this->request->getVar('kelulusan_id');
                $this->kelulusan->update($kelulusan_id, $updatedata);
                $msg = [
                    'sukses' => 'Data berhasil diupdate'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function hapuskelulusan()
    {
        if ($this->request->isAJAX()) {

            $kelulusan_id = $this->request->getVar('kelulusan_id');

            $this->kelulusan->delete($kelulusan_id);
            $msg = [
                'sukses' => 'Data Berhasil Dihapus'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusallkelulusan()
    {
        if ($this->request->isAJAX()) {
            $kelulusan_id = $this->request->getVar('kelulusan_id');
            $jmldata = count($kelulusan_id);
            for ($i = 0; $i < $jmldata; $i++) {
                $this->kelulusan->delete($kelulusan_id[$i]);
            }

            $msg = [
                'sukses' => "$jmldata Data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }
    // --- TAMBAHKAN KODE INI DI BAGIAN BAWAH CONTROLLER PENGUMUMAN ---

    public function import_excel()
{
    $file = $this->request->getFile('file_excel');
    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('alert', 'File tidak valid atau tidak ditemukan.');
    }

    // 1. Baca mentah seluruh isi file
    $raw_content = file_get_contents($file->getTempName());
    
    // 2. Bersihkan karakter aneh (BOM) dan pastikan encoding ke UTF-8
    $raw_content = str_replace("\xEF\xBB\xBF", "", $raw_content);
    
    // 3. Pecah baris secara manual (mendukung semua format: Windows, Mac, Linux)
    $lines = preg_split('/\r\n|\r|\n/', $raw_content);
    
    // Hapus data lama agar tidak duplikat
    $this->db->table('kelulusan')->truncate();

    $berhasil = 0;
    foreach ($lines as $index => $line) {
        // Lewati baris kosong atau baris header (1-4)
        if (empty(trim($line)) || $index < 4) {
            continue;
        }

        // 4. Pecah kolom (Gunakan explode agar lebih pasti jika str_getcsv gagal)
        // Kita coba titik koma dulu sesuai file template Bapak
        $data_row = explode(";", $line);

        // Jika ternyata pemisahnya koma, coba pecah lagi
        if (count($data_row) <= 1) {
            $data_row = explode(",", $line);
        }

        // 5. Ambil data berdasarkan urutan kolom di CSV
        // Kolom 0=NO, 1=NAMA, 2=NISN, 3=NIS, 4=TEMPAT LAHIR, 5=TGL LAHIR, 6=KURIKULUM
        $nama = isset($data_row[1]) ? trim($data_row[1]) : '';
        
        // Validasi: Jika nama kosong atau hanya berisi teks header, jangan masukkan
        if (empty($nama) || $nama == 'NAMA SISWA' || $nama == 'NAMA') {
            continue;
        }

        $insert_data = [
            'nama'          => $nama,
            'nisn'          => isset($data_row[2]) ? trim($data_row[2]) : '',
            'nis'           => isset($data_row[3]) ? trim($data_row[3]) : '',
            'tempat_lahir'  => isset($data_row[4]) ? trim($data_row[4]) : '',
            'tanggal_lahir' => isset($data_row[5]) ? trim($data_row[5]) : '',
            'kurikulum'     => isset($data_row[6]) ? trim($data_row[6]) : '',
            'no_ujian'      => isset($data_row[2]) ? trim($data_row[2]) : '', // Default ke NISN
            'keterangan'    => 'LULUS',
            'tgl_upload'    => date('Y-m-d H:i:s')
        ];

        if ($this->db->table('kelulusan')->insert($insert_data)) {
            $berhasil++;
        }
    }

    if ($berhasil > 0) {
        return redirect()->to(base_url('auth/kelulusan/manage'))->with('status', "Sukses! $berhasil data berhasil diimport.");
    } else {
        return redirect()->back()->with('alert', "Gagal! Sistem membaca file tapi tidak menemukan data siswa. Pastikan mulai baris ke-5 ada isinya.");
    }
}
}
