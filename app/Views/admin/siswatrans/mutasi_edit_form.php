<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title"><b>Form Edit Siswa</b></h4>

               </div>
               <div class="card-body mx-5 my-3">

                  <form action="<?= base_url('admin/mutasi-edit-proses'); ?>" method="post" enctype="multipart/form-data" class="form-default">
                     <?= csrf_field() ?>
                     <input type="hidden" name="id" value="<?=$query->id?>">
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
                        <label for="nis">NIS</label>
                        <input type="text" id="nis" class="form-control" name="nis" value="<?=$query->nis;?>">
                        
                     </div>

                     <div class="form-group mt-4">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" class="form-control" name="nama" value="<?=$query->nama_siswa;?>">
                        
                     </div>
                     
                     <div class="row mt-4">
                        <div class="col-md-4 form-group mt-0">
                           <label for="kelas">Kelas <span class="text-danger">*)</span></label>
                           <select class="custom-select" id="kelas" name="kelas" required>
                              <option value="">--Pilih kelas--</option>
                              <option value="lulus" <?=$query->kode_kelas == "lulus" ? 'selected="selected"' :'';?>>LULUS/KELUAR</option>
                              <?php foreach ($kelas as $value) : ?>
                                 <option value="<?= $value['kode']; ?>" <?= old('kelas') ?? $query->kode_kelas == $value['kode'] ? 'selected' : ''; ?>>
                                    <?= $value['kelas'] . ' ' . $value['jurusan']; ?>
                                 </option>
                              <?php endforeach; ?>
                           </select>
                        </div>

                        <div class="col-md-4 form-group mt-0">
                            <label for="tipe">Tipe  <span class="text-danger">*)</span></label>
                            <select class="custom-select" id="tipe" name="tipe" required>
                                <option value="">--Pilih Tipe Mutasi--</option>
                                <option value="masuk" <?=$query->tipe == "masuk" ? 'selected="selected"' : ''; ?>>BERI KELAS</option>
                                <option value="naik" <?=$query->tipe == "naik" ? 'selected="selected"' : ''; ?>>NAIK KELAS</option>
                                <option value="lulus" <?=$query->tipe == "lulus" ? 'selected="selected"' : ''; ?>>LULUS / KELUAR</option>
                            </select>
                        </div>    

                        <div class="col-md-4 form-group mt-0">
                                <label for="thajar">Tahun Ajar <span class="text-danger">*)</span></label>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="number" class="form-control" name="thawal" value="<?=$thawal;?>" min="<?$thawal;?>">
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="number" class="form-control" name="thakhir" value="<?=$thakhir;?>" min="<?$thakhir;?>">
                                    </div>
                                </div>
                        </div>
                    </div>    
                    <div class="form-group mt-4">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keteranga" id="keterangan" class="form-control" placeholder="Masukkan Keterangan (Opsional)"><?=$query->keterangan;?></textarea>
                        
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