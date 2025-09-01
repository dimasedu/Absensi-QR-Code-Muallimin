<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\PetugasModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Myth\Auth\Password;

class DataPetugas extends BaseController
{
   protected PetugasModel $petugasModel;

   protected $petugasValidationRules = [
      'email' => [
         'rules' => 'required|is_unique[users.email]',
         'errors' => [
            'required' => 'Email harus diisi.',
            'is_unique' => 'Email ini telah terdaftar.'
         ]
      ],
      'username' => [
         'rules' => 'required|min_length[5]|is_unique[users.username]',
         'errors' => [
            'required' => 'Username harus diisi',
            'is_unique' => 'Username ini telah terdaftar.',
         ]
      ],
      'password' => [
         'rules' => 'permit_empty|min_length[6]',
      ],
      'role' => [
         'rules' => 'required',
         'errors' => [
            'required' => 'Role wajib diisi'
         ]
      ]
   ];


   public function __construct()
   {
      $this->petugasModel = new PetugasModel();
      $this->session = \Config\Services::session();
   }

   public function index()
   {
      if (user()->toArray()['is_superadmin'] != '1') {
         return redirect()->to('admin');
      }

      $data = [
         'title' => 'Data Petugas',
         'ctx' => 'petugas'
      ];

      return view('admin/petugas/data-petugas', $data);
   }

   public function ambilDataPetugas()
   {
      $petugas = $this->petugasModel->getAllPetugas();

      $data = [
         'data' => $petugas,
         'empty' => empty($petugas)
      ];

      return view('admin/petugas/list-data-petugas', $data);
   }

   public function registerPetugas()
   {
      if (user()->toArray()['is_superadmin'] != '1') {
         return redirect()->to('admin');
      }

      $data = [
         'title' => 'Register Petugas',
         'ctx' => 'petugas'
      ];

      return view('admin/petugas/register', $data);
   }


   public function store(){
      // if (!$this->validate($this->petugasValidationRules)) {
      //    session()->setFlashdata([
      //       'msg' => 'Terjadi Kesalahan',
      //       'error' => true,
      //       'errors'=>$this->validator
      //    ]);

      //    return redirect()->back()->withInput();
      // } else {


         $email = $this->request->getVar('email');
         $username = $this->request->getVar('username');
         $passwordHash = Password::hash($this->request->getVar('password'));
         $role = $this->request->getVar('role');

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

         $simpan = $this->petugasModel->insert([
            'email' => $email,
            'username' => $username,
            'password_hash' => $passwordHash,
            'is_superadmin' => $is_admin,
            'is_operator'=>$is_operator,
            'active'=>1
         ]);

         session()->setFlashdata([
            'msg' => 'Tambah Data Petugas berhasil',
            'error' => false
         ]);

         return redirect()->to('admin/petugas');
      // }
   }


   public function formEditPetugas($id)
   {
      $petugas = $this->petugasModel->getPetugasById($id);

      if (empty($petugas)) {
         throw new PageNotFoundException('Data petugas dengan id ' . $id . ' tidak ditemukan');
      }

      $data = [
         'data' => $petugas,
         'ctx' => 'petugas',
         'title' => 'Edit Data Petugas',
      ];

      return view('admin/petugas/edit-data-petugas', $data);
   }
   

   public function updatePetugas()
   {
      $idPetugas = $this->request->getVar('id');

      $petugasLama = $this->petugasModel->getPetugasById($idPetugas);

      // if ($petugasLama['username'] != $this->request->getVar('username')) {
      //    $this->petugasValidationRules['username']['rules'] = 'required|is_unique[users.username]';
      // }

      // if ($petugasLama['email'] != $this->request->getVar('email')) {
      //    $this->petugasValidationRules['email']['rules'] = 'required|is_unique[users.email]';
      // }

      // // validasi
      // if (!$this->validate($this->petugasValidationRules)) {
      //    $data = [
      //       'data' => $this->petugasModel->getPetugasById($idPetugas),
      //       'ctx' => 'petugas',
      //       'title' => 'Edit Data Petugas',
      //       'validation' => $this->validator,
      //       'oldInput' => $this->request->getVar()
      //    ];
      //    return view('admin/petugas/edit-data-petugas', $data);
      // }

      $password = $this->request->getVar('password') ?? false;

      $email = $this->request->getVar('email');
      $username = $this->request->getVar('username');
      $passwordHash = $password ? Password::hash($password) : $petugasLama['password_hash'];
      $role = $this->request->getVar('role');

      $result = $this->petugasModel->savePetugas($idPetugas, $email, $username, $passwordHash, $role);

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Edit data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/petugas');
      }

      session()->setFlashdata([
         'msg' => 'Gagal mengubah data',
         'error' => true
      ]);
      return redirect()->to('/admin/petugas/edit/' . $idPetugas);
   }

   public function delete($id)
   {
      $result = $this->petugasModel->delete($id);

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Data berhasil dihapus',
            'error' => false
         ]);
         return redirect()->to('/admin/petugas');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menghapus data',
         'error' => true
      ]);
      return redirect()->to('/admin/petugas');
   }
}
