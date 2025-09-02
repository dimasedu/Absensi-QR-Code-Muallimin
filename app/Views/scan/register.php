<?= $this->extend('templates/starting_page_layout'); ?>

<?= $this->section('navaction') ?>
<a href="<?= base_url('/'); ?>" class="btn btn-primary pull-right pl-3">
   <i class="material-icons mr-2">qr_code</i>
   Scan QR
</a>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<style>
body {background:#1572e8!important;}
.navbar .navbar-brand {color: #fff;}
</style>
<div class="main-panel" id="login-page">
   <div class="content">
      <div class="container-fluid">
               <div class="card">
                  <div class="card-header px-3">
                     <h4 class="card-title">Pendaftaran SCC</h4>
                     <p class="card-category">Silahkan masukkan detail anda untuk melakukan pendaftaran</p>
                  </div>

                  <div class="card-body mx-5 my-3">
                  <form action="" method="post" enctype="multipart/form-data" id="frmRegister">
                     <?= csrf_field() ?>
                     <?php $validation = \Config\Services::validation(); ?>

                     <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="false">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Informasi Sistem</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="pb-2">
                          <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success'  ?> ">
                            
                            <?= session()->getFlashdata('msg') ?>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                     <div class="form-group mt-4">
                        <label for="nis">NISN</label>
                        <input type="text" id="nis" class="form-control <?= $validation->getError('nis') ? 'is-invalid' : ''; ?>" name="nis" placeholder="10 Digit Nomor Induk Siswa Nasional" value="<?= old('nis') ?? $oldInput['nis']  ?? '' ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('nis'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" class="form-control <?= $validation->getError('nama') ? 'is-invalid' : ''; ?>" name="nama" placeholder="Nama Siswa" value="<?= old('nama') ?? $oldInput['nama']  ?? '' ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('nama'); ?>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <label for="kelas">Kelas</label>
                           <select class="custom-select <?= $validation->getError('id_kelas') ? 'is-invalid' : ''; ?>" id="kelas" name="id_kelas">
                              <option value="">--Pilih kelas--</option>
                              <?php foreach ($kelas as $value) : ?>
                                 <option value="<?= $value['kode']; ?>" <?= old('id_kelas') ?? $oldInput['id_kelas'] ?? '' == $value['kode'] ? 'selected' : ''; ?>>
                                    <?= $value['kelas'] . ' ' . $value['jurusan']; ?>
                                 </option>
                              <?php endforeach; ?>
                           </select>
                           <div class="invalid-feedback">
                              <?= $validation->getError('id_kelas'); ?>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <label for="jk">Jenis Kelamin</label>
                           <?php
                           if (old('jk')) {
                              $l = (old('jk') ?? $oldInput['jk']) == '1' ? 'checked' : '';
                              $p = (old('jk') ?? $oldInput['jk']) == '2' ? 'checked' : '';
                           }
                           ?>
                           <div class="form-check form-control pt-0 mb-1 <?= $validation->getError('jk') ? 'is-invalid' : ''; ?>" id="jk">
                              <div class="row">
                                 <div class="col-auto">
                                    <div class="row">
                                       <div class="col-auto pr-1">
                                          <input class="form-check" type="radio" name="jk" id="laki" value="1" <?= $l ?? ''; ?> required>
                                       </div>
                                       <div class="col">
                                          <label class="form-check-label pl-0 pt-1" for="laki">
                                             <h6 class="text-dark">Laki-laki</h6>
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
                     </div>

                     <div class="form-group mt-5">
                        <label for="hp">No Whatsapp Ibumu</label>
                        <input type="number" id="hp" name="no_hp" class="form-control <?= $validation->getError('no_hp') ? 'is-invalid' : ''; ?>" value="<?= old('no_hp') ?? $oldInput['no_hp'] ?? '' ?>" required placeholder="08xxxxxx">
                        <small class="text-danger"><b>Ketik dengan format 08xx jangan +62 atau 62 Contoh : 0812345678 </b></small>
                        <div class="invalid-feedback">
                           <?= $validation->getError('no_hp'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-3">
                        <label for="foto">Foto Siswa Latar Merah</label>
                     </div>
                     <div class="input-group">
                           <input type="file" id="filefoto" class="form-control" name="filefoto" placeholder="1234" accept=".jpg, .jpeg, .png" />
                        </div>
                        <small class="text-danger">File yang diizinkan JPG, JPEG, PNG</small>

                     <button type="button" class="btn btn-primary btn-block" id="daftar">Daftarkan Saya</button>
                  </form>
                  </div>
               </div>
            </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="notifModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Informasi Sistem</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger"><b>Oops! Terjadi Kesalahan.</b> <span id="text-error"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
   
   $(function(){
      <?php if (session()->getFlashdata('msg')) : ?>
          $('#infoModal').modal('show');
        <?php endif;?>
      $('#daftar').click(function(){
        var frmRegister = $('#frmRegister').serialize();
        var nis = $('#nis').val();
        var nama = $('#nama').val();
        var kelas = $('#kelas').val();
        var hp = $('#hp').val();
        var filefoto = $('#filefoto').val();

        if(nis == ""){
         $('#notifModal').modal('show');
         $('#text-error').html('NIS tidak boleh kosong.');
        } else if(nama == ""){
         $('#notifModal').modal('show');
         $('#text-error').html('Nama Lengkap tidak boleh kosong.');
        } else if(kelas == "") {
         $('#notifModal').modal('show');
         $('#text-error').html('Pilih Kelas dahulu');
        } else if(hp == ""){
         $('#notifModal').modal('show');
         $('#text-error').html('No. HP tidak boleh kosong.');
        // } else if(filefoto == ""){
        //  $('#notifModal').modal('show');
        //  $('#text-error').html('Lengkapi Foto dahulu');
        // } 
        
        }else {
            // $.ajax({
            //    url : '<?= url_to('register') ?>',
            //    type : 'POST',
            //    data : frmRegister,
            //    async: false,
            //    cache: false,
            //    contentType: false,
            //    enctype: 'multipart/form-data',
            //    processData: false,
            //    berforeSend : function(html){
            //       $('#daftar').prop('disabled','disabled');
            //    },
            //    success : function(result){
            //       if(result.status == "FAILED"){
            //          $('#daftar').prop('disabled','');
            //          alert('Maaf NIS Kamu Sudah terdaftar. Silahkan Coba Lagi.');
            //       } else {
            //          alert('Pendaftaran Berhasil. Terima kasih telah mendaftar.');
            //          // window.location.href="<?=url_to('register')?>";
            //       }
            //    }

            // });

            $('#daftar').prop('disabled','disabled');
            $('#frmRegister').submit();
        }

         
      });
   });
</script>
<?= $this->endSection(); ?>