<?php

namespace App\Controllers\Admin;

use App\Models\KelasModel;

use App\Models\SiswaModel;
use App\Models\IjinModel;

use App\Controllers\BaseController;
use App\Models\KehadiranModel;
use App\Models\PresensiSiswaModel;
use CodeIgniter\I18n\Time;

class DataAbsenSiswa extends BaseController
{
   protected KelasModel $kelasModel;

   protected SiswaModel $siswaModel;

   protected KehadiranModel $kehadiranModel;

   protected PresensiSiswaModel $presensiSiswa;

   protected string $currentDate;
   protected $ijinModel;

   protected $db;

   public function __construct()
   {
      $this->currentDate = Time::today()->toDateString();

      $this->siswaModel = new SiswaModel();

      $this->kehadiranModel = new KehadiranModel();

      $this->kelasModel = new KelasModel();
      $this->ijinModel = new IjinModel();

      $this->presensiSiswa = new PresensiSiswaModel();
      $this->db = \Config\Database::connect();
      $this->session = \Config\Services::session();
   }

   public function index()
   {
      $kelas = $this->kelasModel->getAllKelas();

      $data = [
         'title' => 'Data Absen Siswa',
         'ctx' => 'absen-siswa',
         'kelas' => $kelas
      ];

      return view('admin/absen/absen-siswa', $data);
   }

   public function ambilDataSiswa()
   {
      // ambil variabel POST
      $kelas = $this->request->getVar('kelas');
      $idKelas = $this->request->getVar('id_kelas');
      $tanggal = $this->request->getVar('tanggal');
      $thajar = $this->session->get('thajar');

      $lewat = Time::parse($tanggal)->isAfter(Time::today());

      $result = $this->presensiSiswa->getPresensiByKelasTanggal($idKelas, $tanggal, $thajar);

      $data = [
         'kelas' => $kelas,
         'data' => $result,
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'lewat' => $lewat,
         'id_kelas'=>$idKelas,
         'tanggal'=>$tanggal
      ];

      return view('admin/absen/list-absen-siswa', $data);
   }

   public function ambilKehadiran()
   {
      $idPresensi = $this->request->getVar('id_presensi');
      $idSiswa = $this->request->getVar('id_siswa');

      $data = [
         'presensi' => $this->presensiSiswa->getPresensiById($idPresensi),
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'data' => $this->siswaModel->getSiswaById($idSiswa)
      ];

      return view('admin/absen/ubah-kehadiran-modal', $data);
   }

   public function ubahKehadiran()
   {
      // ambil variabel POST
      $idKehadiran = $this->request->getVar('id_kehadiran');
      $idSiswa = $this->request->getVar('id_siswa');
      $idKelas = $this->request->getVar('id_kelas');
      $tanggal = $this->request->getVar('tanggal');
      $jamMasuk = $this->request->getVar('jam_masuk');
      $jamKeluar = $this->request->getVar('jam_keluar');
      $keterangan = $this->request->getVar('keterangan');

      $cek = $this->presensiSiswa->cekAbsen($idSiswa, $tanggal);

      $result = $this->presensiSiswa->updatePresensi(
         $cek == false ? NULL : $cek,
         $idSiswa,
         $idKelas,
         $tanggal,
         $idKehadiran,
         $jamMasuk ?? NULL,
         $jamKeluar ?? NULL,
         $keterangan
      );

      $response['nama_siswa'] = $this->siswaModel->getSiswaById($idSiswa)['nama_siswa'];

      if ($result) {
         $response['status'] = TRUE;
      } else {
         $response['status'] = FALSE;
      }

      return $this->response->setJSON($response);
   }


   public function resetabsensi(){
      $hapus = $this->db->query('TRUNCATE TABLE tb_presensi_siswa');
      return redirect()->to('/admin/absen-siswa');
   }


   public function laporan_harian(){
      $idkelas = $this->request->getVar('idkelas');
      $tanggal = $this->request->getVar('tanggal');
      $format = $this->request->getVar('format');
      $namakelas = $this->kelasModel->getById($idkelas);
      $thajar = $this->session->get('thajar');
      $query = $this->presensiSiswa->getPresensiByKelasTanggal($idkelas, $tanggal,$thajar);

      $queryset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
      $lewat = Time::parse($tanggal)->isAfter(Time::today());

      // var_dump($idkelas);

      $data = [
         'namakelas'=>'',
         'query'=>$query,
         'tanggal'=>$tanggal,
         'queryset'=>$queryset,
         'lewat'=>$lewat
      ];

      if($format == "excel"){
         return view('admin/absen/laporan_harian_excel',$data);
      } else{
         $mpdf = new \Mpdf\Mpdf();
         $html = view('admin/absen/laporan_harian_pdf',$data);
         $mpdf->WriteHTML($html);
         $this->response->setHeader('Content-Type', 'application/pdf');
         $mpdf->Output('laporan-absensi-siswa_'.strtolower($data['namakelas']).'.pdf','I'); // opens in browser
      }
      

   } 


   public function laporan_bulanan()
   {
      $idkelas = $this->request->getVar('idkelas');
      $bulan = $this->request->getVar('bulan');
      $tahun = $this->request->getVar('tahun');
      $format = $this->request->getVar('format');
      $namakelas = $this->kelasModel->getById($idkelas);
      $query = $this->siswaModel->getAllSiswaWithKelas($idkelas, $jurusan, $thajar);

      $data = [
         'namakelas'=>$namakelas,
         'bulan'=>$bulan,
         'tahun'=>$tahun,
         'query'=>$query,
      ];

      if($format == "excel"){
         return view('admin/absen/laporan_bulanan  _excel',$data);
      } else{
         $mpdf = new \Mpdf\Mpdf();
         $html = view('admin/absen/laporan_bulanan_pdf',$data);
         $mpdf->WriteHTML($html);
         $this->response->setHeader('Content-Type', 'application/pdf');
         $mpdf->Output('lapabsensi-bulanan-siswa_'.strtolower($data['namakelas']).'.pdf','I'); // opens in browser
      }

   }

   public function laporan_ijin(){
      $idkelas = $this->request->getVar('idkelas');
      $tanggal = $this->request->getVar('tanggal');
      $format = $this->request->getVar('format');

      $thajar = $this->session->get('thajar');

      $query = $this->ijinModel->select(
         ['tb_siswa.nis','tb_siswa.nama_siswa','ijin.*']
     )
     ->join('tb_siswa','tb_siswa.id_siswa = ijin.id_siswa','INNER')
     ->join('tb_siswa_trans','tb_siswa_trans.id_siswa = ijin.id_siswa','LEFT')
     ->join('tb_kelas','tb_kelas.kode = tb_siswa_trans.kode_kelas','LEFT')
      ->where('DATE(tanggal)',$tanggal)
      ->where('tb_kelas.kode',$idkelas)
      ->where('tb_siswa_trans.th_ajar',$thajar)
      ->orderBy('ijin.id','ASC')
      ->get()
      ->getResultArray();

      // var_dump($tanggal);
      
      $queryset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();

      $data = [
         'namakelas'=>'',
         'query'=>$query,
         'tanggal'=>$tanggal,
         'queryset'=>$queryset,
      ];

      if($format == "excel"){
         return view('admin/absen/laporan_ijin_excel',$data);
      } else{
         $mpdf = new \Mpdf\Mpdf();
         $html = view('admin/absen/laporan_ijin_pdf',$data);
         $mpdf->WriteHTML($html);
         $this->response->setHeader('Content-Type', 'application/pdf');
         $mpdf->Output('laporan-ijin-siswa.pdf','I'); // opens in browser
      }
      
   }
}
