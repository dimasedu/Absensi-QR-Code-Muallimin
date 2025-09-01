<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-success">
                  <h4 class="card-title"><b>Form Edit Guru</b></h4>

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

                  <form action="<?= base_url('admin/guru/edit'); ?>" method="post" enctype="multipart/form-data">
                     <?= csrf_field() ?>
                     <?php $validation = \Config\Services::validation(); ?>

                     <input type="hidden" name="id" value="<?= $data['id_guru'] ?>">
                     <input type="hidden" name="fotolama" value="<?= $data['foto'] ?>">

                     <div class="form-group mt-4">
                        <label for="nuptk">NBM</label>
                        <input type="text" id="nuptk" class="form-control <?= $validation->getError('nuptk') ? 'is-invalid' : ''; ?>" name="nuptk" placeholder="1234" value="<?= old('nuptk') ?? $oldInput['nuptk'] ?? $data['nuptk'] ?>">
                        <div class="invalid-feedback">
                           <?= $validation->getError('nuptk'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" class="form-control <?= $validation->getError('nama') ? 'is-invalid' : ''; ?>" name="nama" placeholder="Your Name" value="<?= old('nama') ?? $oldInput['nama'] ?? $data['nama_guru'] ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('nama'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*)</span></label>
                        <input type="text" id="tempat_lahir" class="form-control <?= $validation->getError('tempat_lahir') ? 'is-invalid' : ''; ?>" name="tempat_lahir" placeholder="Tempat Lahir" value="<?= old('tempat_lahir') ?? $data['tempat_lahir'] ?? '' ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('tempat_lahir'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*)</span></label>
                        <input type="date" id="tanggal_lahir" class="form-control <?= $validation->getError('tanggal_lahir') ? 'is-invalid' : ''; ?>" name="tanggal_lahir" placeholder="Tanggal Lahir" value="<?= old('tanggal_lahir') ?? $data['tanggal_lahir'] ?? '' ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('tanggal_lahir'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-2">
                        <label for="jk">Jenis Kelamin</label>
                        <?php
                        $jenisKelamin = (old('jk') ?? $oldInput['jk'] ?? $data['jenis_kelamin']);
                        $l = $jenisKelamin == 'Laki-laki' || $jenisKelamin == '1' ? 'checked' : '';
                        $p = $jenisKelamin == 'Perempuan' || $jenisKelamin == '2' ? 'checked' : '';
                        ?>
                        <div class="form-check form-control pt-0 mb-1 <?= $validation->getError('jk') ? 'is-invalid' : ''; ?>">
                           <div class="row">
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="jk" id="laki" value="1" <?= $l; ?>>
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
                                       <input class="form-check" type="radio" name="jk" id="perempuan" value="2" <?= $p; ?>>
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
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" value="<?= old('alamat') ?? $oldInput['alamat']  ?? $data['alamat'] ?>">
                     </div>

                     <div class="form-group mt-4">
                        <label for="hp">No HP</label>
                        <input type="number" id="hp" name="no_hp" class="form-control <?= $validation->getError('no_hp') ? 'is-invalid' : ''; ?>" placeholder="08969xxx" value="<?= old('no_hp') ?? $oldInput['no_hp']  ?? $data['no_hp'] ?>" required>
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
                              $seljab = $data['jabatan'] == $val ? 'selected="selected"' : '';
                              echo '<option value="'.$val.'" '.$seljab.'>'.$val.'</option>';
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
                              $selstat = $data['status_guru'] == $val ? 'selected="selected"' : '';
                              echo '<option value="'.$val.'" '.$selstat.'>'.$val.'</option>';
                           endforeach;
                           ?>
                        </select>
                     </div>

                     <div class="form-group mt-5">
                        <label for="foto">Foto <span class="text-danger"><?= $validation->getError('filefoto'); ?></span></label>
                     </div>
                     <div class="input-group">
                           <input type="file" id="fileefoto" class="form-control <?= $validation->getError('filefoto') ? 'is-invalid' : ''; ?>" name="filefoto" placeholder="1234" accept=".jpg, .jpeg, .png" />
                        </div>
                        <small class="text-danger">File yang diijinkan JPG, JPEG, PNG</small><br>
                        <img src="<?= $data['foto'] != NULL ? base_url('/public/uploads/fotoguru/'.$data['foto']) : base_url('public/assets/img/new_logo.png'); ?>" class="img-fluid" width="100"><br>
                        

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