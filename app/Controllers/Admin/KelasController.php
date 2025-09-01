<?php

namespace App\Controllers\Admin;

use App\Models\JurusanModel;
use App\Models\KelasModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class KelasController extends ResourceController
{
    protected KelasModel $kelasModel;

    protected JurusanModel $jurusanModel;
    protected $db;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->jurusanModel = new JurusanModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'title' => 'Kelas & Jurusan',
            'ctx' => 'kelas',
        ];

        return view('admin/kelas/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $result = $this->kelasModel
            ->select(['tb_kelas.*','tb_jurusan.jurusan','tb_guru.nama_guru'])
            ->join('tb_guru', 'tb_guru.id_guru = tb_kelas.id_walikelas', 'LEFT')
            ->join('tb_jurusan', 'tb_kelas.id_jurusan = tb_jurusan.id', 'LEFT')
            ->findAll();

        $data = [
            'data' => $result,
            'empty' => empty($result)
        ];

        return view('admin/kelas/list-kelas', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $jurusan = $this->jurusanModel->findAll();
        $wali = $this->db->table('tb_guru')->orderBy('nama_guru','ASC')->get()->getResultArray();

        $data = [
            'ctx' => 'kelas',
            'jurusan' => $jurusan,
            'title' => 'Tambah Data Kelas',
            'wali'=>$wali
        ];
        return view('/admin/kelas/create', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if (!$this->validate([
            'kelas' => [
                'rules' => 'required|max_length[32]',
            ],
            'id_jurusan' => [
                'rules' => 'required|numeric',
            ],
        ])) {
            $jurusan = $this->jurusanModel->findAll();

            $data = [
                'ctx' => 'kelas',
                'jurusan' => $jurusan,
                'title' => 'Tambah Data Kelas',
                'validation' => $this->validator,
                'oldInput' => $this->request->getVar()
            ];
            return view('/admin/kelas/create', $data);
        }

        // ambil variabel POST
        $kode = trim(strtoupper($this->request->getVar('kode')));
        $kelas = $this->request->getVar('kelas');
        $idJurusan = $this->request->getVar('id_jurusan');
        $idWali = $this->request->getVar('id_wali');

        $result = $this->kelasModel->tambahKelas($kode,$kelas, $idJurusan,$idWali);

        if ($result) {
            session()->setFlashdata([
                'msg' => 'Tambah data berhasil',
                'error' => false
            ]);
            return redirect()->to('/admin/kelas');
        }

        session()->setFlashdata([
            'msg' => 'Gagal menambah data',
            'error' => true
        ]);
        return redirect()->to('/admin/kelas/create');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $kelas = $this->kelasModel->where(['id_kelas' => $id])->first();

        if (!$kelas) {
            throw new PageNotFoundException('Data kelas dengan id ' . $id . ' tidak ditemukan');
        }

        $jurusan = $this->jurusanModel->findAll();
        $wali = $this->db->table('tb_guru')->orderBy('nama_guru','ASC')->get()->getResultArray();
        $data = [
            'ctx' => 'kelas',
            'data' => $kelas,
            'jurusan' => $jurusan,
            'title' => 'Edit Kelas',
            'wali'=>$wali
        ];
        return view('/admin/kelas/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        if (!$this->validate([
            'kelas' => [
                'rules' => 'required|max_length[32]',
            ],
            'id_jurusan' => [
                'rules' => 'required|numeric',
            ],
        ])) {
            $jurusan = $this->jurusanModel->findAll();

            $kelas = $this->kelasModel->where(['id_kelas' => $id])->first();

            if (!$kelas) {
                throw new PageNotFoundException('Data kelas dengan id ' . $id . ' tidak ditemukan');
            }

            $data = [
                'ctx' => 'kelas',
                'jurusan' => $jurusan,
                'title' => 'Edit Kelas',
                'data' => $kelas,
                'validation' => $this->validator,
                'oldInput' => $this->request->getRawInput()
            ];
            return view('/admin/kelas/edit', $data);
        }

        // ambil variabel POST
        $kelas = $this->request->getRawInputVar('kelas');
        $idJurusan = $this->request->getRawInputVar('id_jurusan');
        $idWali = $this->request->getRawInputVar('id_wali');

        $result = $this->kelasModel->update($id, [
            'kelas' => $kelas,
            'id_jurusan' => $idJurusan,
            'id_walikelas'=>$idWali
        ]);

        if ($result) {
            session()->setFlashdata([
                'msg' => 'Edit data berhasil',
                'error' => false
            ]);
            return redirect()->to('/admin/kelas');
        }

        session()->setFlashdata([
            'msg' => 'Gagal mengubah data',
            'error' => true
        ]);
        return redirect()->to('/admin/kelas/' . $id . '/edit');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $result = $this->kelasModel->delete($id);

        if ($result) {
            session()->setFlashdata([
                'msg' => 'Data berhasil dihapus',
                'error' => false
            ]);
            return redirect()->to('/admin/kelas');
        }

        session()->setFlashdata([
            'msg' => 'Gagal menghapus data',
            'error' => true
        ]);
        return redirect()->to('/admin/kelas');
    }
}
