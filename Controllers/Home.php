<?php

namespace App\Controllers;
use Dompdf\Dompdf;
use Dompdf\Options;

class Home extends BaseController
{
	public function index()
	{
		$staf = $this->staf->selectCount('staf_id')->first();
		$guru = $this->guru->selectCount('guru_id')->first();
		$siswa = $this->siswa->selectCount('siswa_id')->first();
		$kelas = $this->kelas->selectCount('kelas_id')->first();
		$konfigurasi = $this->konfigurasi->orderBy('konfigurasi_id')->first();
		$berita = $this->berita->published();
		$list_staf = $this->staf->orderBy('staf_id')->get()->getResultArray();
		$gallery = $this->gallery->list();
		$kategori = $this->kategori->list();
		$data = [
			'title' => 'SMANSA MALIKU!',
			'staf' => $staf,
			'guru' => $guru,
			'siswa' => $siswa,
			'kelas' => $kelas,
			'konfigurasi' => $konfigurasi,
			'berita' => $berita,
			'list_staf' => $list_staf,
			'gallery' => $gallery,
			'kategori' => $kategori,
		];
		return view('front/layout/menu', $data);
	}

	public function detail_berita($slug_berita = null)
	{
		if (!isset($slug_berita)) return redirect()->to('/home#berita');
		$konfigurasi = $this->konfigurasi->orderBy('konfigurasi_id')->first();
		$berita = $this->berita->detail_berita($slug_berita);
		$kategori = $this->kategori->list();
		if ($berita) {
			$data = [
				'title'  => 'Berita - ' . $berita->judul_berita,
				'konfigurasi' => $konfigurasi,
				'berita' => $berita,
				'kategori' => $kategori,
			];
			return view('front/berita/detail', $data);
		} else {
			return redirect()->to('/home#berita');
		}
	}

	public function detail_gallery($id = null)
	{
		if (!isset($id)) return redirect()->to('/home#gallery');
		$konfigurasi = $this->konfigurasi->orderBy('konfigurasi_id')->first();
		$gallery = $this->gallery->detail_gallery($id);
		$list_foto = $this->foto->detail_foto($id);
		$kategori = $this->kategori->list();
		if ($gallery) {
			$data = [
				'title'  => 'Gallery - ' . $gallery->nama_gallery,
				'konfigurasi' => $konfigurasi,
				'gallery' => $gallery,
				'list_foto' => $list_foto,
				'kategori' => $kategori,
			];
			return view('front/gallery/detail', $data);
		} else {
			return redirect()->to('/home#gallery');
		}
	}

	public function cekspp()
	{
		$konfigurasi = $this->konfigurasi->orderBy('konfigurasi_id')->first();
		$kategori = $this->kategori->list();
		$data = [
			'title' => 'Cek SPP',
			'konfigurasi' => $konfigurasi,
			'kategori' => $kategori,
		];
		return view('front/spp/list', $data);
	}

	public function searchspp()
	{
		$keyword  = $this->request->getVar('keyword');
		if (!isset($keyword)) return redirect()->to('cekspp');
		$check = $this->spp->get_spp_keyword($keyword);
		if ($check) {

			$konfigurasi = $this->konfigurasi->orderBy('konfigurasi_id')->first();
			$kategori = $this->kategori->list();
			$data = [
				'title' => 'Cek SPP',
				'konfigurasi' => $konfigurasi,
				'kategori' => $kategori,
				'spp'	=> $check,
			];
			return view('front/spp/search', $data);
		} else {
			session()->setFlashdata('alert', 'Nis yang anda masukkan tidak terdaftar!');
			return redirect()->to('cekspp');
		}
	}


}
