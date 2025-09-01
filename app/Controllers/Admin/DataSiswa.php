<?php

namespace App\Controllers\Admin;

use App\Models\SiswaModel;
use App\Models\SiswatransModel;
use App\Models\KelasModel;

use App\Controllers\BaseController;
use App\Models\JurusanModel;
use CodeIgniter\Exceptions\PageNotFoundException;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class DataSiswa extends BaseController
{
   protected SiswaModel $siswaModel;
   protected KelasModel $kelasModel;
   protected JurusanModel $jurusanModel;
   protected $db;

   protected QrCode $qrCode;
   protected PngWriter $writer;
   protected ?Logo $logo;
   protected Label $label;
   protected Font $labelFont;
   protected Color $foregroundColor;
   protected Color $foregroundColor2;
   protected Color $backgroundColor;

   protected string $qrCodeFilePath;
   

   const PUBLIC_PATH = ROOTPATH . 'public' . DIRECTORY_SEPARATOR;
   const UPLOADS_PATH = self::PUBLIC_PATH . 'uploads' . DIRECTORY_SEPARATOR;

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
      // 'id_kelas' => [
      //    'rules' => 'required',
      //    'errors' => [
      //       'required' => 'Kelas harus diisi'
      //    ]
      // ],
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


   protected $siswaEditValidationRules = [
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
      // 'id_kelas' => [
      //    'rules' => 'required',
      //    'errors' => [
      //       'required' => 'Kelas harus diisi'
      //    ]
      // ],
      'jk' => ['rules' => 'required', 'errors' => ['required' => 'Jenis kelamin wajib diisi']],
      'no_hp' => 'required|numeric|max_length[20]|min_length[5]',
      'filefoto' => [
				'rules' => 'mime_in[filefoto,image/jpg,image/jpeg,image/gif,image/png]|max_size[filefoto,2048]',
				'errors' => [
					
					'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
					'max_size' => 'Ukuran File Maksimal 5 MB'
				]
 
         ],
      ];

   public function __construct()
   {
      $this->siswaModel = new SiswaModel();
      $this->siswatransModel = new SiswatransModel();
      $this->kelasModel = new KelasModel();
      $this->jurusanModel = new JurusanModel();
      $this->db = \Config\Database::connect();
      $this->session = \Config\Services::session();

      $this->setQrCodeFilePath(self::UPLOADS_PATH);

      $this->writer = new PngWriter();

      $this->labelFont = new Font(self::PUBLIC_PATH . 'assets/fonts/Roboto-Medium.ttf', 14);

      $this->foregroundColor = new Color(44, 73, 162);
      $this->foregroundColor2 = new Color(28, 101, 90);
      $this->backgroundColor = new Color(255, 255, 255);

      // Create logo
      $this->logo = boolval(env('QR_LOGO'))
         ? Logo::create(self::PUBLIC_PATH . 'assets/img/logo_sekolah.jpg')->setResizeToWidth(75)
         : null;

      $this->label = Label::create('')
         ->setFont($this->labelFont)
         ->setTextColor($this->foregroundColor);

      // Create QR code
      $this->qrCode = QrCode::create('')
         ->setEncoding(new Encoding('UTF-8'))
         ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
         ->setSize(300)
         ->setMargin(10)
         ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
         ->setForegroundColor($this->foregroundColor)
         ->setBackgroundColor($this->backgroundColor);
   }


   public function setQrCodeFilePath(string $qrCodeFilePath)
   {
      $this->qrCodeFilePath = $qrCodeFilePath;
      if (!file_exists($this->qrCodeFilePath)) mkdir($this->qrCodeFilePath, recursive: true);
   }


   public function index()
   {
      $data = [
         'title' => 'Data Siswa',
         'ctx' => 'siswa',
         'kelas' => $this->kelasModel->getAllKelas(),
         'jurusan' => $this->jurusanModel->findAll(),
         'kelasxls'=>'all',
         'jurusanxls'=>'all'
      ];

      return view('admin/data/data-siswa', $data);
   }

   public function ambilDataSiswa()
   {
      $kelas = $this->request->getVar('kelas') ?? "all";
      $jurusan = $this->request->getVar('jurusan') ?? "all";
      $thajar = $this->session->get('thajar');

      $result = $this->siswaModel->getAllSiswaWithKelas($kelas, $jurusan,$thajar);

      $data = [
         'data' => $result,
         'empty' => empty($result),
         'kelasxls'=>$kelas,
         'jurusanxls'=>$jurusan,
         'kelas' => $this->kelasModel->getAllKelas(),
         'jurusan' => $this->jurusanModel->findAll(),
      ];

      return view('admin/data/list-data-siswa', $data);
   }


   public function excel($kelas, $jurusan)
   {

      $thajar = $this->session->get('thajar');
      $result = $this->siswaModel->getAllSiswaWithKelas($kelas, $jurusan, $thajar);

      $data = [
         'data' => $result,
         'empty' => empty($result)
      ];

      return view('admin/data/excel-data-siswa', $data);
   }

   public function formTambahSiswa()
   {
      $kelas = $this->kelasModel->getAllKelas();

      $data = [
         'ctx' => 'siswa',
         'kelas' => $kelas,
         'title' => 'Tambah Data Siswa'
      ];

      return view('admin/data/create/create-data-siswa', $data);
   }

   public function saveSiswa()
   {
      // validasi
      $dataBerkas = $this->request->getFile('filefoto');

      if (!$this->validate($this->siswaValidationRules)) {
         $kelas = $this->kelasModel->getAllKelas();

         $data = [
            'ctx' => 'siswa',
            'kelas' => $kelas,
            'title' => 'Tambah Data Siswa',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/create/create-data-siswa', $data);
      }

      $fileName = $dataBerkas->getRandomName();
      $dataBerkas->move('public/uploads/fotosiswa/', $fileName);
      // simpan
      $result = $this->siswaModel->createSiswa(
         nis: $this->request->getVar('nis'),
         nama: $this->request->getVar('nama'),
         // idKelas: intval($this->request->getVar('id_kelas')),
         jenisKelamin: $this->request->getVar('jk'),
         noHp: $this->request->getVar('no_hp'),
         foto: $fileName
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Tambah data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/siswa');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menambah data',
         'error' => true
      ]);
      return redirect()->to('/admin/siswa/create');
   }

   public function formEditSiswa($id)
   {
      $siswa = $this->siswaModel->getSiswaById($id);
      $kelas = $this->kelasModel->getAllKelas();

      if (empty($siswa) || empty($kelas)) {
         throw new PageNotFoundException('Data siswa dengan id ' . $id . ' tidak ditemukan');
      }

      $data = [
         'data' => $siswa,
         'kelas' => $kelas,
         'ctx' => 'siswa',
         'title' => 'Edit Siswa',
      ];

      return view('admin/data/edit/edit-data-siswa', $data);
   }


   public function show($id){

      $thajar = $this->session->get('thajar');
      $siswa = $this->siswaModel->getSiswaByIdThAjar($id,$thajar);
      $data = [
         'data' => $siswa,
         'ctx' => 'siswa',
         'title' => 'Detail Siswa',
      ];

      return view('admin/data/detail-siswa', $data);
   }

   public function updateSiswa()
   {
      $idSiswa = $this->request->getVar('id');

      $siswaLama = $this->siswaModel->getSiswaById($idSiswa);

      if ($siswaLama['nis'] != $this->request->getVar('nis')) {
         $this->siswaValidationRules['nis']['rules'] = 'required|max_length[20]|min_length[4]|is_unique[tb_siswa.nis]';
      }

      // validasi
      if (!$this->validate($this->siswaEditValidationRules)) {
         $siswa = $this->siswaModel->getSiswaById($idSiswa);
         $kelas = $this->kelasModel->getAllKelas();

         $data = [
            'data' => $siswa,
            'kelas' => $kelas,
            'ctx' => 'siswa',
            'title' => 'Edit Siswa',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/edit/edit-data-siswa', $data);
      }

      // update
      $dataBerkas = $this->request->getFile('filefoto');
      // var_dump($_FILES['filefoto']['name']);
		
      if(!empty($_FILES['filefoto']['name'])){
         $fileName = $dataBerkas->getRandomName();
         
         $dataBerkas->move('public/uploads/fotosiswa/', $fileName);
         $result = $this->siswaModel->updateSiswa(
            id: $idSiswa,
            nis: $this->request->getVar('nis'),
            nama: $this->request->getVar('nama'),
            // idKelas: intval($this->request->getVar('id_kelas')),
            jenisKelamin: $this->request->getVar('jk'),
            noHp: $this->request->getVar('no_hp'),
            foto : $fileName
         );
      } else {
         $result = $this->siswaModel->updateSiswa(
            id: $idSiswa,
            nis: $this->request->getVar('nis'),
            nama: $this->request->getVar('nama'),
            // idKelas: intval($this->request->getVar('id_kelas')),
            jenisKelamin: $this->request->getVar('jk'),
            noHp: $this->request->getVar('no_hp'),
            foto : null
         );
      }
      

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Edit data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/siswa');
      }

      session()->setFlashdata([
         'msg' => 'Gagal mengubah data',
         'error' => true
      ]);
      return redirect()->to('/admin/siswa/edit/' . $idSiswa);
   }

   public function delete($id)
   {

      $siswa = $this->siswaModel->getSiswaById($id);
      if(file_exists('/public/uploads/fotosiswa/'.$siswa['foto'])){
         unlink('/public/fotosiswa/'.$siswa['foto']);
      } 
      $result = $this->siswaModel->delete($id);
      $hapus_trans = $this->db->table('tb_siswa_trans')->where('id_siswa',$id)->delete();

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Data berhasil dihapus',
            'error' => false
         ]);
         return redirect()->to('/admin/siswa');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menghapus data',
         'error' => true
      ]);
      return redirect()->to('/admin/siswa');
   }


   public function kartucetak()
   {
      $id = $this->request->getVar('id');
      $thajar = $this->session->get('thajar');
      $siswa = array();
      if(empty($id)){
         return redirect()->to('/admin/siswa');
      } else {
         $files = glob('public/uploads/qrcode/*'); //get all file names
         foreach($files as $file){
            if(is_file($file))
            unlink($file); //delete file
         }
         for($i=0; $i<count($id); $i++){
            $idx = $id[$i];
            $qsiswa = $this->siswaModel->getSiswaByIdThAjar($idx,$thajar);
            $qrcode= $this->downloadQrSiswa($idx); 
            $siswa[] = [
               'nama_siswa'=>$qsiswa['nama_siswa'],
               'nis'=>$qsiswa['nis'],
               'jenis_kelamin'=>$qsiswa['jenis_kelamin'],
               'kelas'=>$qsiswa['kelas'],
               'jurusan'=>$qsiswa['jurusan'],
               'no_hp'=>$qsiswa['no_hp'],
               'qrcode'=>$qrcode,
               'foto'=>$qsiswa['foto']
            ];
         }
      }

      $data = [
         'siswa'=>$siswa,
         'queryset'=>$this->db->table('pengaturan')->where('id',1)->get()->getRow()
      ];

      return view('/admin/data/kartu-pelajar',$data);
   }

   public function downloadQrSiswa($idSiswa = null)
   {
      $siswa = (new SiswaModel)->find($idSiswa);
      if (!$siswa) {
         session()->setFlashdata([
            'msg' => 'Siswa tidak ditemukan',
            'error' => true
         ]);
         
      }
      try {
         $kelas = $this->getKelasJurusanSlug($siswa['id_kelas']) ?? 'tmp';
         // $this->qrCodeFilePath .= "qr-siswa/$kelas/";

         // if (!file_exists($this->qrCodeFilePath)) {
         //    mkdir($this->qrCodeFilePath, recursive: true);
         // }

         // $genqr = $this->response->download(
         //    $this->generate(
         //       nama: $siswa['nama_siswa'],
         //       nomor: $siswa['nis'],
         //       unique_code: $siswa['unique_code'],
         //       fullpath : true
         //    ),
         //    null,
         //    true,
         // );

         return 
            $this->generate(
               nama: $siswa['nama_siswa'],
               nomor: $siswa['nis'],
               unique_code: $siswa['unique_code'],
               fullpath:false
            );
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return 'Gagal';
      }
   }


   public function importsiswa()
   {

      $thajar = $this->session->get('thajar');
      $file = $this->request->getFile('fileexcel');
      if ($file->isValid() && !$file->hasMoved()) {
         $spreadsheet = new Spreadsheet();
         $reader = new Xlsx();
         $spreadsheet = $reader->load($file->getTempName());

         $sheetData = $spreadsheet->getActiveSheet()->toArray();

         foreach ($sheetData as $key => $value) {
             if ($key == 0) {
                 continue; // Skip header row
             }

            $result = $this->siswaModel->createSiswa(
               nis: $value[0],
               nama: $value[1],
               // idKelas: intval($value[2]),
               jenisKelamin: $value[3],
               noHp: $value[4],
               foto : $value[6]
            );

            $idsiswa = $this->siswaModel->insertID();

            $siswa_trans = $this->siswatransModel->save([
               'id_siswa'=>$idsiswa,
               'kode_kelas'=>$value[2],
               'tipe'=>'masuk',
               'th_ajar'=>$thajar
            ]);
         }

         session()->setFlashdata([
            'msg' => 'Proses Import Data Siswa berhasil',
            'error' => false
         ]);
     } else {
      session()->setFlashdata([
         'msg' => 'Oops! Terjadi kesahalan. Silahkan coba kembali...!',
         'error' => true
      ]);
     }
		
		return redirect()->to('/admin/siswa');
   }


   public function generate($nama, $nomor, $unique_code,$fullpath=true)
   {
      $filename = url_title($nama, lowercase: true) . "_" . url_title($nomor, lowercase: true) . '.png';

      // set qr code data
      $this->qrCode->setData($nomor);

      $this->label->setText($nama);

      // Save it to a file
      $this->writer
         ->write(
            qrCode: $this->qrCode,
            logo: $this->logo,
            // label: $this->label
         )
         ->saveToFile(
            path: 'public/uploads/qrcode/'. $filename
         );

         return $filename;
      
   }


   protected function kelas(string $unique_code)
   {
      return self::UPLOADS_PATH . DIRECTORY_SEPARATOR . "qr-siswa/{$unique_code}.png";
   }

   protected function getKelasJurusanSlug(string $idKelas)
   {
      $kelas = (new KelasModel)
         ->join('tb_jurusan', 'tb_kelas.id_jurusan = tb_jurusan.id', 'left')
         ->find($idKelas);
      if ($kelas) {
         return url_title($kelas['kelas'] . ' ' . $kelas['jurusan'], lowercase: true);
      } else {
         return false;
      }
   }


   public function listpesan(){
      $query = $this->db->query("SELECT * FROM outbox where status != 'Y' ORDER BY id DESC")->getResultArray();

      $data = [
         'ctx' => 'pesan',
         'query' => $query,
         'title' => 'Daftar Pesan Gagal Kirim'
      ];

      return view('admin/data/pesan/listpesan', $data);

      // var_dump(count($query));
   }


   public function kirimpesan($id){
      $query = $this->db->table('outbox')->where('id',$id)->get()->getRow();
      $querset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
      $pesanreg = $querset->pesan_registrasi;
      $pesan_siswa_masuk = $querset->pesan_masuk_siswa;
      $pesan_siswa_keluar = $querset->pesan_keluar_siswa;
      $pesan_guru_masuk = $querset->pesan_masuk_guru;
      $pesan_guru_keluar = $querset->pesan_keluar_guru;

      $thajar = $this->session->get('thajar');

      
      if($query->tipe == "registrasi"){
                $siswa = $this->siswaModel->getSiswaByIdThajar($query->person_id,$thajar);
                $pesan = $pesanreg;
                $pesan1 = str_replace('[NAMA]',$siswa['nama_siswa'],$pesan);
                $pesan2 = str_replace('[KELAS]',$siswa['kelas'],$pesan1);
                $pesan3 = str_replace('[JURUSAN]',$siswa['jurusan'],$pesan2);
                $pesan4 =str_replace('[WAKTU]',$query->created_at,$pesan3);
                $pesan5 = str_replace('[NIS]',$siswa['nis'],$pesan4);

                $pesankirim = $pesan5;
                

            } elseif($query->tipe == "masuk"){
                if($query->person_tipe == "siswa"){
                    $siswa = $this->siswaModel->getSiswaByIdThajar($query->person_id,$thajar);
                    $pesan1 = str_replace('[NAMA]',$siswa['nama_siswa'],$pesan_siswa_masuk);
                    $pesan2 = str_replace('[KELAS]',$siswa['kelas'],$pesan1);
                    $pesan3 = str_replace('[JURUSAN]',$siswa['jurusan'],$pesan2);
                    $pesan4 =str_replace('[WAKTU]',$query->created_at,$pesan3);

                    $pesankirim = $pesan4;

                } else{

                    $gurune = $this->db->table('tb_guru')->where('id_guru',$query->person_id)->get()->getRow();
                    $pesan1 = str_replace('[NUPTK]',$gurune->nuptk,$pesan_guru_masuk);
                    $pesan2 = str_replace('[NAMA]',$gurune->nama_guru,$pesan1);
                    $pesan3 =str_replace('[WAKTU]',$query->created_at,$pesan2);
                    
                    $pesankirim = $pesan3;
                }

            } elseif($query->tipe == "pulang"){
                if($query->person_tipe == "siswa"){
                    $siswa = $this->siswaModel->getSiswaByIdThajar($query->person_id,$thajar);
                    $pesan1 = str_replace('[NAMA]',$siswa['nama_siswa'],$pesan_siswa_keluar);
                    $pesan2 = str_replace('[KELAS]',$siswa['kelas'],$pesan1);
                    $pesan3 = str_replace('[JURUSAN]',$siswa['jurusan'],$pesan2);
                    $pesan4 =str_replace('[WAKTU]',$query->created_at,$pesan3);

                    $pesankirim = $pesan4;

                } else{

                    $gurune = $this->db->table('tb_guru')->where('id_guru',$query->person_id)->get()->getRow();
                    $pesan1 = str_replace('[NUPTK]',$gurune->nuptk,$pesan_guru_keluar);
                    $pesan2 = str_replace('[NAMA]',$gurune->nama_guru,$pesan1);
                    $pesan3 =str_replace('[WAKTU]',$query->created_at,$pesan2);
                    
                    $pesankirim = $pesan3;
                }
            } elseif($query->tipe == "ijin"){
                $pesankirim = $query->isi_pesan;
            }

      $kirim = $this->kirimwasender($pesankirim, $query->tujuan);

      // $kirim = $this->kirimwatest();
      var_dump($kirim);

      if(!empty($query)){
         $kirim = $this->kirimwasender($query->isi_pesan, $query->tujuan);
         if(!empty($kirimwa)){
            $pola = json_decode($kirimwa);
            if(isset($pola->error)){
               $data = $this->db->query("UPDATE outbox SET status = 'Y' WHERE id = '$query->id'");
               session()->setFlashdata([
                 'msg'=>'Notifikasi Berhasil berhasil dikirimkan',
                 'error'=>false
              ]);
            }

            
            if(isset($pola->message_status) && $pola->message_status =="Success"){
               $data = $this->db->query("UPDATE outbox SET status = 'Y' WHERE id = '$query->id'");
               session()->setFlashdata([
                 'msg'=>'Notifikasi Berhasil dikirimkan',
                 'error'=>false
              ]);
            } else {
               $data = $this->db->query("UPDATE outbox SET status = 'Y' WHERE id = '$query->id'");
               session()->setFlashdata([
                 'msg'=>'Notifikasi Berhasil  terkirim',
                 'error'=>false
              ]);
            }

            
        } else {
         $data = $this->db->query("UPDATE outbox SET status = 'Y' WHERE id = '$query->id'");
         session()->setFlashdata([
           'msg'=>'Pesan Berhasil dikirimkan',
           'error'=>false
        ]);
        }
            
      }

      
      return redirect('admin/siswa/pesan');
   }


   public function hapus_pesan($id)
   {
      $query = $this->db->table('outbox')->where('id',$id)->delete();
      session()->setFlashdata([
         'msg'=>'Notifikasi berhasil dihapus',
         'error'=>false
      ]);

      return redirect('admin/siswa/pesan');

   }


   public function hapus_pesan_multi()
   {
      $id = $this->request->getVar('id');

      if(empty($id)){
         session()->setFlashdata([
            'msg'=>'Silahkan pilih pesan yang akan dihapus.',
            'error'=>true
         ]);
      } else {
         for($i=0; $i< count($id); $i++){
            $idx = $id[$i];
            $query = $this->db->table('outbox')->where('id',$idx)->delete();
         }

         session()->setFlashdata([
            'msg'=>'Notifikasi berhasil dihapus',
            'error'=>false
         ]);
      }
         

      return redirect('admin/siswa/pesan');
   }


   public function kirimwasender($pesan, $target){

      $querset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
      $appkey = $querset->app_key;
      $authkey = $querset->auth_key;
      $urltarget = $querset->url_api;
      // $params = [
      //         'appkey' => $appkey,
      //         'authkey'=> $authkey,
      //         'to' => $this->format_nomor($target),
      //         'message' => urldecode($pesan),
      //         'sandbox'=> false
      //         ];
      // $ch = curl_init();
      // curl_setopt($ch, CURLOPT_URL,$urltarget);
      // curl_setopt($ch, CURLOPT_POST, true);
      // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // $output = curl_exec ($ch);
      // curl_close ($ch);

      // return $output;

      $curl = curl_init();

         curl_setopt_array($curl, array(
         CURLOPT_URL => 'https://app.kedaiwa.biz.id/api/create-message',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => array(
         'appkey' => $appkey,
         'authkey' => $authkey,
         'to' => $this->format_nomor($target),
         'message' => urldecode($pesan),
         'sandbox' => 'false'
         ),
         ));

         $response = curl_exec($curl);

         curl_close($curl);
         return  $response;
    }


    public function kirimwatest(){
      $curl = curl_init();

         curl_setopt_array($curl, array(
         CURLOPT_URL => 'https://app.kedaiwa.biz.id/api/create-message',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => array(
         'appkey' => '7098bfd6-589b-4ebb-93f0-2be51a92699b',
         'authkey' => 'jnMx2BnXH1dC8nIFvcIGasbH6eIX5LDB3PCqzs9936MXZ1J5mS',
         'to' => '6287710248484',
         'message' => 'Contoh saja ini ya gaes',
         'sandbox' => 'false'
         ),
         ));

         $response = curl_exec($curl);

         curl_close($curl);
         echo $response;
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
