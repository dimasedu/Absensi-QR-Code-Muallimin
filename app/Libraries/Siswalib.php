<?php
namespace App\Libraries;
use App\Models\KelasModel;

class Siswalib{

    protected $db;
    protected $kelasmodel;
    function __construct(){
        $this->db = \Config\Database::connect();
        $this->kelasmodel = new KelasModel();
        $this->session = \Config\Services::session();
    }


    public function get_kelas($idsiswa){
        $thajar = $this->session->get('thajar');
        $query = $this->kelasmodel->select([
            'tb_kelas.kelas',
            'tb_jurusan.jurusan'
        ])
        ->join('tb_jurusan','tb_jurusan.id = tb_kelas.id_jurusan','LEFT')
        ->join('tb_siswa_trans','tb_siswa_trans.kode_kelas = tb_kelas.kode')
        ->where('tb_siswa_trans.th_ajar',$thajar)
        ->where('tb_siswa_trans.id_siswa',$idsiswa)
        ->first();

        if(!empty($query)){
            $kelas = $query['kelas'].' '.$query['jurusan'];
        } else {
            $kelas = '';
        }

        return $kelas;
    }


    public function get_kelas_id($idsiswa){
        $thajar = $this->session->get('thajar');
        $query = $this->kelasmodel->select([
            'tb_kelas.id_kelas',
        ])
        ->join('tb_siswa_trans','tb_siswa_trans.kode_kelas = tb_kelas.kode')
        ->where('tb_siswa_trans.th_ajar',$thajar)
        ->where('tb_siswa_trans.id_siswa',$idsiswa)
        ->first();

        if(!empty($query)){
            $kelas = $query['id_kelas'];
        } else {
            $kelas = '0';
        }

        return $kelas;
    }


    public function get_absensi($idsiswa,$tanggal,$tipe){
        $query = $this->db->table('tb_presensi_siswa')
        ->select('jam_masuk, jam_keluar')
        ->where('DATE(tanggal)',$tanggal)
        ->get()
        ->getRow();

        if(!empty($query)){
            $jam_masuk = $query->jam_masuk;
            $jam_keluar = $query->jam_keluar;
        } else {
            $jam_masuk = '';
            $jam_keluar = '';
        }

        if($tipe == "M"){
            return $jam_masuk;
        } else {
            return $jam_keluar;
        }
        
    }

}