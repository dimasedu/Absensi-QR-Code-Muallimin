<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\SiswaModel;
use App\Models\SiswatransModel;
use App\Models\KelasModel;

class SiswatransController extends BaseController
{

    function __construct(){
        $this->siswaModel = new SiswaModel();
        $this->mutasiModel = new SiswatransModel();
        $this->kelasModel = new KelasModel();
        $this->session = \Config\Services::session();
    }


    public function index()
    {
        $query = $this->siswaModel->select([
          'tb_siswa.id_siswa',
          'tb_siswa.nis',
          'tb_siswa.nama_siswa' ,
          'tb_siswa.no_hp',
          'tb_siswa.jenis_kelamin' 
        ])
        ->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')
        ->where('tb_siswa_trans.kode_kelas',null)
        ->orderBy('tb_siswa.nama_siswa',"ASC")
        ->get()
        ->getResultArray();

        $data = [
            'title' => 'Beri Kelas Siswa',
            'ctx' => 'berikelas',
            'kelas' => $this->kelasModel->getAllKelas(),
            'thajar'=>$this->session->get('thajar'),
            'data'=>$query
         ];
   
        return view('admin/siswatrans/berikelas', $data);
    }


    public function berikelas()
    {
        $thajar = $this->request->getVar('thajar');
        $id = $this->request->getVar('id');
        $kelas = $this->request->getVar('kelas');

        if(!empty($id)){
            for($i =0; $i<count($id); $i++){

                $idx = $id[$i];
                $qsiswa = $this->siswaModel->getSiswaById($idx);
    
                $simpan = $this->mutasiModel->save([
                    'id_siswa'=>$idx,
                    'kode_kelas'=>$kelas,
                    'th_ajar'=>$thajar,
                    'tipe'=>'masuk'
                ]);
    
            }
    
            session()->setFlashdata([
                'msg' => 'Proses beri kelas siswa berhasil',
                'error' => false
            ]);

        } else {
            session()->setFlashdata([
                'msg' => '<b>Gagal!</b> silahkan pilih siswa dahulu.',
                'error' => true
            ]);
        }
        

        return redirect('admin/berikelas');
    }


    public function mutasi()
    {
        $thajar_aktif = $this->session->get('thajar');
        $ex_th = explode("/",$thajar_aktif);
        $th_a = $ex_th[0] + 1;
        $th_b = $ex_th[1] + 1;
        $thajar_berikut = $th_a.'/'.$th_b;

        $data = [
            'title' => 'Mutasi Siswa',
            'ctx' => 'mutasi',
            'kelas' => $this->kelasModel->getAllKelas(),
            'thajar_aktif'=>$thajar_aktif,
            'thajar_berikut'=>$thajar_berikut
            
         ];
   
        return view('admin/siswatrans/pindahkelas', $data);
    }


    public function mutasi_data(){
        $kelas = $this->request->getVar('kelas');
        $thajar_aktif = $this->request->getVar('thajar_aktif');
        $thajar = explode('/',$this->session->get('thajar'));
        $thajar_next = $thajar[1].'/'.($thajar[1] + 1);

        $result = $this->mutasiModel->getAllSiswaWithKelas($kelas, $thajar_aktif);

        $data = [
            'data' => $result,
            'empty' => empty($result),
            'kelasxls'=>$kelas,
            'kelas' => $this->kelasModel->getAllKelas(),
            'thajar_now'=>$this->session->get('thajar'),
            'thajar_next'=>$thajar_next
        ];

        return view('admin/siswatrans/pindahkelas_data', $data);
    }


    public function mutasi_proses()
    {
        $thajar = $this->request->getVar('thajar');
        $id = $this->request->getVar('id');
        $kelas = $this->request->getVar('kelas');
        $keterangan = $this->request->getVar('keterangan');

        if($kelas != "lulus"){
            $tipe = 'naik';
        } else {
            $tipe = 'lulus';
        }

        if(!empty($id)){
            for($i =0; $i<count($id); $i++){

                $idx = $id[$i];
                $qsiswa = $this->siswaModel->getSiswaById($idx);
    
                $simpan = $this->mutasiModel->save([
                    'id_siswa'=>$idx,
                    'kode_kelas'=>$kelas,
                    'th_ajar'=>$thajar,
                    'tipe'=>$tipe,
                    'keterangan'=>$keterangan[$i]
                ]);

                if($tipe == "lulus"){
                    $de_aktif_siswa = $this->siswaModel->update($idx,['aktif'=>'T']);
                }
    
            }
    
            session()->setFlashdata([
                'msg' => 'Proses mutasi siswa berhasil',
                'error' => false
            ]);

        } else {
            session()->setFlashdata([
                'msg' => '<b>Gagal!</b> silahkan pilih siswa dahulu.',
                'error' => true
            ]);
        }
        

        return redirect('admin/mutasi');
    }


    public function mutasi_edit(){
        $thajar = $this->session->get('thajar');
        $result = $this->siswaModel->select([
            'tb_siswa_trans.id',
            'tb_siswa_trans.th_ajar',
            'tb_siswa_trans.tipe',
            'tb_siswa.id_siswa',
            'tb_siswa.nis',
            'tb_siswa.nama_siswa',
            'tb_siswa.jenis_kelamin',
            'tb_siswa.no_hp',
            'tb_siswa.foto',
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'
          ])
            ->join('tb_siswa_trans','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')              
            ->join(
                'tb_kelas',
                'tb_kelas.kode = tb_siswa_trans.kode_kelas',
                'LEFT'
            )->join(
                'tb_jurusan',
                'tb_kelas.id_jurusan = tb_jurusan.id',
                'LEFT'
            )
          ->orderBy('tb_siswa_trans.id',"DESC")
          ->get()
          ->getResultArray();

          $data = [
            'title' => 'Edit Mutasi Siswa',
            'ctx' => 'mutasi-edit',
            'data' => $result,
            'empty' => empty($result),
            'kelas' => $this->kelasModel->getAllKelas(),
        ];

        return view('admin/siswatrans/mutasi_edit', $data);
    }


    public function mutasi_edit_ubah($id){
        $result = $this->mutasiModel->select([
            'tb_siswa_trans.*',
            'tb_siswa.nis',
            'tb_siswa.nama_siswa',
            'tb_siswa.jenis_kelamin'
        ])
        ->join('tb_siswa','tb_siswa_trans.id_siswa = tb_siswa.id_siswa','LEFT')
        ->where('tb_siswa_trans.id',$id)
        ->get()
        ->getRow();

        $thajar= explode("/",$result->th_ajar);


        $data = [
            'title' => 'Edit Mutasi Siswa',
            'ctx' => 'mutasi-edit',
            'query' => $result,
            'empty' => empty($result),
            'kelas' => $this->kelasModel->getAllKelas(),
            'thawal' => $thajar[0],
            'thakhir'=>$thajar[1]
        ];

        return view('admin/siswatrans/mutasi_edit_form', $data);
    }


    public function mutasi_edit_simpan(){
        $id = $this->request->getVar('id');
        $kelas = $this->request->getVar('kelas');
        $thawal = $this->request->getVar('thawal');
        $thakhir = $this->request->getVar('thakhir');
        $tipe = $this->request->getVar('tipe');
        $keterangan = $this->request->getVar('keterangan');
        $thajar = $thawal.'/'.$thakhir;

        if($thawal == $thakhir){
            session()->setFlashdata([
                'msg' => 'Tahun Ajar tidak boleh sama',
                'error' => true
            ]);

            return redirect()->back()->withInput();
        } else{

            $simpan = $this->mutasiModel->update($id,[
                'kode_kelas'=>$tipe == "lulus" ? 'lulus' : $kelas,
                'th_ajar'=>$thajar,
                'tipe'=>$tipe,
                'keterangan'=>$keterangan
            ]);

            if($tipe == "lulus"){
                $de_aktif_siswa = $this->siswaModel->update($id,['aktif'=>'T']);
            }

            session()->setFlashdata([
                'msg' => 'Data berhasil diubah',
                'error' => false
            ]);

            return redirect('admin/mutasi-edit');
        }
    }
    

    public function mutasi_edit_delete($id){
        $result = $this->mutasiModel->delete($id);

        if ($result) {
            session()->setFlashdata([
                'msg' => 'Data berhasil dihapus',
                'error' => false
            ]);
            return redirect()->to('/admin/mutasi-edit');
        }

        session()->setFlashdata([
            'msg' => 'Gagal menghapus data',
            'error' => true
        ]);
        return redirect()->to('/admin/mutasi-edit');
    }
}
