<?php

namespace App\Models;

use CodeIgniter\Model;

class PetugasModel extends Model
{
   protected function initialize()
   {
      $this->allowedFields = [
         'email',
         'username',
         'password_hash',
         'is_superadmin',
         'is_operator',
         'active'
      ];
   }

   protected $table = 'users';

   protected $primaryKey = 'id';

   public function getAllPetugas()
   {
      return $this->findAll();
   }

   public function getPetugasById($id)
   {
      return $this->where([$this->primaryKey => $id])->first();
   }

   public function savePetugas($idPetugas, $email, $username, $passwordHash, $role)
   {
 

         if($role == "admin"){
            $is_admin = 1;
            $is_operator = 0;
         } elseif($role == "opscan"){
            $is_admin = 0;
            $is_operator = 1;
         } else {
            $is_admin = 0;
            $is_operator = 0;
         }

      return $this->save([
         $this->primaryKey => $idPetugas,
         'email' => $email,
         'username' => $username,
         'password_hash' => $passwordHash,
         'is_superadmin' => $is_admin,
         'is_operator'=>$is_operator,
         'active'=>1
      ]);
   }
}
