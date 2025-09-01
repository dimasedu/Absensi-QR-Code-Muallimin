<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use App\Models\BroadcastModel;
use App\Models\SiswaModel;
use PhpParser\Node\Expr\Empty_;

class BroadcastController extends BaseController
{
    protected $db;
    protected $bcModel;
    protected $siswaModel;
    protected $brodcastValidation = [
        'filefoto' => [
				'rules' => 'mime_in[filefoto,image/jpg,image/jpeg,image/gif,image/png, image/webp]|max_size[filefoto,250]',
				'errors' => [
					
					'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png, webp',
					'max_size' => 'Ukuran File Maksimal 200 KB'
				]
 
         ],
      ];

    public function __construct()
    {
        $this->db = Database::connect();
        $this->bcModel = new BroadcastModel();
        $this->siswaModel = new SiswaModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $query = $this->bcModel->select([
            'broadcast.*',
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'
        ])
        ->join('tb_kelas','tb_kelas.id_kelas = broadcast.id_kelas','LEFT')
        ->join('tb_jurusan','tb_jurusan.id = tb_kelas.id_jurusan','LEFT')
        ->orderBy('created_at','DESC');

        $data = [
            'title' => 'Broadcast WA',
            'ctx' => 'broadcast',
            'query' => $query->paginate(30, 'broadcast'),
            'pager' => $query->pager, 
            'nomor' => nomor($this->request->getVar('page_broadcast'), 30),
            'total' => $query->countAll()
         ];
   
         return view('broadcast/index', $data);
    }


    public function create()
    {
        $kelas = $this->db->table('tb_kelas')
        ->select(['tb_kelas.*','tb_jurusan.jurusan'])
        ->join('tb_jurusan','tb_jurusan.id = tb_kelas.id_jurusan','INNER')
        ->where('tb_kelas.deleted_at',NULL)
        ->orderBy('kelas','ASC')
        ->get()
        ->getResult();


        $data = [
            'title' => 'Tambah Baru - Broadcast WA',
            'ctx' => 'broadcast',
            'kelas' => $kelas,
         ];
   
         return view('broadcast/create', $data);
    }


    public function store()
    {
        if (!$this->validate($this->brodcastValidation)) {
            
            $kelas = $this->db->table('tb_kelas')
            ->select(['tb_kelas.*','tb_jurusan.jurusan'])
            ->join('tb_jurusan','tb_jurusan.id = tb_kelas.id_jurusan','INNER')
            ->where('tb_kelas.deleted_at',NULL)
            ->orderBy('kelas','ASC')
            ->get()
            ->getResult();


            $data = [
                'title' => 'Tambah Baru - Broadcast WA',
                'ctx' => 'broadcast',
                'kelas' => $kelas,
                'validation' => $this->validator,
                'oldInput' => $this->request->getVar()
            ];
    
            return view('broadcast/create', $data);
        } else{

            $idkelas = $this->request->getVar('kelas');
            $bcid = md5(date('Y-m-d H:i:s'));
            $thajar = $this->session->get('thajar');

            if($idkelas != "gr"){
                $querykontak = $this->siswaModel->getSiswaByKelas($idkelas, $thajar);  
                $jmlkontak = count($querykontak);
                $tipe_person = 'siswa';
                 foreach($querykontak as $row){
             
                    $querydetail = $this->db->table('broadcast_detail')->insert([
                        'bc_id'=>$bcid,
                        'tujuan'=>$row['no_hp'],
                        'id_person'=>$row['id_siswa'],
                        'tipe_person'=>'siswa',
                        'is_sent'=>'N',
                        'created_at'=>date('Y-m-d H:i:s')
                    ]);
            } 
                
            }  else {
                $querykontak = $this->db->table("tb_guru")->select(['tb_guru.id_guru as id','tb_guru.no_hp'])->get()->getResultArray();    
                $jmlkontak = $this->db->table('tb_guru')->countAllResults();
                $tipe_person = 'guru';
                
                foreach($querykontak as $row){
                $querydetail = $this->db->table('broadcast_detail')->insert([
                        'bc_id'=>$bcid,
                        'tujuan'=>$row['no_hp'],
                        'id_person'=>$row['id'],
                        'tipe_person'=>'guru',
                        'is_sent'=>'N',
                        'created_at'=>date('Y-m-d H:i:s')
                    ]);
                }    
            }
            
            // var_dump($querykontak);
            

           

            if(!empty($_FILES['filefoto']['name'])){
                $dataBerkas = $this->request->getFile('filefoto');
                $fileName = $dataBerkas->getRandomName();
                $dataBerkas->move('public/uploads/broadcast', $fileName);
            } else {
                $fileName = '';
            }


            $pesan = preg_replace("/\r\n|\r|\n/", '%0a', $this->request->getVar('pesan'));
            $query = $this->bcModel->insert([
                'bc_id'=>$bcid,
                'judul'=>$this->request->getVar('judul'),
                'id_kelas'=>$idkelas,
                'isi_pesan'=>$pesan,
                'total_kontak'=>$jmlkontak,
                'sent_at'=>date('Y-m-d H:i:s',strtotime($this->request->getVar('tanggal'))),
                'is_finished'=>'N',
                'gambar'=>$fileName,
                'created_at'=>date('Y-m-d H:i:s')
            ]);
        

            session()->setFlashdata([
                'msg'=>'Broadcast baru berhasil ditambahkan',
                'error'=>false
            ]);

            return redirect('admin/broadcast');

        }
    }


    public function detail($id){
        
        $query = $this->db->table('broadcast')
        ->select(['broadcast.*','tb_kelas.kelas','tb_jurusan.jurusan'])
        ->join('tb_kelas','tb_kelas.id_kelas = broadcast.id_kelas','LEFT')
        ->join('tb_jurusan','tb_jurusan.id = tb_kelas.id_jurusan','LEFT')
        ->where('bc_id',$id)
        ->get()
        ->getRow();

        $sukses = $this->db->table('broadcast_detail')->where('bc_id',$id)->where('is_sent','Y')->countAllResults();
        $gagal = $this->db->table('broadcast_detail')->where('bc_id',$id)->where('is_sent','F')->countAllResults();
        $pending = $this->db->table('broadcast_detail')->where('bc_id',$id)->where('is_sent','N')->countAllResults();

        if($query->id_kelas != "gr"){
            $qdetail = $this->db->table('broadcast_detail')
            ->select(['broadcast_detail.*','tb_siswa.nama_siswa as nama'])
            ->join('tb_siswa','tb_siswa.id_siswa = broadcast_detail.id_person','INNER')
            ->where('bc_id',$id)
            ->orderBy('id','ASC')
            ->get()
            ->getResult();
        } else {
            $qdetail = $this->db->table('broadcast_detail')
            ->select(['broadcast_detail.*','tb_guru.nama_guru as nama'])
            ->join('tb_guru','tb_guru.id_guru = broadcast_detail.id_person','INNER')
            ->where('bc_id',$id)
            ->orderBy('id','ASC')
            ->get()
            ->getResult();
        }
        

        $data = [
            'title' => 'Broadcast WA',
            'ctx' => 'broadcast',
            'query'=>$query,
            'sukses'=>$sukses,
            'gagal'=>$gagal,
            'pending'=>$pending,
            'qdetail'=>$qdetail
         ];
   
         return view('broadcast/detail', $data);
    }

    public function destroy($id){
        $del_detail = $this->db->table('broadcast_detail')->where('bc_id',$id)->delete();
        $del_bc = $this->db->table('broadcast')->where('bc_id',$id);
        $databc = $del_bc->get()->getRow();

        // if(!empty($databc->gambar)){

        // }
        // if(file_exists("public/uploads/broadcast/".$databc->gambar)){
        //     @unlink("/public/uploads/broadcast/".$databc->gambar);
        // }

        $hapusbc = $this->db->table('broadcast')->where('bc_id',$id)->delete();
        

        session()->setFlashdata([
            'msg'=>'Hapus data berhasil',
            'error'=>false
        ]);

        return redirect('admin/broadcast');
    }


    public function resend($id){
        $query_detail = $this->db->table('broadcast_detail')->where('id',$id)->get()->getRow();
        if(!empty($query_detail)){
            $query = $this->db->table('broadcast')->where('bc_id',$query_detail->bc_id)->get()->getRow();

            $kirim = $this->kirimwasender($query->isi_pesan,$query_detail->tujuan,$query->gambar);

            $pola = json_decode($kirim);
            $data = $this->db->query("UPDATE broadcast_detail SET is_sent = 'Y' WHERE id = '$query_detail->id'");
                session()->setFlashdata([
                    'msg'=>'Pesan telah berhasil dikirimkan',
                    'error'=>false
                ]);
            
            // if(isset($pola->error)){
            //     $data = $this->db->query("UPDATE broadcast_detail SET is_sent = 'F' WHERE id = '$query_detail->id'");
            //     session()->setFlashdata([
            //         'msg'=>'Pesan gagal dikirimkan. Silahkan coba lagi',
            //         'error'=>true
            //     ]);
            // }

            // if(isset($pola->message_status) && $pola->message_status =="Success"){
            //     $data = $this->db->query("UPDATE broadcast_detail SET is_sent = 'Y' WHERE id = '$query_detail->id'");
            //     session()->setFlashdata([
            //         'msg'=>'Pesan telah berhasil dikirimkan',
            //         'error'=>false
            //     ]);
            // } else {
            //     $data = $this->db->query("UPDATE broadcast_detail SET is_sent = 'F' WHERE id = '$query_detail->id'");
            //     session()->setFlashdata([
            //         'msg'=>'Pesan gagal dikirimkan. Silahkan coba lagi',
            //         'error'=>false
            //     ]);
            // }
        }
        

        return redirect()->back();

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
