<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

class Kelulusan extends BaseController
{
    // --- 1. TAMPILAN HALAMAN CARI (Siswa) ---
    public function index()
    {
        $data = [
            'title'       => 'Pengumuman Kelulusan',
            'konfigurasi' => $this->konfigurasi->orderBy('konfigurasi_id')->first(),
            'kategori'    => $this->kategori->list(),
        ];
        return view('front/kelulusan/list', $data);
    }

    // --- 2. PROSES PENCARIAN (Siswa) ---
    public function search()
    {
        $keyword = $this->request->getVar('keyword');
        if (empty($keyword)) return redirect()->to('kelulusan');
        
        $check = $this->kelulusan->get_kelulusan_keyword($keyword);
        if ($check) {
            $data = [
                'title'       => 'Hasil Pencarian Kelulusan',
                'konfigurasi' => $this->konfigurasi->orderBy('konfigurasi_id')->first(),
                'kategori'    => $this->kategori->list(),
                'kelulusan'   => $check,
            ];
            return view('front/kelulusan/search', $data);
        } else {
            session()->setFlashdata('alert', 'Data tidak ditemukan! Silakan cek kembali nomor Anda.');
            return redirect()->to('kelulusan');
        }
    }

    // --- 3. HALAMAN MANAGEMENT ADMIN (SOLUSI ERROR 404) ---
    public function admin_index()
    {
        $data = [
            'title'       => 'Manajemen Data Kelulusan',
            'konfigurasi' => $this->konfigurasi->orderBy('konfigurasi_id')->first(),
            'kelulusan'   => $this->kelulusan->orderBy('kelulusan_id', 'DESC')->findAll(),
        ];
        // Sesuaikan folder view backend Bapak, biasanya di folder backend
        return view('auth/kelulusan/index', $data); 
    }

    // --- 4. FUNGSI LOGO ---
    private function get_logos()
    {
        $logos = ['sekolah' => '', 'provinsi' => ''];
        
        $path_sekolah = FCPATH . 'img/konfigurasi/logo/logo_sekolah_skl.jpg';
        if (file_exists($path_sekolah)) {
            $data_img = file_get_contents($path_sekolah);
            $logos['sekolah'] = 'data:image/' . pathinfo($path_sekolah, PATHINFO_EXTENSION) . ';base64,' . base64_encode($data_img);
        }

        $path_provinsi = FCPATH . 'img/konfigurasi/logo/logo_provinsi_kalteng.png';
        if (file_exists($path_provinsi)) {
            $data_img_prov = file_get_contents($path_provinsi);
            $logos['provinsi'] = 'data:image/' . pathinfo($path_provinsi, PATHINFO_EXTENSION) . ';base64,' . base64_encode($data_img_prov);
        }
        return $logos;
    }

    // --- 5. CETAK SKL ---
    public function download_skl($nis)
    {
        ini_set('memory_limit', '512M');
        $check = $this->kelulusan->get_kelulusan_keyword($nis);

        if ($check) {
            $logos = $this->get_logos();
            $data = [
                'title'         => 'SURAT KETERANGAN LULUS',
                'value'         => $check[0],
                'logo_sekolah'  => $logos['sekolah'],
                'logo_provinsi' => $logos['provinsi']
            ];

            $html = view('front/kelulusan/cetak_skl_pdf', $data);
            return $this->generate_pdf($html, "SKL_" . $nis);
        }
        return redirect()->to('kelulusan');
    }

    // --- 6. CETAK TRANSKRIP ---
    public function download_transkrip($nis)
    {
        ini_set('memory_limit', '512M');
        $check = $this->kelulusan->get_kelulusan_keyword($nis);

        if ($check) {
            $logos = $this->get_logos();
            $data = [
                'title'         => 'TRANSKRIP NILAI',
                'value'         => $check[0],
                'logo_sekolah'  => $logos['sekolah'],
                'logo_provinsi' => $logos['provinsi']
            ];

            $html = view('front/kelulusan/cetak_transkrip_pdf', $data);
            return $this->generate_pdf($html, "TRANSKRIP_" . $nis);
        }
        return redirect()->to('kelulusan');
    }

    // --- FUNGSI GENERATE PDF ---
    private function generate_pdf($html, $filename)
    {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        if (ob_get_length()) ob_end_clean();
        return $dompdf->stream($filename . ".pdf", ["Attachment" => 1]);
    }

    // --- 7. IMPORT EXCEL (Disesuaikan dengan file Leger) ---
   public function import_excel()
{
    $file = $this->request->getFile('file_excel');
    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('alert', 'File tidak valid');
    }

    // 1. Ambil konten file
    $content = file_get_contents($file->getTempName());
    
    // 2. Hapus BOM (karakter aneh) jika ada
    $content = str_replace("\xEF\xBB\xBF", '', $content);
    
    // 3. Pecah berdasarkan baris (mendukung format Windows & Linux)
    $lines = preg_split('/\r\n|\r|\n/', $content);
    
    $berhasil = 0;
    $this->db->table('kelulusan')->truncate();

    foreach ($lines as $index => $line) {
        if (empty(trim($line))) continue;

        // 4. Coba pecah dengan titik koma (;)
        $data_row = str_getcsv($line, ";");
        
        // 5. Jika gagal (hanya 1 kolom), coba pecah dengan koma (,)
        if (count($data_row) <= 1) {
            $data_row = str_getcsv($line, ",");
        }

        // Lewati 4 baris header (Baris 0,1,2,3)
        if ($index < 4) continue;
        
        // Pastikan kolom Nama (indeks 1) tidak kosong
        if (!isset($data_row[1]) || empty(trim($data_row[1]))) continue;

        $data = [
            'nama'          => trim($data_row[1]),
            'nisn'          => trim($data_row[2] ?? ''),
            'nis'           => trim($data_row[3] ?? ''),
            'tempat_lahir'  => trim($data_row[4] ?? ''),
            'tanggal_lahir' => trim($data_row[5] ?? ''),
            'kurikulum'     => trim($data_row[6] ?? ''),
            'no_ujian'      => trim($data_row[2] ?? ''), 
            'keterangan'    => 'LULUS',
            'tgl_upload'    => date('Y-m-d H:i:s')
        ];

        if ($this->db->table('kelulusan')->insert($data)) {
            $berhasil++;
        }
    }

    return redirect()->to(base_url('auth/kelulusan/manage'))->with('status', "Berhasil: $berhasil data.");
}
}