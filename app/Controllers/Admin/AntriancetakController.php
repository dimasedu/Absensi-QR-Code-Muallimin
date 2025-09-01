<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AntriancetakModel;
use App\Models\SiswaModel;


use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class AntriancetakController extends BaseController
{
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

    public function __construct()
    {
        $this->antrianModel = new AntriancetakModel();
        $this->siswaModel = new SiswaModel();
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
      $result = $this->antrianModel->select(['
      tb_siswa.id_siswa','tb_siswa.nis','tb_siswa.nama_siswa','tb_siswa.jenis_kelamin','tb_siswa.no_hp','tb_siswa.foto','tb_kelas.kelas','tb_jurusan.jurusan','tb_antrian_cetak.id',
        'tb_antrian_cetak.id_person'])
      ->join('tb_siswa', 'tb_siswa.id_siswa = tb_antrian_cetak.id_person', 'LEFT')
      ->join(
         'tb_siswa_trans',
         'tb_siswa_trans.id_siswa = tb_siswa.id_siswa',
         'LEFT')      
      ->join(
         'tb_kelas',
         'tb_kelas.kode = tb_siswa_trans.kode_kelas',
         'LEFT'
      )->join(
         'tb_jurusan',
         'tb_kelas.id_jurusan = tb_jurusan.id',
         'LEFT'
      )     
 
       ->where('tb_antrian_cetak.tipe','siswa')
        ->findAll();

        $data = [
            'data' => $result,
            'empty' => empty($result),
            'title'=>'Antrian Cetak Kartu Pelajar'
        ];

        return view('admin/data/list-antrian-siswa', $data);
    }


    public function store($id){
        $cek = $this->antrianModel->where('id_person',$id)->first();
        if(!empty($cek)){
            $json = ['status'=>'FAIL','msg'=>'Data sudah ditambahkan'];
        } else {
            $query = $this->antrianModel
            ->save([
                'id_person'=>$id,
                'tipe'=>'siswa',
            ]);

            $siswa = $this->siswaModel->find($id);

            $json = [
                'status'=>'OK',
                'msg'=>'Data berhasil ditambahkan',
                'nisn'=>$siswa['nis'],
                'nama'=>$siswa['nama_siswa']
            ];
        }

        echo json_encode($json);
    }


    public function kartucetak()
    {
    

      $query = $this->antrianModel->select(['
      tb_siswa.id_siswa','tb_siswa.nis','tb_siswa.nama_siswa','tb_siswa.jenis_kelamin','tb_siswa.no_hp','tb_siswa.foto','tb_kelas.kelas','tb_jurusan.jurusan','tb_antrian_cetak.id',
        'tb_antrian_cetak.id_person'])
      ->join('tb_siswa', 'tb_siswa.id_siswa = tb_antrian_cetak.id_person', 'LEFT')
      ->join(
         'tb_siswa_trans',
         'tb_siswa_trans.id_siswa = tb_siswa.id_siswa',
         'LEFT')      
      ->join(
         'tb_kelas',
         'tb_kelas.kode = tb_siswa_trans.kode_kelas',
         'LEFT'
      )->join(
         'tb_jurusan',
         'tb_kelas.id_jurusan = tb_jurusan.id',
         'LEFT'
      )     
 
       ->where('tb_antrian_cetak.tipe','siswa')
       ->findAll();

       if(empty($query)){
          return redirect()->to('/admin/siswa');
       } else {
          $files = glob('public/uploads/qrcode/*'); //get all file names
          foreach($files as $file){
             if(is_file($file))
             unlink($file); //delete file
          }

          foreach($query as $qsiswa){
             
             $qrcode= $this->downloadQrSiswa($qsiswa['id_person']); 
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


    public function clear_antrian(){
        $query = $this->antrianModel->where('tipe','siswa')->delete();

        return redirect()->to('/admin/siswa');
    }

    public function destroy($id){
        $query = $this->antrianModel->delete($id);

        session()->setFlashdata('msg','Data antrian berhasil dihapus');
        return redirect()->to('/admin/siswa/cetakantrian');
    }
}
