<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\IjinModel;
use Config\Database;
use App\Models\SiswaModel;
use App\Libraries\Siswalib;
use CodeIgniter\HTTP\Request;
use PhpParser\Node\Scalar\String_;

class IjinController extends BaseController
{
    protected $ijinModel;
    protected $siswaModel;
    protected $db;

    protected $ijinValidation = [
        'filefoto' => [
				'rules' => 'mime_in[filefoto,image/jpg,image/jpeg,image/gif,image/png, image/webp, application/pdf]|max_size[filefoto,2048]',
				'errors' => [
					
					'mime_in' => 'File Extention Harus Berupa Gambar (jpg,jpeg,gif,png, webp) dan Dokumen(PDF)',
					'max_size' => 'Ukuran File Maksimal 2 MB'
				]
 
         ],
      ];

    public function __construct()
    {
        $this->ijinModel = new IjinModel();
        $this->siswaModel = new SiswaModel();
        $this->db = Database::connect();
        $this->session = \Config\Services::session();
        $this->siswalib = new Siswalib();
    }

    public function index()
    {
        $query = $this->ijinModel->select(
            ['tb_siswa.nis','tb_siswa.nama_siswa','ijin.*']
        )
        ->join('tb_siswa','tb_siswa.id_siswa = ijin.id_siswa','INNER')
    
        ->orderBy('ijin.id','DESC');

        $data = [
            'title' => 'Daftar Izin/Sakit',
            'ctx' => 'absensi-ijin',
            'query' => $query->paginate(30, 'absensi'),
            'pager' => $query->pager, 
            'nomor' => nomor($this->request->getVar('page_absensi'), 30),
            'total' => $query->countAll()
         ];
   
         return view('ijin/index', $data);
    }


    public function create()
    {
        $siswa = $this->siswaModel->select([
            'tb_siswa.nis',
            'tb_siswa.nama_siswa',
            'tb_siswa.id_siswa',
            'tb_siswa.no_hp',
            'tb_siswa.foto',
            'tb_siswa.id_kelas',
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'
        ])
        ->join(
            'tb_kelas',
            'tb_kelas.id_kelas = tb_siswa.id_kelas',
            'LEFT'
         )
         ->join(
            'tb_jurusan',
            'tb_jurusan.id = tb_kelas.id_jurusan',
            'LEFT'
         )
         ->orderBy('tb_siswa.nama_siswa','ASC')
         ->get()
         ->getResult();

        
        $data = [
            'title' => 'Tambah Baru - Data Ijin / Sakit',
            'ctx' => 'absensi-ijin',
            'siswa' => $siswa,
         ];
   
         return view('ijin/create', $data);
    }

    public function store() {
        $querset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
        $pesan = $querset->pesan_ijin;
        if (!$this->validate($this->ijinValidation)) {
            $siswa = $this->siswaModel->select([
                'tb_siswa.nis',
                'tb_siswa.nama_siswa',
                'tb_siswa.id_siswa',
                'tb_siswa.no_hp',
                'tb_siswa.foto',
                'tb_siswa.id_kelas',
                'tb_kelas.kelas',
                'tb_jurusan.jurusan'
            ])
            ->join(
                'tb_kelas',
                'tb_kelas.id_kelas = tb_siswa.id_kelas',
                'LEFT'
             )
             ->join(
                'tb_jurusan',
                'tb_jurusan.id = tb_kelas.id_jurusan',
                'LEFT'
             )
             ->orderBy('tb_siswa.nama_siswa','ASC')
             ->get()
             ->getResult();
    
            
            $data = [
                'title' => 'Tambah Baru - Data Izin / Sakit',
                'ctx' => 'absensi-ijin',
                'siswa' => $siswa,
                'validation' => $this->validator,
                'oldInput' => $this->request->getVar()
             ];
       
             return view('ijin/create', $data);

        } else {
            if(!empty($_FILES['filefoto']['name'])){
                $dataBerkas = $this->request->getFile('filefoto');
                $fileName = $dataBerkas->getRandomName();
                $dataBerkas->move('public/uploads/ijin', $fileName);
            } else {
                $fileName = '';
            }

            $siswax = explode("|",$this->request->getVar('siswa'));

            $simpan = $this->ijinModel->insert([
                'id_siswa'=>$siswax[0],
                'tanggal'=>$this->request->getVar('tanggal'),
                'tipe'=>$this->request->getVar('tipe'),
                'keterangan'=>$this->request->getVar('pesan'),
                'file_ijin'=>$fileName,
                'th_ajar'=>$this->session->get('thajar'),
                'created_at'=>date('Y-m-d H:i:s')
            ]);

            //INPUT KE TABLE OUTBOX
            $ambil_wali = $this->ambil_wali($siswax[2]);
            $ambil_siswa = $this->ambil_siswa($siswax[0]);

            $pesanex = str_replace("[GURU]",$ambil_wali->nama_guru, $querset->pesan_ijin);
            $pesanex1 = str_replace("[WAKTU]",$this->request->getVar('tanggal'),$pesanex);
            $pesanex2 = str_replace("[NISN]",$ambil_siswa->nis,$pesanex1);
            $pesanex3 = str_replace("[NAMA]",$ambil_siswa->nama_siswa,$pesanex2);
            $pesanex4 = str_replace("[KELAS]",$this->siswalib->get_kelas($siswax[0]),$pesanex3);
            $pesanex5 = str_replace("[TIPE]",$this->request->getVar('tipe') == "I" ? 'Ijin' : 'Sakit',$pesanex4);
            $pesanex6 = str_replace("[KETERANGAN]",$this->request->getVar('keterangan'),$pesanex5);


            $outbox = $this->db->table('outbox')->insert([
                'tipe'=>'ijin',
                'tujuan'=>$ambil_wali->no_hp,
                'person_id'=>$ambil_wali->id_guru,
                'person_tipe'=>'guru',
                'isi_pesan'=>$pesanex6,
                'created_at'=>date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata([
                'msg'=>'Permohonan izin berhasil dikirimkan',
                'error'=>false
            ]);

            return redirect('admin/absensi-izin');
        }
    }


    public function edit($id){
        $query = $this->db->table('ijin')->where('id',$id)->get()->getRow();
        $siswa = $this->siswaModel->select([
            'tb_siswa.nis',
            'tb_siswa.nama_siswa',
            'tb_siswa.id_siswa',
            'tb_siswa.no_hp',
            'tb_siswa.foto',
            'tb_siswa.id_kelas',
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'
        ])
        ->join(
            'tb_kelas',
            'tb_kelas.id_kelas = tb_siswa.id_kelas',
            'LEFT'
         )
         ->join(
            'tb_jurusan',
            'tb_jurusan.id = tb_kelas.id_jurusan',
            'LEFT'
         )
         ->orderBy('tb_siswa.nama_siswa','ASC')
         ->get()
         ->getResult();

        $data = [
            'title' => 'Ubah - Data Izin / Sakit',
            'ctx' => 'absensi-ijin',
            'query'=>$query,
            'siswa' => $siswa,
         ];
   
         return view('ijin/edit', $data);
    }


    public function update()
    {
        $querset = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
        if(!empty($_FILES['filefoto']['name'])){
            $dataBerkas = $this->request->getFile('filefoto');
            $fileName = $dataBerkas->getRandomName();
            $dataBerkas->move('public/uploads/ijin', $fileName);
        } else {
            $fileName = $this->request->getVar('filelama');
        }

        $siswax = explode("|",$this->request->getVar('siswa'));

        $simpan = $this->ijinModel
        // ->where('id',$this->request->getVar('id'))
        ->update($this->request->getVar('id'),[
            'id_siswa'=>$this->request->getVar('siswa'),
            'tanggal'=>$this->request->getVar('tanggal'),
            'tipe'=>$this->request->getVar('tipe'),
            'keterangan'=>$this->request->getVar('pesan'),
            'file_ijin'=>$fileName,
            'update_at'=>date('Y-m-d H:i:s')
        ]);


         //INPUT KE TABLE OUTBOX
         $ambil_wali = $this->ambil_wali($siswax[2]);
         $ambil_siswa = $this->ambil_siswa($siswax[0]);

         $pesanex = str_replace("[GURU]",$ambil_wali->nama_guru, $querset->pesan_ijin);
         $pesanex1 = str_replace("[WAKTU]",$this->request->getVar('tanggal'),$pesanex);
         $pesanex2 = str_replace("[NISN]",$ambil_siswa->nis,$pesanex1);
         $pesanex3 = str_replace("[NAMA]",$ambil_siswa->nama_siswa,$pesanex2);
         $pesanex4 = str_replace("[KELAS]",$this->siswalib->get_kelas($siswax[0]),$pesanex3);
         $pesanex5 = str_replace("[TIPE]",$this->request->getVar('tipe') == "I" ? 'Ijin' : 'Sakit',$pesanex4);
         $pesanex6 = str_replace("[KETERANGAN]",$this->request->getVar('keterangan'),$pesanex5);


         $outbox = $this->db->table('outbox')->insert([
             'tipe'=>'ijin',
             'tujuan'=>$ambil_wali->no_hp,
             'person_id'=>$ambil_wali->id_guru,
             'person_tipe'=>'guru',
             'isi_pesan'=>"*(UPDATE DATA IJIN/SAKIT)*%0a %0a".$pesanex6,
             'created_at'=>date('Y-m-d H:i:s')
         ]);

        session()->setFlashdata([
            'msg'=>'Ubah data permohonan Izin berhasil.',
            'error'=>false
        ]);

        return redirect('admin/absensi-izin');
    }


    public function destroy($id)
    {
        $query = $this->ijinModel->where('id',$id)->delete();
        session()->setFlashdata([
            'msg'=>'Permohonan izin berhasil dihapus',
            'error'=>false
        ]);

        return redirect('admin/absensi-izin');
    }

    public function detail($id){
        $query = $this->ijinModel->select(
            ['tb_siswa.nis','tb_siswa.nama_siswa','ijin.*','tb_kelas.kelas','tb_jurusan.jurusan']
        )
        ->join('tb_siswa','tb_siswa.id_siswa = ijin.id_siswa','INNER')
        
        ->join(
            'tb_kelas',
            'tb_kelas.id_kelas = tb_siswa.id_kelas',
            'LEFT'
         )
         ->join(
            'tb_jurusan',
            'tb_jurusan.id = tb_kelas.id_jurusan',
            'LEFT'
         );

         $data = [
            'title' => 'Detail - Data Ijin / Sakit',
            'ctx' => 'absensi-ijin',
            'query'=>$query,
         ];
   
         return view('ijin/detail', $data);
    }


    //additional modul
    public function tampil_siswa($id)
    {
        $query = $this->siswaModel->select([
            'tb_siswa.nis',
            'tb_siswa.nama_siswa',
            'tb_siswa.id_siswa',
            'tb_siswa.no_hp',
            'tb_siswa.foto',
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'
        ])
        ->join(
            'tb_kelas',
            'tb_kelas.id_kelas = tb_siswa.id_kelas',
            'LEFT'
         )
         ->join(
            'tb_jurusan',
            'tb_jurusan.id = tb_kelas.id_jurusan',
            'LEFT'
         )
         ->where('tb_siswa.id_siswa',$id)
         ->get()
         ->getRow();

        
        $data = [
            'query' => $query,
         ];
   
         return view('ijin/tampil_siswa', $data);
    }


    function ambil_wali($idkelas){       

            // $ambil_wali = $this->db->table('tb_guru')
            // ->select([
            //     'ijin.*',
            //     'tb_siswa.nis',
            //     'tb_siswa.nama_siswa',
            //     'tb_kelas.kelas',
            //     'tb_jurusan.jurusan',
            //     'tb_guru.nama_guru',
            //     'tb_guru.no_hp'
            // ])
            // ->join(
            //     'tb_siswa',
            //     'tb_siswa.id_siswa = ijin.id_siswa',
            //     'INNER'
            // )
            // ->join(
            //     'tb_kelas',
            //     'tb_kelas.id_kelas = tb_siswa.id_kelas',
            //     'INNER'
            // )
            // ->join(
            //     'tb_jurusan',
            //     'tb_siswa.id_siswa = tb_kelas.id_jurusan',
            //     'INNER'
            // )
            // ->join(
            //     'tb_guru',
            //     'tb_guru.id_guru = tb_kelas.id_walikelas',
            //     'LEFT'
            // )
            // ->where('id_walikelas',$idwali)
            // ->get()
            // ->getRow();

            $ambil_wali = $this->db->table('tb_kelas')
            ->select([
                'tb_guru.id_guru',
                'tb_guru.nama_guru',
                'tb_guru.no_hp'
            ])
            ->join(
                'tb_guru',
                'tb_guru.id_guru = tb_kelas.id_walikelas',
                'LEFT'
            )
            ->where('tb_kelas.id_kelas',$idkelas)
            ->get()
            ->getRow();

            return $ambil_wali;
    }


    function ambil_siswa($idsiswa)
    {
        $ambil_siswa = $this->db->table('tb_siswa')
            ->select([
                'tb_siswa.nis',
                'tb_siswa.nama_siswa',
            ])
            ->where('tb_siswa.id_siswa',$idsiswa)
            ->get()
            ->getRow();


            return $ambil_siswa;
    }

}
