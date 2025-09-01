<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SiswaModel;
use App\Models\KelasModel;

class Register extends BaseController
{
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;

    protected $siswaValidationRules = [
        'nis' => [
            'rules' => 'required|max_length[10]|min_length[10]',
            'errors' => [
                'required' => 'NISN harus diisi.',
                'is_unique' => 'NISN ini telah terdaftar.',
                'min_length[4]' => 'Panjang NISN minimal 10 karakter'
            ]
        ],
        'nama' => [
            'rules' => 'required|min_length[3]',
            'errors' => [
                'required' => 'Nama harus diisi'
            ]
        ],
        'id_kelas' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Kelas harus diisi'
            ]
        ],
        'jk' => ['rules' => 'required', 'errors' => ['required' => 'Jenis kelamin wajib diisi']],
        'no_hp' => 'required|numeric|max_length[20]|min_length[5]',
        'filefoto' => [
				'rules' => 'uploaded[filefoto]|mime_in[filefoto,image/jpg,image/jpeg,image/gif,image/png]|max_size[filefoto,2048]',
				'errors' => [
					'uploaded' => 'Harus Ada File yang diupload',
					'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
					'max_size' => 'Ukuran File Maksimal 5 MB'
				]
 
            ],
    ];
    

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();

    }

    public function index()
    {
        $kelas = $this->kelasModel->getAllKelas();
      $data = [
         'ctx' => 'siswa',
         'kelas' => $kelas,
         'title' => 'Tambah Data Siswa',
         'validation' => $this->validator,
         'oldInput' => $this->request->getVar()
      ];
      return view('/scan/register', $data);
    }


    public function simpan(){
        var_dump($this->request);
        // $dataBerkas = $this->request->getFile('filefoto');

        // if (!$this->validate($this->siswaValidationRules)) {
        //     $fileName = $dataBerkas->getRandomName();
        //     $dataBerkas->move('public/uploads/fotosiswa/', $fileName);
        //     $kelas = $this->kelasModel->getAllKelas();
   
        //     $data = [
        //        'ctx' => 'siswa',
        //        'kelas' => $kelas,
        //        'title' => 'Tambah Data Siswa',
        //        'validation' => $this->validator,
        //        'oldInput' => $this->request->getVar()
        //     ];
        //     return view('scan/register', $data);
        //  }
   
        //  // simpan
        //  $result = $this->siswaModel->createSiswa(
        //     nis: $this->request->getVar('nis'),
        //     nama: $this->request->getVar('nama'),
        //     idKelas: intval($this->request->getVar('id_kelas')),
        //     jenisKelamin: $this->request->getVar('jk'),
        //     noHp: $this->request->getVar('no_hp'),
        //     foto: $fileName
        //  );
   
        // //  if ($result) {
        //     session()->setFlashdata([
        //        'msg' => 'Pendaftaran Data berhasil dilakukan',
        //        'error' => false
        //     ]);
   
        //     $pesan = '*Assalamualaikum Wr., Wb.*%0a %0a Halo Orangtua/Wali Siswa ananda '.$this->request->getVar('nama').'%0a('.$this->request->getVar('nis').').%0aTelah melakukan pendaftaran pada sistem Absensi Online pada '.date('d/m/Y H:i:s').' WIB%0a*Wassalamualaikum Wr., Wb.*%0a %0a _(Dikirim oleh Sistem Absensi)_';
        //     $this->kirimwasender($pesan, $this->request->getVar('no_hp'));
        //     return redirect()->to('/register');
        // //  }
    }

    public function kirimwasender($pesan, $target){

        $appkey = 'd7f191df-c148-4110-a6d0-1bbd7014c5d3';
        $authkey = 'K5nhcltHFE588DQtQsTFVokHVgL2ZcGyYt5WJV93or3SEp7gvD';
        $urltarget = 'https://kedaiwa.my.id/api/create-message';
        $params = [
                'appkey' => $appkey,
                'authkey'=> $authkey,
                'to' => $this->format_nomor($target),
                'message' => urldecode($pesan),
                'sandbox'=> false
                ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$urltarget);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec ($ch);
        curl_close ($ch);

        return $output;
      }
  
  
      public function format_nomor($nomorhp){
        
           $nomorhp = trim($nomorhp);
           $nomorhp = strip_tags($nomorhp);     
           $nomorhp= str_replace(" ","",$nomorhp);
           $nomorhp= str_replace("(","",$nomorhp);
           $nomorhp= str_replace(".","",$nomorhp); 
      
          
           if(!preg_match('/[^+0-9]/',trim($nomorhp))){
               
               if(substr(trim($nomorhp), 0, 3)=='+62'){
                   $nomorhp= '62'.substr($nomorhp, 1);
               }
               
              elseif(substr($nomorhp, 0, 1)=='0'){
                   $nomorhp= '62'.substr($nomorhp, 1);
               }
           }
           return $nomorhp;
       
      }
}
