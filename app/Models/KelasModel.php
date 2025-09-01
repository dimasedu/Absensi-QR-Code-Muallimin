<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
   protected $DBGroup          = 'default';
   protected $useAutoIncrement = true;
   protected $returnType       = 'array';
   protected $useSoftDeletes   = true;
   protected $protectFields    = true;
   protected $allowedFields    = ['kelas', 'kode','id_jurusan','id_walikelas'];

   protected $table = 'tb_kelas';

   protected $primaryKey = 'id_kelas';

   public function getAllKelas()
   {
      return $this->join('tb_jurusan', 'tb_kelas.id_jurusan = tb_jurusan.id', 'left')->findAll();
   }

   public function tambahKelas($kode, $kelas, $idJurusan,$idWali)
   {
      return $this->db->table($this->table)->insert([
         'kode'=>$kode,
         'kelas' => $kelas,
         'id_jurusan' => $idJurusan,
         'id_walikelas'=>$idWali
      ]);
   }

   public function getById($id)
   {
      return $this->select(['tb_kelas.*','tb_jurusan.jurusan'])
      ->join('tb_jurusan', 'tb_kelas.id_jurusan = tb_jurusan.id', 'left')
      ->where('id_kelas',$id)
      ->first();
   }
}
