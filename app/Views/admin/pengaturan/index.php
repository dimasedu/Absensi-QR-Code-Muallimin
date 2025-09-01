<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Pengaturan</b></h4>
          </div>
          <div class="card-body mx-5 my-3">

            <form action="<?= site_url('admin/simpanpengaturan'); ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>
              <input type="hidden" name="filettdlama" value="<?=$query->ttd_kepsek;?>">
              <input type="hidden" name="filelogo1lama" value="<?=$query->logo1;?>">
              <input type="hidden" name="filelogo2lama" value="<?=$query->logo2;?>">
              <input type="hidden" name="filebackgroundlama" value="<?=$query->background_kartu;?>">
              <input type="hidden" name="filestempellama" value="<?=$query->stempel;?>">

              <?php $validation = \Config\Services::validation(); ?>

              <?php if (session()->getFlashdata('msg')) : ?>
                <div class="pb-2">
                  <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success'  ?> ">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <i class="material-icons">close</i>
                    </button>
                    <?= session()->getFlashdata('msg') ?>
                  </div>
                </div>
              <?php endif; ?>

              <div class="form-group mt-4">
                <label for="nama">Tahun Ajar Aktif</label>
                <input type="text" id="thajar" class="form-control " name="thajar" placeholder="Masukkan Tahun Ajar" value="<?= $query->thajar_aktif;?>" required>
              </div>

              <div class="form-group mt-4">
                <label for="nama">Nama Sekolah</label>
                <input type="text" id="nama" class="form-control " name="nama" placeholder="Masukkan Nama Sekolah" value="<?= $query->nama_sekolah;?>" required>
              </div>
              <div class="form-group mt-4">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" class="form-control " name="alamat" placeholder="Masukkan Alamat Sekolah" value="<?= $query->alamat;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="telp">No. Telp</label>
                <input type="text" id="telp" class="form-control " name="telp" placeholder="Masukkan No. Telp" value="<?= $query->no_telp;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control " name="email" placeholder="Masukkan Email" value="<?= $query->email;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="kota">Kota/Kabupaten</label>
                <input type="text" id="kota" class="form-control " name="kota" placeholder="Masukkan Kota/Kabupaten" value="<?= $query->kota;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="website">Website</label>
                <input type="text" id="website" class="form-control " name="website" placeholder="Masukkan Website" value="<?= $query->website;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="kepsek">Kepala Sekolah</label>
                <input type="text" id="kepsek" class="form-control " name="kepsek" placeholder="Masukkan Kepala Sekolah" value="<?= $query->nama_kepsek;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="nip">NIP Kepala Sekolah</label>
                <input type="text" id="nip" class="form-control " name="nip" placeholder="NIP Kepala Sekolah" value="<?= $query->nip_kepsek;?>" required>
                
              </div>

                <div class="form-group mt-1">
                    <label for="ttd">File TTD<span class="text-danger"><?= $validation->getError('filettd'); ?></span></label>
                </div>
                <div class="input-group">
                    <input type="file" id="filettd" class="form-control <?= $validation->getError('filettd') ? 'is-invalid' : ''; ?>" name="filettd" placeholder="1234" accept=".jpg, .jpeg, .png" />
                </div>
                <small class="text-danger">File yang diijinkan JPG, JPEG, PNG</small><br>
                <img src="<?=base_url('public/uploads/ttd/'.$query->ttd_kepsek);?>" class="img-fluid" width="150"><br>


                <div class="form-group mt-1">
                    <label for="ttd">File Logo 1<span class="text-danger"><?= $validation->getError('filelogo1'); ?></span></label>
                </div>
                <div class="input-group">
                    <input type="file" id="filelogo1" class="form-control <?= $validation->getError('filelogo1') ? 'is-invalid' : ''; ?>" name="filelogo1" placeholder="1234" accept=".jpg, .jpeg, .png" />
                </div>
                <small class="text-danger">File yang diijinkan JPG, JPEG, PNG</small><br>
                <img src="<?=base_url('public/assets/kapel/'.$query->logo1);?>" class="img-fluid" width="150"><br>


                <div class="form-group mt-1">
                    <label for="ttd">File Logo 2<span class="text-danger"><?= $validation->getError('filelogo2'); ?></span></label>
                </div>
                <div class="input-group">
                    <input type="file" id="filelogo2" class="form-control <?= $validation->getError('filelogo2') ? 'is-invalid' : ''; ?>" name="filelogo2" placeholder="1234" accept=".jpg, .jpeg, .png" />
                </div>
                <small class="text-danger">File yang diijinkan JPG, JPEG, PNG</small><br>
                <img src="<?=base_url('public/assets/kapel/'.$query->logo2);?>" class="img-fluid" width="150"><br>

                <div class="form-group mt-1">
                    <label for="ttd">File Background Kartu Pelajar<span class="text-danger"><?= $validation->getError('filebackground'); ?></span></label>
                </div>
                <div class="input-group">
                    <input type="file" id="filebackground" class="form-control <?= $validation->getError('filebackground') ? 'is-invalid' : ''; ?>" name="filebackground" placeholder="1234" accept=".jpg, .jpeg, .png" />
                </div>
                <small class="text-danger">File yang diijinkan JPG, JPEG, PNG</small><br>
                <img src="<?=base_url('public/assets/kapel/'.$query->background_kartu);?>" class="img-fluid" width="150"><br>

                <div class="form-group mt-1">
                    <label for="ttd">File Stempel<span class="text-danger"><?= $validation->getError('filestempel'); ?></span></label>
                </div>
                <div class="input-group">
                    <input type="file" id="filestempel" class="form-control <?= $validation->getError('filestempel') ? 'is-invalid' : ''; ?>" name="filestempel" placeholder="1234" accept=".jpg, .jpeg, .png" />
                </div>
                <small class="text-danger">File yang diijinkan JPG, JPEG, PNG</small><br>
                <img src="<?=base_url('public/uploads/ttd/'.$query->stempel);?>" class="img-fluid" width="150"><br>


              <div class="form-group mt-4">
                <label for="url">URL WA API (Pastikan Gunakan Kedai WA)</label>
                <input type="text" id="url" class="form-control " name="url" placeholder="URL API" value="<?= $query->url_api;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="apikey">APP KEY</label>
                <input type="text" id="appkey" class="form-control " name="appkey" placeholder="API KEY" value="<?= $query->app_key;?>" required>
                
              </div>

              <div class="form-group mt-4">
                <label for="authkey">NO. WA SERVER</label>
                <input type="text" id="authkey" class="form-control " name="authkey" placeholder="AUTH KEY" value="<?= $query->auth_key;?>" required>
                
              </div>


              <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </form>

            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>