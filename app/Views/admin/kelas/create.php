<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Form Tambah Kelas</b></h4>
          </div>
          <div class="card-body mx-5 my-3">

            <form action="<?= base_url('admin/kelas'); ?>" method="post" class="form-default">
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
                <label for="kode">Kode Kelas</label>
                <input type="text" id="kode" class="form-control <?= $validation->getError('kode') ? 'is-invalid' : ''; ?>" name="kode" placeholder="Masukkan Kode Kelas (Tanpa Spasi)" , value="<?= old('kode') ?? $oldInput['kode']  ?? '' ?>" required>
                <div class="invalid-feedback">
                  <?= $validation->getError('kode'); ?>
                </div>
              </div>

              <div class="form-group mt-4">
                <label for="kelas">Kelas / Tingkat</label>
                <input type="text" id="kelas" class="form-control <?= $validation->getError('kelas') ? 'is-invalid' : ''; ?>" name="kelas" placeholder="'X', 'XI', '11'" , value="<?= old('kelas') ?? $oldInput['kelas']  ?? '' ?>" required>
                <div class="invalid-feedback">
                  <?= $validation->getError('kelas'); ?>
                </div>
              </div>
              <div class="row">
                <div class="col-12 form-group mt-4">
                  <label for="id_jurusan">Jurusan</label>
                  <select class="custom-select <?= $validation->getError('id_jurusan') ? 'is-invalid' : ''; ?>" id="id_jurusan" name="id_jurusan">
                    <option value="">--Pilih Jurusan--</option>
                    <?php foreach ($jurusan as $value) : ?>
                      <option value="<?= $value['id']; ?>" <?= old('id_jurusan') ?? $oldInput['id_jurusan'] ?? '' == $value['id'] ? 'selected' : ''; ?>>
                        <?= $value['jurusan']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    <?= $validation->getError('id_jurusan'); ?>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12 form-group mt-4">
                  <label for="id_wali">Walikelas</label>
                  <select class="custom-select <?= $validation->getError('id_wali') ? 'is-invalid' : ''; ?>" id="id_wali" name="id_wali">
                    <option value="">--Pilih Wali--</option>
                    <?php foreach ($wali as $value) : ?>
                      <option value="<?= $value['id_guru']; ?>" <?= old('id_wali') ?? $oldInput['id_wali'] ?? '' == $value['id_guru'] ? 'selected' : ''; ?>>
                        <?= $value['nama_guru']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    <?= $validation->getError('id_wali'); ?>
                  </div>
                </div>
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