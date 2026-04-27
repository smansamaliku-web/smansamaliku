<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelspp extends Model
{
    protected $table      = 'spp';
    protected $primaryKey = 'spp_id';
    protected $allowedFields = ['siswa_id', 'bulan', 'tahun', 'nominal', 'status'];
    protected $column_order = array('nis', 'nama', 'nama_kelas');
    protected $column_search = array('nis', 'nama', 'nama_kelas');
    protected $order = array('spp_id' => 'asc');

    // ✅ Constructor TIDAK memerlukan parameter
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    private function _get_datatables_query()
    {
        $request = service('request');
        $i = 0;
        
        foreach ($this->column_search as $item) {
            $searchValue = $request->getPost('search')['value'] ?? '';
            if ($searchValue) {
                if ($i === 0) {
                    $this->builder()->groupStart();
                    $this->builder()->like($item, $searchValue);
                } else {
                    $this->builder()->orLike($item, $searchValue);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->builder()->groupEnd();
                }
            }
            $i++;
        }

        if ($request->getPost('order')) {
            $this->builder()->orderBy(
                $this->column_order[$request->getPost('order')[0]['column']], 
                $request->getPost('order')[0]['dir']
            );
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->builder()->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $request = service('request');
        $this->_get_datatables_query();
        
        if ($request->getPost('length') != -1) {
            $this->builder()->limit($request->getPost('length'), $request->getPost('start'));
        }
        
        return $this->builder()
            ->join('siswa', 'siswa.siswa_id = spp.siswa_id')
            ->join('kelas', 'kelas.kelas_id = siswa.kelas_id')
            ->get()
            ->getResult();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->builder()
            ->join('siswa', 'siswa.siswa_id = spp.siswa_id')
            ->join('kelas', 'kelas.kelas_id = siswa.kelas_id')
            ->countAllResults();
    }

    public function count_all()
    {
        return $this->db->table($this->table)->countAllResults();
    }

    public function list()
    {
        return $this->table('spp')
            ->join('siswa', 'siswa.siswa_id = spp.siswa_id')
            ->join('kelas', 'kelas.kelas_id = siswa.kelas_id')
            ->orderBy('spp_id', 'ASC')
            ->get()
            ->getResultArray();
    }
}