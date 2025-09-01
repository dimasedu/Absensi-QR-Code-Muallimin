<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Guru extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];


    protected $attributes = [
        'nuptk' => null,
        'nama_guru' => null,
        'tempat_lahir' => null,
        'tanggal_lahir' => null,
        'alamat' => null,
        'status_guru' => null,
        'jabatan' => null,
        'jenis_kelamin' => null,
        'no_hp'=> null

    ];

    public function setNuptk(string $nuptk): self
    {
        $this->attributes['nuptk'] = strtoupper($nuptk);
        return $this;
    }

    public function setNama(string $nama): self
    {
        $this->attributes['nama'] = $nama;
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->attributes['status'] = $status;
        return $this;
    }

    public function setJabatan(string $jabatan): self
    {
        $this->attributes['jabatan'] = $jabatan;
        return $this;
    }

    public function setAlamat(string $alamat): self
    {
        $this->attributes['alamat'] = $alamat;
        return $this;
    }

    public function setNohp(string $nohp): self
    {
        $this->attributes['nohp'] = $nohp;
        return $this;
    }

    public function setJenkel(string $jenkel): self
    {
        $this->attributes['jenkel'] = $jenkel;
        return $this;
    }


    public function setTempatLahir(string $tempatlahir): self
    {
        $this->attributes['tempat_lahir'] = $tempatlahir;
        return $this;
    }

    public function setTanggalLahir(string $tangallahir): self
    {
        $this->attributes['tanggal_lahir'] = $tanggallahir;
        return $this;
    }

}
