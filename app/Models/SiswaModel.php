<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
   protected function initialize()
   {
      $this->allowedFields = [
         'nis',
         'nama_siswa',
         'id_kelas',
         'jenis_kelamin',
         'no_hp',
         'unique_code',
         'foto',
         'aktif'
      ];
   }

   protected $table = 'tb_siswa';

   protected $primaryKey = 'id_siswa';

   public function cekSiswa(string $unique_code)
   {
      // $this->select(['
      // tb_siswa.id_siswa','tb_siswa.nis','tb_siswa.nama_siswa','tb_siswa.jenis_kelamin','tb_siswa.no_hp','tb_siswa.foto','tb_kelas.kelas','tb_jurusan.jurusan'])
      // ->join(
      //    'tb_siswa_trans',
      //    'tb_siswa_trans.id_siswa = tb_siswa.id_siswa',
      //    'LEFT')      
      // ->join(
      //    'tb_kelas',
      //    'tb_kelas.kode = tb_siswa_trans.kode_kelas',
      //    'LEFT'
      // )->join(
      //    'tb_jurusan',
      //    'tb_kelas.id_jurusan = tb_jurusan.id',
      //    'LEFT'
      // );

      $this->select(['tb_siswa_trans.id',
            'tb_siswa_trans.th_ajar',
            'tb_siswa_trans.tipe',
            'tb_siswa_trans.kode_kelas',
            'tb_siswa.id_siswa',
            'tb_siswa.nis',
            'tb_siswa.nama_siswa',
            'tb_siswa.jenis_kelamin',
            'tb_siswa.no_hp',
            'tb_siswa.foto',
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'
          ])
            ->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')              
            ->join(
                'tb_kelas',
                'tb_kelas.kode = tb_siswa_trans.kode_kelas',
                'LEFT'
            )->join(
                'tb_jurusan',
                'tb_kelas.id_jurusan = tb_jurusan.id',
                'LEFT'
            );
      return $this->where(['tb_siswa.nis' => $unique_code])->first();
   }

   public function getSiswaById($id)
   {
      $query = $this->select(['
      tb_siswa.id_siswa','tb_siswa.nis','tb_siswa.nama_siswa','tb_siswa.jenis_kelamin','tb_siswa.no_hp','tb_siswa.foto'])
      ->join(
         'tb_siswa_trans',
         'tb_siswa_trans.id_siswa = tb_siswa.id_siswa',
         'LEFT');
      return $this->where(['tb_siswa.id_siswa'=>$id])->first();
   }

   public function getSiswaByIdThAjar($idsiswa, $thajar){
      
      return $this->select(['tb_siswa_trans.id',
      'tb_siswa_trans.th_ajar',
      'tb_siswa_trans.tipe',
      'tb_siswa_trans.kode_kelas',
      'tb_siswa.id_siswa',
      'tb_siswa.nis',
      'tb_siswa.nama_siswa',
      'tb_siswa.jenis_kelamin',
      'tb_siswa.no_hp',
      'tb_siswa.foto',
      'tb_kelas.kelas',
      'tb_jurusan.jurusan'
    ])
      ->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')              
      ->join(
          'tb_kelas',
          'tb_kelas.kode = tb_siswa_trans.kode_kelas',
          'LEFT'
      )->join(
          'tb_jurusan',
          'tb_kelas.id_jurusan = tb_jurusan.id',
          'LEFT'
      )
      ->where('tb_siswa_trans.id_siswa',$idsiswa)
      ->where('tb_siswa_trans.th_ajar',$thajar)
      ->first();
   }

   public function getAllSiswaWithKelas($kelas, $jurusan,$thajar)
   {
      $query = $this->select(['
      DISTINCT(tb_siswa_trans.kode_kelas),
      tb_siswa.id_siswa',
      'tb_siswa.nis',
      'tb_siswa.nama_siswa',
      'tb_siswa.jenis_kelamin',
      'tb_siswa.no_hp',
      'tb_siswa.foto',
      'tb_siswa.unique_code',
      'tb_kelas.kelas',
      'tb_jurusan.jurusan'
      ])
      ->join(
         'tb_siswa_trans',
         'tb_siswa_trans.id_siswa = tb_siswa.id_siswa'
         )  
         ->join(
            'tb_kelas',
            'tb_kelas.kode = tb_siswa_trans.kode_kelas',
            'LEFT'
        )
        ->join(
         'tb_jurusan',
         'tb_kelas.id_jurusan = tb_jurusan.id',
         'LEFT'
     )
      ->where('tb_siswa_trans.th_ajar',$thajar);


      if ($kelas !="all") {
         $query = $this->where(['tb_siswa_trans.kode_kelas' => $kelas]);
      } else {
         $query = $this;
      }

      return $query->orderBy('nama_siswa')->findAll();
   }

   public function getSiswaByKelas($id_kelas,$thajar)
   {
      
      return $this->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')
      ->join(
         'tb_kelas',
         'tb_kelas.kode = tb_siswa_trans.kode_kelas',
         'LEFT'
      )
         ->join('tb_jurusan', 'tb_kelas.id_jurusan = tb_jurusan.id', 'left')
         ->where(['tb_kelas.id_kelas' => $id_kelas,'tb_siswa_trans.th_ajar'=>$thajar])
         ->findAll();
   }

   public function createSiswa($nis, $nama, $jenisKelamin, $noHp, $foto)
   {
      return $this->save([
         'nis' => $nis,
         'nama_siswa' => $nama,
         'jenis_kelamin' => $jenisKelamin,
         'no_hp' => $noHp,
         'unique_code' => sha1($nama . md5($nis . $noHp . $nama)) . substr(sha1($nis . rand(0, 100)), 0, 24),
         'foto'=>$foto,
         'aktif'=>'Y'
      ]);
   }

   public function updateSiswa($id, $nis, $nama, $jenisKelamin, $noHp,$foto)
   {
      if(!empty($foto)){
         return $this->save([
            $this->primaryKey => $id,
            'nis' => $nis,
            'nama_siswa' => $nama,
            'jenis_kelamin' => $jenisKelamin,
            'no_hp' => $noHp,
            'foto'=>$foto
         ]);
      } else {
         return $this->save([
            $this->primaryKey => $id,
            'nis' => $nis,
            'nama_siswa' => $nama,
           
            'jenis_kelamin' => $jenisKelamin,
            'no_hp' => $noHp,
         ]);
      }
      
   }
}
