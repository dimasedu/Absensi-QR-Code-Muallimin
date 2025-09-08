<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Pengaturan Pesan</b></h4>
          </div>
          <div class="card-body mx-5 my-3">

            <form action="<?= site_url('admin/simpanpengaturanpesan'); ?>" method="post" enctype="multipart/form-data" class="form-default">
              <?= csrf_field() ?>

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

              

              <div class="form-group mt-0">
                <label for="url">Registrasi Siswa</label>
                <textarea name="pesanregistrasi" class="form-control" rows="5" required id="pesanregistrasi"><?=$query->pesan_registrasi;?></textarea>
                
              </div>

              <div class="form-group mt-4">
                <label for="apikey">Absensi Masuk Siswa</label>
                <textarea name="absensimasuksiswa" class="form-control" rows="5" required id="absensimasuksiswa"><?=$query->pesan_masuk_siswa;?></textarea>
                
              </div>

              <div class="form-group mt-4">
                <label for="apikey">Absensi Pulang Siswa</label>
                <textarea name="absensipulangsiswa" class="form-control" rows="5" required id="absensipulangsiswa"><?=$query->pesan_keluar_siswa;?></textarea>
                
              </div>

              <div class="form-group mt-4">
                <label for="apikey">Absensi Masuk Guru</label>
                <textarea name="absensimasukguru" class="form-control" rows="5" required id="absensimasukguru"><?=$query->pesan_masuk_guru;?></textarea>
                
              </div>


              <div class="form-group mt-4">
                <label for="apikey">Absensi Pulang Guru</label>
                <textarea name="absensipulangguru" class="form-control" rows="5" required id="absensipulangguru"><?=$query->pesan_keluar_guru;?></textarea>
                
              </div>


              <div class="form-group mt-4">
                <label for="apikey">Pesan Ijin/Sakit</label>
                <textarea name="ijin" class="form-control" rows="5" required id="ijin"><?=$query->pesan_ijin;?></textarea>
                
              </div>


              <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>