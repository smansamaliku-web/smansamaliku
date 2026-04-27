<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelsiswa extends Model
{
    protected $table      = 'siswa';
    protected $primaryKey = 'siswa_id';
    protected $allowedFields = ['nis', 'nama', 'kelas_id', 'alamat', 'tgl_lahir', 'tmp_lahir', 'jenkel'];
    protected $column_order = array(null, null, 'nis', 'nama', 'kelas_id', 'alamat', 'tgl_lahir', 'tmp_lahir', 'jenkel', null);
    protected $column_search = array('nis', 'nama');
    protected $order = array('nis' => 'asc');

    public function list()
    {
        return $this->table('siswa')
            ->join('kelas', 'kelas.kelas_id = siswa.kelas_id')
            ->orderBy('siswa_id', 'ASC')
            ->get()->getResultArray();
    }

    public function getkelas()
    {
        return $this->table('siswa')
            ->join('kelas', 'kelas.kelas_id = siswa.kelas_id')
            ->like('nama_kelas', 'XII')
            ->orderBy('siswa_id', 'ASC')
            ->get()->getResultArray();
    }

    private function _get_datatables_query()
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($this->request->getPost('search')) {
                $searchValue = $this->request->getPost('search')['value'];
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
            }
            $i++;
        }

        if ($this->request->getPost('order')) {
            $this->builder()->orderBy($this->column_order[$this->request->getPost('order')[0]['column']], $this->request->getPost('order')[0]['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->builder()->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($this->request->getPost('length') != -1) {
            $this->builder()->limit($this->request->getPost('length'), $this->request->getPost('start'));
        }
        return $this->builder()->get()->getResult();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->builder()->countAllResults();
    }

    public function count_all()
    {
        return $this->db->table($this->table)->countAllResults();
    }
}