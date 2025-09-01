<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Guru;

class GuruModel extends Model
{
   protected $allowedFields = [
      'nuptk',
      'nama_guru',
      'jenis_kelamin',
      'alamat',
      'no_hp',
      'unique_code',
      'status_guru',
      'jabatan',
      'tempat_lahir',
      'tanggal_lahir',
      'foto'
   ];

   protected $table = 'tb_guru';

   protected $primaryKey = 'id_guru';

   public function cekGuru(string $unique_code)
   {
      return $this->where(['nuptk' => $unique_code])->first();
   }

   public function getAllGuru()
   {
      return $this->orderBy('nama_guru')->findAll();
   }

   public function getGuruById($id)
   {
      return $this->where([$this->primaryKey => $id])->first();
   }

   public function createGuru($nuptk, $nama, $jenisKelamin, $alamat, $noHp, $tempat_lahir, $tanggal_lahir, $status_guru, $jabatan, $foto="")
   {
      return $this->save([
         'nuptk' => $nuptk,
         'nama_guru' => $nama,
         'jenis_kelamin' => $jenisKelamin,
         'alamat' => $alamat,
         'no_hp' => $noHp,
         'tempat_lahir'=>$tempat_lahir,
         'tanggal_lahir'=>$tanggal_lahir,
         'status_guru'=>$status_guru,
         'jabatan'=>$jabatan,
         'unique_code' => sha1($nama . md5($nuptk . $nama . $noHp)) . substr(sha1($nuptk . rand(0, 100)), 0, 24),
         'foto'=>$foto
      ]);
   }

   public function updateGuru($id, $nuptk, $nama, $jenisKelamin, $alamat, $noHp, $tempat_lahir, $tanggal_lahir, $status_guru, $jabatan, $foto)
   {
      return $this->save([
         $this->primaryKey => $id,
         'nuptk' => $nuptk,
         'nama_guru' => $nama,
         'jenis_kelamin' => $jenisKelamin,
         'alamat' => $alamat,
         'no_hp' => $noHp,
         'tempat_lahir'=>$tempat_lahir,
         'tanggal_lahir'=>$tanggal_lahir,
         'status_guru'=>$status_guru,
         'jabatan'=>$jabatan,
         'foto'=>$foto
      ]);
   }
}
