<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\PresensiGuruModel;
use App\Models\PresensiSiswaModel;
use App\Libraries\enums\TipeUser;

class Scan extends BaseController
{
   protected SiswaModel $siswaModel;
   protected GuruModel $guruModel;
   protected kelasModel $kelasModel;

   protected PresensiSiswaModel $presensiSiswaModel;
   protected PresensiGuruModel $presensiGuruModel;
   protected $db;

   protected $siswaValidationRules = [
      'nis' => [
         'rules' => 'required|max_length[20]|min_length[4]',
         'errors' => [
            'required' => 'NIS harus diisi.',
            'is_unique' => 'NIS ini telah terdaftar.',
            'min_length[4]' => 'Panjang NIS minimal 4 karakter'
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
      'no_hp' => 'required|numeric|max_length[20]|min_length[5]'
   ];
   

   public function __construct()
   {
      $this->siswaModel = new SiswaModel();
      $this->guruModel = new GuruModel();
      $this->kelasModel = new KelasModel();
      $this->presensiSiswaModel = new PresensiSiswaModel();
      $this->presensiGuruModel = new PresensiGuruModel();
      $this->db = \Config\Database::connect();
      $this->session = \Config\Services::session();

   }

   public function index($t = 'Masuk')
   {
      $data = ['waktu' => $t, 'title' => 'Absensi Siswa dan Guru Berbasis QR Code'];
      return view('scan/scan', $data);
   }

   public function cekKode()
   {
      // ambil variabel POST
      $uniqueCode = $this->request->getVar('unique_code');
      $waktuAbsen = $this->request->getVar('waktu');

      $status = false;
      $type = TipeUser::Siswa;

      // cek data siswa di database
      $result = $this->siswaModel->cekSiswa($uniqueCode);

      if (empty($result)) {
         // jika cek siswa gagal, cek data guru
         $result = $this->guruModel->cekGuru($uniqueCode);

         if (!empty($result)) {
            $status = true;

            $type = TipeUser::Guru;
         } else {
            $status = false;

            $result = NULL;
         }
      } else {
         $status = true;
      }

      if (!$status) { // data tidak ditemukan
         return $this->showErrorView('Data tidak ditemukan');
      }

      // jika data ditemukan
      switch ($waktuAbsen) {
         case 'masuk':
            return $this->absenMasuk($type, $result);
            break;

         case 'pulang':
            return $this->absenPulang($type, $result);
            break;

         default:
            return $this->showErrorView('Data tidak valid');
            break;
      }
   }

   public function absenMasuk($type, $result)
   {
      // data ditemukan
      $data['data'] = $result;
      $data['waktu'] = 'masuk';

      $date = Time::today()->toDateString();
      $time = Time::now()->toTimeString();
      $thajar = $this->session->get('thajar');
      
      // var_dump($thajar);

      $querset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
      $pesanreg = $querset->pesan_registrasi;
      $pesan_siswa_masuk = $querset->pesan_masuk_siswa;
      $pesan_siswa_keluar = $querset->pesan_keluar_siswa;
      $pesan_guru_masuk = $querset->pesan_masuk_guru;
      $pesan_guru_keluar = $querset->pesan_keluar_guru;

      // absen masuk
      switch ($type) {
         case TipeUser::Guru:
            $idGuru =  $result['id_guru'];
            $data['type'] = TipeUser::Guru;

            $sudahAbsen = $this->presensiGuruModel->cekAbsen($idGuru, $date);

            if ($sudahAbsen) {
               $data['presensi'] = $this->presensiGuruModel->getPresensiById($sudahAbsen);
               return $this->showErrorView('Anda sudah absen hari ini', $data);
            }

            $this->presensiGuruModel->absenMasuk($idGuru, $date, $time, '',$thajar);

            $data['presensi'] = $this->presensiGuruModel->getPresensiByIdGuruTanggal($idGuru, $date);

            $gurune = $this->db->table('tb_guru')->where('id_guru',$idGuru)->get()->getRow();
            $pesan1 = str_replace('[NUPTK]',$gurune->nuptk,$pesan_guru_masuk);
            $pesan2 = str_replace('[NAMA]',$gurune->nama_guru,$pesan1);
            $pesan3 =str_replace('[WAKTU]',date('Y-m-d H:i:s'),$pesan2);
            
            $pesankirim = $pesan3;
            $kirimwa  = $this->kirimwasender($pesankirim, $gurune->no_hp);
            

         //    $outbox = $this->db->table('outbox')->insert([
         //       'tipe'=>'masuk',
         //       'tujuan'=>$gurune->no_hp,
         //       'person_id'=>$idGuru,
         //       'person_tipe'=>'guru',
         //       'created_at'=>date('Y-m-d H:i:s')
         //   ]);

            return view('scan/scan-result', $data);

         case TipeUser::Siswa:
            $idSiswa =  $result['id_siswa'];
            $idKelas =  $result['kode_kelas'];
            $data['type'] = TipeUser::Siswa;

            $sudahAbsen = $this->presensiSiswaModel->cekAbsen($idSiswa, Time::today()->toDateString());

            if ($sudahAbsen) {
               $data['presensi'] = $this->presensiSiswaModel->getPresensiById($sudahAbsen);
               return $this->showErrorView('Anda sudah absen hari ini', $data);
            }

            $this->presensiSiswaModel->absenMasuk($idSiswa, $date, $time, $idKelas, $thajar);

            $data['presensi'] = $this->presensiSiswaModel->getPresensiByIdSiswaTanggal($idSiswa, $date);

            $siswa = $this->siswaModel->getSiswaByIdThajar($idSiswa,$thajar);

            $pesan1 = str_replace('[NAMA]',$siswa['nama_siswa'],$pesan_siswa_masuk);
            $pesan2 = str_replace('[KELAS]',$siswa['kelas'],$pesan1);
            $pesan3 = str_replace('[JURUSAN]',$siswa['jurusan'],$pesan2);
            $pesan4 =str_replace('[WAKTU]',date('Y-m-d H:i:s'),$pesan3);

            $pesankirim = $pesan4;
            $kirimwa  = $this->kirimwasender($pesankirim, $siswa['no_hp']);

            // $pesan1 = str_replace('[NAMA]',$siswa['nama_siswa'],$pesan);
            // $pesan2 = str_replace('[KELAS]',$siswa['kelas'],$pesan1);
            // $pesan3 = str_replace('[JURUSAN]',$siswa['jurusan'],$pesan2);
            // $pesan4 =str_replace('[WAKTU]',date('Y-m-d H:i:s'),$pesan3);
            // $kirimwa  = $this->kirimwasender($pesan, $siswa['no_hp']);
         //    $outbox = $this->db->table('outbox')->insert([
         //       'tipe'=>'masuk',
         //       'tujuan'=>$siswa['no_hp'],
         //       'person_id'=>$idSiswa,
         //       'person_tipe'=>'siswa',
         //       'created_at'=>date('Y-m-d H:i:s')
         //   ]);

            return view('scan/scan-result', $data);

         default:
            return $this->showErrorView('Tipe tidak valid');
      }
   }

   public function absenPulang($type, $result)
   {
      
      // data ditemukan
      $data['data'] = $result;
      $data['waktu'] = 'pulang';

      $date = Time::today()->toDateString();
      $time = Time::now()->toTimeString();

      $thajar = $this->session->get('thajar');
      
      // var_dump($thajar);

      $querset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
      $pesanreg = $querset->pesan_registrasi;
      $pesan_siswa_masuk = $querset->pesan_masuk_siswa;
      $pesan_siswa_keluar = $querset->pesan_keluar_siswa;
      $pesan_guru_masuk = $querset->pesan_masuk_guru;
      $pesan_guru_keluar = $querset->pesan_keluar_guru;

      // absen pulang
      switch ($type) {
         case TipeUser::Guru:
            $idGuru =  $result['id_guru'];
            $data['type'] = TipeUser::Guru;

            $sudahAbsen = $this->presensiGuruModel->cekAbsen($idGuru, $date);

            if (!$sudahAbsen) {
               return $this->showErrorView('Anda belum absen hari ini', $data);
            }

            $this->presensiGuruModel->absenKeluar($sudahAbsen, $time);

            $data['presensi'] = $this->presensiGuruModel->getPresensiById($sudahAbsen);
            $gurune = $this->db->table('tb_guru')->where('id_guru',$idGuru)->get()->getRow();
            $pesan1 = str_replace('[NUPTK]',$gurune->nuptk,$pesan_guru_keluar);
            $pesan2 = str_replace('[NAMA]',$gurune->nama_guru,$pesan1);
            $pesan3 =str_replace('[WAKTU]',date('Y-m-d H:i:s'),$pesan2);
            
            $pesankirim = $pesan3;
            $kirimwa  = $this->kirimwasender($pesankirim, $gurune->no_hp);

            return view('scan/scan-result', $data);

         case TipeUser::Siswa:
            $idSiswa =  $result['id_siswa'];
            $data['type'] = TipeUser::Siswa;

            $sudahAbsen = $this->presensiSiswaModel->cekAbsen($idSiswa, $date);

            if (!$sudahAbsen) {
               return $this->showErrorView('Anda belum absen hari ini', $data);
            }

            $this->presensiSiswaModel->absenKeluar($sudahAbsen, $time);

            $data['presensi'] = $this->presensiSiswaModel->getPresensiById($sudahAbsen);

            $siswa = $siswa = $this->siswaModel->getSiswaByIdThajar($idSiswa,$thajar);
            $pesan1 = str_replace('[NAMA]',$siswa['nama_siswa'],$pesan_siswa_keluar);
            $pesan2 = str_replace('[KELAS]',$siswa['kelas'],$pesan1);
            $pesan3 = str_replace('[JURUSAN]',$siswa['jurusan'],$pesan2);
            $pesan4 =str_replace('[WAKTU]',date('Y-m-d H:i:s'),$pesan3);

            $pesankirim = $pesan4;
            $kirimwa  = $this->kirimwasender($pesankirim, $siswa['no_hp']);
           

            return view('scan/scan-result', $data);

            
         default:
            return $this->showErrorView('Tipe tidak valid');
      }
   }


   public function scan_device($tipe)
   {
      if($tipe == "Masuk"){
         return view('scan/scan-device-masuk');
      } else {
         return view('scan/scan-device-pulang');
      }
   }


   public function scan_device_proses()
   {
      
   }


   public function register(){
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


   public function registerstore()
   {
      if (!$this->validate($this->siswaValidationRules)) {
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

      // simpan
      $result = $this->siswaModel->createSiswa(
         nis: $this->request->getVar('nis'),
         nama: $this->request->getVar('nama'),
         idKelas: intval($this->request->getVar('id_kelas')),
         jenisKelamin: $this->request->getVar('jk'),
         noHp: $this->request->getVar('no_hp'),
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Pendaftaran Data berhasil dilakukan',
            'error' => false
         ]);

         $pesan = '*Assalamualaikum Wr., Wb.*%0a %0a Halo Orangtua/Wali Siswa ananda%0a %0a'.$this->request->getVar('nama').'%0a('.$this->request->getVar('nis').').%0aTelah melakukan pendaftaran pada sistem Absensi Online MTsN 1 Batam pada '.date('d/m/Y H:i:s').' WIB%0a*Wassalamualaikum Wr., Wb.*%0a %0a_(Dikirim oleh Sistem Absensi MTsN 1 Batam)_';
         
         $this->kirimwasender($pesan, $this->request->getVar('no_hp'));
         return redirect()->to('/register');
      }
   }



   public function showErrorView(string $msg = 'no error message', $data = NULL)
   {
      $errdata = $data ?? [];
      $errdata['msg'] = $msg;

      return view('scan/error-scan-result', $errdata);
   }

   public function teskirim(){
      $pesan = '*Assalamualaikum Wr., Wb.*%0a %0a Kepada YTH kader PAC GP Ansor Adiwerna';
      $target = '087710248484';

      $kirim = $this->kirimwasender($pesan, $target);

      var_dump($kirim);
   }


   

    public function kirimwasender($pesan, $target, $gambar=""){

      $querset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
      $appkey = $querset->app_key;
      $authkey = $querset->auth_key;
      $urltarget = $querset->url_api;

      if($gambar != "" || !empty($gambar)){
          $params = [
              'appkey' => $appkey,
              'authkey'=> $authkey,
              'to' => $this->format_nomor($target),
              'message' => urldecode($pesan),
              
              'file' => base_url('public/uploads/broadcast/'.$gambar),
              'sandbox'=> false
              ];  
      } else {
          $params = [
              'appkey' => $appkey,
              'authkey'=> $authkey,
              'to' => $this->format_nomor($target),
              'message' => urldecode($pesan),
             
              'sandbox'=> false
              ];
      }

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
