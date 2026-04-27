<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelkelulusan extends Model
{
    protected $table      = 'kelulusan';
    protected $primaryKey = 'kelulusan_id';

    protected $allowedFields = [
        'siswa_id', 'nama', 'nisn', 'nis', 'tempat_lahir', 
        'tanggal_lahir', 'kurikulum', 'no_ujian', 'jurusan', 
        'mapel', 'keterangan', 'tgl_upload'
    ];

    protected $column_order  = [null, 'kelulusan_id', 'nama', 'nisn', 'nis', 'no_ujian', 'keterangan', null];
    protected $column_search = ['nama', 'nisn', 'nis', 'no_ujian', 'keterangan'];
    protected $order         = ['nama' => 'ASC'];

    private function _get_datatables_query($request)
    {
        $builder = $this->builder();

        $i = 0;
        foreach ($this->column_search as $item) {
            $searchValue = $request->getPost('search')['value'] ?? '';
            if ($searchValue) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $searchValue);
                } else {
                    $builder->orLike($item, $searchValue);
                }
                if (count($this->column_search) - 1 == $i) {
                    $builder->groupEnd();
                }
            }
            $i++;
        }

        if ($request->getPost('order')) {
            $builder->orderBy($this->column_order[$request->getPost('order')[0]['column']], $request->getPost('order')[0]['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }

    public function get_datatables($request)
    {
        $builder = $this->_get_datatables_query($request);
        if ($request->getPost('length') != -1) {
            $builder->limit($request->getPost('length'), $request->getPost('start'));
        }
        return $builder->get()->getResult();
    }

    public function count_filtered($request)
    {
        $builder = $this->_get_datatables_query($request);
        return $builder->countAllResults();
    }

    public function count_all()
    {
        return $this->db->table($this->table)->countAllResults();
    }

    public function import_batch($data)
    {
        return $this->db->table($this->table)->insertBatch($data);
    }

    public function hapus_massal($ids)
    {
        return $this->db->table($this->table)->whereIn($this->primaryKey, $ids)->delete();
    }
}