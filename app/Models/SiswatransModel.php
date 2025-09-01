<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswatransModel extends Model
{
    protected $table            = 'tb_siswa_trans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kode_kelas','id_siswa','tipe','th_ajar'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    
    public function getAllSiswaWithKelas($kelas, $thajar)
    {
        $query = $this->select([
            'tb_siswa_trans.th_ajar',
            'tb_siswa.id_siswa',
            'tb_siswa.nis',
            'tb_siswa.nama_siswa',
            'tb_siswa.jenis_kelamin',
            'tb_siswa.no_hp',
            'tb_siswa.foto',
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'])
        ->join(
            'tb_siswa',
            'tb_siswa_trans.id_siswa = tb_siswa.id_siswa',
            )      
        ->join(
            'tb_kelas',
            'tb_kelas.kode = tb_siswa_trans.kode_kelas',
        )->join(
            'tb_jurusan',
            'tb_kelas.id_jurusan = tb_jurusan.id',
        )
        ->where('tb_siswa_trans.th_ajar',$thajar)
        ->where('tb_siswa_trans.tipe !=','lulus')
        ->where('tb_siswa.aktif','Y');


        if ($kelas != "all") {
            $query = $this->where(['tb_siswa_trans.kode_kelas' => $kelas]);
        } else {
            $query = $this;
        }

        return $query->orderBy('nama_siswa')->findAll();
    }
}
