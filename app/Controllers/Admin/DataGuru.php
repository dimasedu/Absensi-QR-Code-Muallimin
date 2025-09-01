<?php

namespace App\Controllers\Admin;

use App\Models\GuruModel;

use App\Controllers\BaseController;

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

class DataGuru extends BaseController
{
   protected GuruModel $guruModel;
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

   protected $guruValidationRules = [
      // 'nuptk' => [
      //    'rules' => 'required|max_length[20]',
      //    'errors' => [
      //       'required' => 'NBM harus diisi.',
      //       'is_unique' => 'NBM ini telah terdaftar.',
      //       // 'min_length[16]' => 'Panjang NUPTK minimal 16 karakter'
      //    ]
      // ],
      // 'tempat_lahir' => [
      //    'rules' => 'required|',
      //    'errors' => [
      //       'required' => 'Tempat Lahir harus diisi',
      //    ]
      // ],
      // 'tanggal_lahir' => [
      //    'rules' => 'required',
      //    'errors' => [
      //       'required' => 'Tanggal Lahir harus diisi.',
      //    ]
      // ],
      // 'nama' => [
      //    'rules' => 'required|min_length[3]',
      //    'errors' => [
      //       'required' => 'Nama harus diisi'
      //    ]
      // ],
      // 'jk' => ['rules' => 'required', 'errors' => ['required' => 'Jenis kelamin wajib diisi']],
      // 'no_hp' => 'required|numeric|max_length[20]|min_length[5]',
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
      $this->guruModel = new GuruModel();
      $this->db = \Config\Database::connect();$this->setQrCodeFilePath(self::UPLOADS_PATH);

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
         'title' => 'Data Guru',
         'ctx' => 'guru',
      ];

      return view('admin/data/data-guru', $data);
   }

   public function ambilDataGuru()
   {
      $result = $this->guruModel->getAllGuru();

      $data = [
         'data' => $result,
         'empty' => empty($result)
      ];

      return view('admin/data/list-data-guru', $data);
   }


   public function excel(){
      $result = $this->guruModel->getAllGuru();

      $data = [
         'data' => $result,
         'empty' => empty($result)
      ];

      return view('admin/data/excel-data-guru', $data);
   }

   public function formTambahGuru()
   {
      $data = [
         'ctx' => 'guru',
         'title' => 'Tambah Data Guru'
      ];

      return view('admin/data/create/create-data-guru', $data);
   }

   public function saveGuru()
   {
      $dataBerkas = $this->request->getFile('filefoto');
      // validasi
      if (!$this->validate($this->guruValidationRules)) {
         $data = [
            'ctx' => 'guru',
            'title' => 'Tambah Data Guru',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/create/create-data-guru', $data);
      }

      $fileName = $dataBerkas->getRandomName();
      $dataBerkas->move('public/uploads/fotoguru/', $fileName);

      // simpan
      $result = $this->guruModel->createGuru(
         nuptk: $this->request->getVar('nuptk'),
         nama: $this->request->getVar('nama'),
         jenisKelamin: $this->request->getVar('jk'),
         alamat: $this->request->getVar('alamat'),
         noHp: $this->request->getVar('no_hp'),
         tempat_lahir : $this->request->getVar('tempat_lahir'),
         tanggal_lahir : $this->request->getVar('tanggal_lahir'),
         status_guru : $this->request->getVar('status_guru'),
         jabatan : $this->request->getVar('jabatan'),
         foto : $fileName
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Tambah data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/guru');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menambah data',
         'error' => true
      ]);
      return redirect()->to('/admin/guru/create/');
   }

   public function formEditGuru($id)
   {
      $guru = $this->guruModel->getGuruById($id);

      if (empty($guru)) {
         throw new PageNotFoundException('Data guru dengan id ' . $id . ' tidak ditemukan');
      }

      $data = [
         'data' => $guru,
         'ctx' => 'guru',
         'title' => 'Edit Data Guru',
      ];

      return view('admin/data/edit/edit-data-guru', $data);
   }

   public function updateGuru()
   {
      $idGuru = $this->request->getVar('id');

      // validasi
      if (!$this->validate($this->guruValidationRules)) {
         $data = [
            'data' => $this->guruModel->getGuruById($idGuru),
            'ctx' => 'guru',
            'title' => 'Edit Data Guru',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/edit/edit-data-guru', $data);
      }


      
      // var_dump($_FILES['filefoto']['name']);
		
      if(!empty($_FILES['filefoto']['name'])){
         $dataBerkas = $this->request->getFile('filefoto');
         $fileName = $dataBerkas->getRandomName();
         $dataBerkas->move('public/uploads/fotoguru/', $fileName);
      } else {
         $fileName = $this->request->getVar('fotolama');
      }

      // update
      $result = $this->guruModel->updateGuru(
         id: $idGuru,
         nuptk: $this->request->getVar('nuptk'),
         nama: $this->request->getVar('nama'),
         jenisKelamin: $this->request->getVar('jk'),
         alamat: $this->request->getVar('alamat'),
         noHp: $this->request->getVar('no_hp'),
         tempat_lahir : $this->request->getVar('tempat_lahir'),
         tanggal_lahir : $this->request->getVar('tanggal_lahir'),
         status_guru : $this->request->getVar('status_guru'),
         jabatan : $this->request->getVar('jabatan'),
         foto : $fileName
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Edit data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/guru');
      }

      session()->setFlashdata([
         'msg' => 'Gagal mengubah data',
         'error' => true
      ]);
      return redirect()->to('/admin/guru/edit/' . $idGuru);
   }

   public function delete($id)
   {
      $result = $this->guruModel->delete($id);

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Data berhasil dihapus',
            'error' => false
         ]);
         return redirect()->to('/admin/guru');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menghapus data',
         'error' => true
      ]);
      return redirect()->to('/admin/guru');
   }


   public function show($id){
      $guru = $this->guruModel->getGuruById($id);

      if (empty($guru)) {
         throw new PageNotFoundException('Data guru dengan id ' . $id . ' tidak ditemukan');
      }

      $data = [
         'data' => $guru,
         'ctx' => 'guru',
         'title' => 'Detail Data Guru',
      ];

      return view('admin/data/detail-guru', $data);
   }


   public function importproses()
   {

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

            $result = $this->guruModel->createGuru(

               nuptk: $value[0],
               nama: $value[1],
               jenisKelamin: $value[3],
               alamat: $value[2],
               noHp: $value[4],
               tempat_lahir : $value[8],
               tanggal_lahir : $value[9],
               status_guru : $value[6],
               jabatan : $value[7],
              
            );
         }

         session()->setFlashdata([
            'msg' => 'Proses Import Data Guru berhasil',
            'error' => false
         ]);
     } else {
      session()->setFlashdata([
         'msg' => 'Oops! Terjadi kesahalan. Silahkan coba kembali...!',
         'error' => true
      ]);
     }
		
		return redirect()->to('/admin/guru');
   }


   public function kartucetak()
   {
      $id = $this->request->getVar('id');
      $siswa = array();
      if(empty($id)){
         return redirect()->to('/admin/guru');
      } else {
         $files = glob('public/uploads/qrcodeguru/*'); //get all file names
         foreach($files as $file){
            if(is_file($file))
            unlink($file); //delete file
         }
         for($i=0; $i<count($id); $i++){
            $idx = $id[$i];
            $qsiswa = $this->guruModel->find($idx);
            $qrcode= $this->downloadQrSiswa($idx); 
            $siswa[] = [
               'nama_guru'=>$qsiswa['nama_guru'],
               'nip'=>$qsiswa['nuptk'],
               'alamat'=>$qsiswa['alamat'],
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

      return view('/admin/data/kartu-guru',$data);
   }

   public function downloadQrSiswa($idSiswa = null)
   {
      $siswa = (new GuruModel)->find($idSiswa);
      if (!$siswa) {
         session()->setFlashdata([
            'msg' => 'Guru tidak ditemukan',
            'error' => true
         ]);
         
      }
      try {

         return 
            $this->generate(
               nama: $siswa['nama_guru'],
               nomor: $siswa['nuptk'],
               unique_code: $siswa['unique_code'],
               fullpath:false
            );
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return $th->getMessage();
      }
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
}
