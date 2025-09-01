<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PengaturanController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }


    public function index()
    {
        $query = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
        $data = [
            'title' => 'Pengaturan Sistem',
            'ctx' => 'pengaturan',
            'query'=>$query
         ];
   
         return view('admin/pengaturan/index', $data);
    }


    public function update(){

        if(!empty($_FILES['filettd']['name'])){
            $datattd = $this->request->getFile('filettd');
            $filettd = $datattd->getRandomName();
            $datattd->move('public/uploads/ttd/', $filettd);
        } else {
            $filettd = $this->request->getVar('filettdlama');
        }


        if(!empty($_FILES['filelogo1']['name'])){
            $datalogo1 = $this->request->getFile('filelogo1');
            $filelogo1 = $datalogo1->getRandomName();
            $datalogo1->move('public/assets/kapel/', $filelogo1);
        } else {
            $filelogo1 = $this->request->getVar('filelogo1lama');
        }


        if(!empty($_FILES['filelogo2']['name'])){
            $datalogo2 = $this->request->getFile('filelogo2');
            $filelogo2 = $datalogo2->getRandomName();
            $datalogo2->move('public/assets/kapel/', $filelogo2);
        } else {
            $filelogo2 = $this->request->getVar('filelogo2lama');
        }


        if(!empty($_FILES['filebackground']['name'])){
            $databackground = $this->request->getFile('filebackground');
            $filebackground = $databackground->getRandomName();
            $databackground->move('public/assets/kapel/', $filebackground);
        } else {
            $filebackground = $this->request->getVar('filebackgroundlama');
        }


        if(!empty($_FILES['filestempel']['name'])){
            $datastempel = $this->request->getFile('filestempel');
            $filestempel = $datastempel->getRandomName();
            $datastempel->move('public/uploads/ttd/', $filestempel);
        } else {
            $filestempel = $this->request->getVar('filestempellama');
        }


        $query = $this->db->table('pengaturan')
        ->where('id',1)
        ->update([
            'thajar_aktif'=>$this->request->getVar('thajar'),
            'nama_sekolah'=>$this->request->getVar('nama'),
            'alamat'=>$this->request->getVar('alamat'),
            'no_telp'=>$this->request->getVar('telp'),
            'email'=>$this->request->getVar('email'),
            'kota'=>$this->request->getVar('kota'),
            'website'=>$this->request->getVar('website'),
            'logo1'=>$filelogo1,
            'logo2'=>$filelogo2,
            'nama_kepsek'=>$this->request->getVar('kepsek'),
            'nip_kepsek'=>$this->request->getVar('nip'),
            'ttd_kepsek'=>$filettd,
            'url_api'=>$this->request->getVar('url'),
            'app_key'=>$this->request->getVar('appkey'),
            'auth_key'=>$this->request->getVar('authkey'),
            'background_kartu'=>$filebackground,
            'stempel'=>$filestempel,
            'updatedAt'=>date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata([
            'msg' => 'Pengaturan Berhasil Disimpan',
            'error' => false
         ]);
         return redirect()->to('/admin/pengaturan');
    }


    public function pesan()
    {
        $query = $this->db->table('pengaturan')->where('id',1)->get()->getRow();
        $data = [
            'title' => 'Pengaturan Pesan',
            'ctx' => 'pengaturanpesan',
            'query'=>$query
         ];
   
         return view('admin/pengaturan/pesan', $data);
    }


    public function updatepesan(){
        $query = $this->db->table('pengaturan')
        ->where('id',1)
        ->update([
            'pesan_registrasi'=>$this->request->getVar('pesanregistrasi'),
            'pesan_masuk_siswa'=>$this->request->getVar('absensimasuksiswa'),
            'pesan_keluar_siswa'=>$this->request->getVar('absensipulangsiswa'),
            'pesan_masuk_guru'=>$this->request->getVar('absensimasukguru'),
            'pesan_keluar_guru'=>$this->request->getVar('absensipulangguru'),
            'pesan_ijin'=>$this->request->getVar('ijin'),
            'updatedAt'=>date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata([
            'msg' => 'Pengaturan Berhasil Disimpan',
            'error' => false
         ]);
         return redirect()->to('/admin/pengaturanpesan');
    }
}
