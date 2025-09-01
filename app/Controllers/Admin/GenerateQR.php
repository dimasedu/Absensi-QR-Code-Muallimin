<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;

class GenerateQR extends BaseController
{
   protected SiswaModel $siswaModel;
   protected KelasModel $kelasModel;

   protected GuruModel $guruModel;

   public function __construct()
   {
      $this->siswaModel = new SiswaModel();
      $this->kelasModel = new KelasModel();

      $this->guruModel = new GuruModel();
      $this->session = \Config\Services::session();
   }

   public function index()
   {
       $thajar = $this->session->get('thajar');
      $siswa = $this->siswaModel->getAllSiswaWithKelas('all','all',$thajar);
      $kelas = $this->kelasModel->getAllKelas();
      $guru = $this->guruModel->getAllGuru();

      $data = [
         'title' => 'Generate QR Code',
         'ctx' => 'qr',
         'siswa' => $siswa,
         'kelas' => $kelas,
         'guru' => $guru
      ];

      return view('admin/generate-qr/generate-qr', $data);
   }

   public function getSiswaByKelas()
   {
      $idKelas = $this->request->getVar('idKelas');
      $thajar = $this->session->get('thajar');

      $siswa = $this->siswaModel->getSiswaByKelas($idKelas,$thajar);

      return $this->response->setJSON($siswa);
   }
}
