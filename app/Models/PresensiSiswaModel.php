<?php

namespace App\Models;

use App\Models\PresensiInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use App\Libraries\enums\Kehadiran;

class PresensiSiswaModel extends Model implements PresensiInterface
{
   protected $primaryKey = 'id_presensi';

   protected $allowedFields = [
      'id_siswa',
      'kode_kelas',
      'tanggal',
      'jam_masuk',
      'jam_keluar',
      'id_kehadiran',
      'keterangan',
      'th_ajar'
   ];

   protected $table = 'tb_presensi_siswa';

   public function cekAbsen(string|int $id, string|Time $date)
   {
      $result = $this->where(['id_siswa' => $id, 'tanggal' => $date])->first();

      if (empty($result)) return false;

      return $result[$this->primaryKey];
   }

   public function absenMasuk(string $id,  $date, $time, $idKelas = '',$thajar='')
   {
      $this->save([
         'id_siswa' => $id,
         'kode_kelas' => $idKelas,
         'tanggal' => $date,
         'jam_masuk' => $time,
         // 'jam_keluar' => '',
         'id_kehadiran' => Kehadiran::Hadir->value,
         'keterangan' => '',
         'th_ajar'=>$thajar,
      ]);
   }

   public function absenKeluar(string $id, $time)
   {
      $this->update($id, [
         'jam_keluar' => $time,
         'keterangan' => ''
      ]);
   }

   public function getPresensiByIdSiswaTanggal($idSiswa, $date)
   {
      return $this->where(['id_siswa' => $idSiswa, 'tanggal' => $date])->first();
   }

   public function getPresensiById(string $idPresensi)
   {
      return $this->where([$this->primaryKey => $idPresensi])->first();
   }

   public function getPresensiByKelasTanggal($idKelas, $tanggal,$thajar)
   {
      // return $this->setTable('tb_siswa')
      //    ->select('*')
      //    ->join(
      //       "(SELECT id_presensi, 
      //       id_siswa AS id_siswa_presensi, 
      //       tanggal, 
      //       jam_masuk, 
      //       jam_keluar, 
      //       id_kehadiran, 
      //       keterangan FROM tb_presensi_siswa)tb_presensi_siswa",
      //       "{$this->table}.id_siswa = tb_presensi_siswa.id_siswa_presensi AND tb_presensi_siswa.tanggal = '$tanggal'",
      //       'left'
      //    )
      //    ->join(
      //       'tb_kehadiran',
      //       'tb_presensi_siswa.id_kehadiran = tb_kehadiran.id_kehadiran',
      //       'left'
      //    )->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')
      //    ->join('tb_kelas','tb_kelas.kode = tb_siswa_trans.kode_kelas','LEFT')
      //    ->where("tb_kelas.id_kelas= $idKelas")
      //    ->orderBy("nama_siswa")
      //    ->findAll();

      $query = $this->select([
         'tb_siswa.nis',
         'tb_siswa.nama_siswa',
         'tb_kehadiran.kehadiran',
         'tb_presensi_siswa.*'
      ])
      ->join('tb_siswa','tb_siswa.id_siswa = tb_presensi_siswa.id_siswa','LEFT')      
      ->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_presensi_siswa.id_siswa','LEFT')
      ->join('tb_kehadiran','tb_kehadiran.id_kehadiran = tb_presensi_siswa.id_kehadiran','LEFT')
      ->where('DATE(tb_presensi_siswa.tanggal)',$tanggal)
      ->where('tb_siswa_trans.kode_kelas',$idKelas)
      ->where('tb_siswa_trans.th_ajar',$thajar)
      ->orderBy('tb_siswa.nama_siswa','ASC')
      ->findAll();

      return $query;
   }


   public function getPresensiByKelasTanggal2($idKelas, $tanggal, $thajar){
      return $this->setTable('tb_siswa')
         ->select('*')
         ->join(
            "(SELECT id_presensi, 
            id_siswa AS id_siswa_presensi, 
            tanggal, 
            jam_masuk, 
            jam_keluar, 
            id_kehadiran, 
            keterangan FROM tb_presensi_siswa)tb_presensi_siswa",
            "{$this->table}.id_siswa = tb_presensi_siswa.id_siswa_presensi AND tb_presensi_siswa.tanggal = '$tanggal'",
            'left'
         )
         ->join(
            'tb_kehadiran',
            'tb_presensi_siswa.id_kehadiran = tb_kehadiran.id_kehadiran',
            'left'
         )->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')
         ->join('tb_kelas','tb_kelas.kode = tb_siswa_trans.kode_kelas','LEFT')
         ->where("tb_kelas.id_kelas= $idKelas")
         ->orderBy("nama_siswa")
         ->findAll();
   }

   public function getPresensiByKehadiran(string $idKehadiran, $tanggal)
   {
      $this->join(
         'tb_siswa',
         "tb_presensi_siswa.id_siswa = tb_siswa.id_siswa AND tb_presensi_siswa.tanggal = '$tanggal'",
         'right'
      );

      if ($idKehadiran == '4') {
         $result = $this->findAll();

         $filteredResult = [];

         foreach ($result as $value) {
            if ($value['id_kehadiran'] != ('1' || '2' || '3')) {
               array_push($filteredResult, $value);
            }
         }

         return $filteredResult;
      } else {
         $this->where(['tb_presensi_siswa.id_kehadiran' => $idKehadiran]);
         return $this->findAll();
      }
   }

   public function updatePresensi(
      $idPresensi,
      $idSiswa,
      $idKelas,
      $tanggal,
      $idKehadiran,
      $jamMasuk,
      $jamKeluar,
      $keterangan
   ) {
      $presensi = $this->getPresensiByIdSiswaTanggal($idSiswa, $tanggal);

      $data = [
         'id_siswa' => $idSiswa,
         'id_kelas' => $idKelas,
         'tanggal' => $tanggal,
         'id_kehadiran' => $idKehadiran,
         'keterangan' => $keterangan ?? $presensi['keterangan'] ?? ''
      ];

      if ($idPresensi != null) {
         $data[$this->primaryKey] = $idPresensi;
      }

      if ($jamMasuk != null) {
         $data['jam_masuk'] = $jamMasuk;
      }

      if ($jamKeluar != null) {
         $data['jam_keluar'] = $jamKeluar;
      }

      return $this->save($data);
   }
}
