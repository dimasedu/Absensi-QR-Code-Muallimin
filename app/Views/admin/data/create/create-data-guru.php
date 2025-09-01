<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-success">
                  <h4 class="card-title"><b>Form Tambah Guru</b></h4>

               </div>
               <div class="card-body mx-5 my-3">

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

                  <form action="<?= base_url('admin/guru/create'); ?>" method="post" enctype="multipart/form-data">
                     <?= csrf_field() ?>
                     <?php $validation = \Config\Services::validation(); ?>

                     <p>Kolom bertanda <span class="text-danger">*)</span> harus diisi.</p>
                     <div class="form-group mt-4">
                        <label for="nuptk">NBM <span class="text-danger">*)</span></label>
                        <input type="text" id="nuptk" class="form-control"  name="nuptk" placeholder="Masukkan NBM" value="<?= old('nuptk') ?? $oldInput['nuptk'] ?? '' ?>" required>
                        
                     </div>

                     <div class="form-group mt-4">
                        <label for="nama">Nama Lengkap <span class="text-danger">*)</span></label>
                        <input type="text" id="nama" class="form-control" name="nama" placeholder="Masukkan Nama Lengkap" value="<?= old('nama') ?? $oldInput['nama'] ?? '' ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('nama'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*)</span></label>
                        <input type="text" id="tempat_lahir" class="form-control <?= $validation->getError('tempat_lahir') ? 'is-invalid' : ''; ?>" name="tempat_lahir" placeholder="Tempat Lahir" value="<?= old('tempat_lahir') ?? $oldInput['tempat_lahir'] ?? '' ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('tempat_lahir'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*)</span></label>
                        <input type="date" id="tanggal_lahir" class="form-control <?= $validation->getError('tanggal_lahir') ? 'is-invalid' : ''; ?>" name="tanggal_lahir" placeholder="Tanggal Lahir" value="<?= old('tanggal_lahir') ?? $oldInput['tanggal_lahir'] ?? '' ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('tanggal_lahir'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-2">
                        <label for="jk">Jenis Kelamin <span class="text-danger">*)</span></label>
                        <?php
                        if (old('jk')) {
                           $l = (old('jk') ?? $oldInput['jk'] ?? '') == '1' ? 'checked' : '';
                           $p = (old('jk') ?? $oldInput['jk'] ?? '') == '2' ? 'checked' : '';
                        }
                        ?>
                        <div class="form-check form-control pt-0 mb-1 <?= $validation->getError('jk') ? 'is-invalid' : ''; ?>">
                           <div class="row">
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="jk" id="laki" value="1" <?= $l ?? ''; ?> required>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="laki">
                                          <h6 class="text-dark">Laki-laki</h5>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="jk" id="perempuan" value="2" <?= $p ?? ''; ?> required>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="perempuan">
                                          <h6 class="text-dark">Perempuan</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="invalid-feedback">
                           <?= $validation->getError('jk'); ?>
                        </div>
                     </div>

                     

                     <div class="form-group mt-4">
                        <label for="alamat">Alamat <span class="text-danger">*)</span></label>
                        <input type="text" id="alamat" name="alamat" class="form-control" value="<?= old('alamat') ?? $oldInput['alamat'] ?? '' ?>" required>
                     </div>

                     <div class="form-group mt-4">
                        <label for="hp">No HP <span class="text-danger">*)</span></label>
                        <input type="text" id="hp" name="no_hp" class="form-control <?= $validation->getError('no_hp') ? 'is-invalid' : ''; ?>" placeholder="08969xxx" value="<?= old('no_hp') ?? $oldInput['no_hp'] ?? ''  ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('no_hp'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="jabatan">Jabatan <span class="text-danger">*)</span></label>
                        <select name="jabatan" id="jabatan" class="form-control" required>
                           <?php
                           $ar_jabatan = ['tendik'=>'Tendik','guru'=>'Guru/Pendidik'];
                           foreach($ar_jabatan as $key=>$val):
                              echo '<option value="'.$val.'">'.$val.'</option>';
                           endforeach;
                           ?>
                        </select>
                     </div>

                     <div class="form-group mt-4">
                        <label for="statusguru">Status <span class="text-danger">*)</span></label>
                        <select name="status_guru" id="status_guru" class="form-control" required>
                           <?php
                           $ar_status = ['PTT','PTY','PTM','HARIAN','GTT','GTY','PNS'];
                           foreach($ar_status as $key=>$val):
                              echo '<option value="'.$val.'">'.$val.'</option>';
                           endforeach;
                           ?>
                        </select>
                     </div>

                     <div class="form-group mt-5">
                        <label for="foto">Foto <span class="text-danger"><?= $validation->getError('filefoto'); ?></span></label>
                     </div>
                     <div class="input-group">
                           <input type="file" id="fileefoto" class="form-control" name="filefoto" placeholder="1234" required accept=".jpg, .jpeg, .png" />
                        </div>
                        <small class="text-danger">File yang diijinkan JPG, JPEG, PNG</small>


                     <button type="submit" class="btn btn-success btn-block">Simpan</button>
                  </form>

                  <hr>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>