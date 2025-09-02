<?= $this->extend('templates/starting_page_layout'); ?>

<?= $this->section('navaction') ?>
<a href="<?= base_url('/'); ?>" class="btn btn-primary pull-right pl-3">
   <i class="material-icons mr-2">qr_code</i>
   Scan QR
</a>
<?= $this->endSection() ?>

<?= $this->section('content'); 
use App\Libraries\Siswalib;
$siswalib = new Siswalib();
?>

<style>
body {background:#1572e8!important;}
.navbar .navbar-brand {color: #fff;}
</style>
<div class="main-panel" id="login-page">
   <div class="content">
      <div class="container-fluid">
               <div class="card">
                  <div class="card-header px-3">
                     <h4 class="card-title">Form Permohonan Izin/Sakit</h4>
                     <p class="card-category">Silahkan masukkan detail permohonan pada form yang tersedia.</p>
                  </div>

                  <div class="card-body mx-5 my-3">
                  <form action="<?= site_url('ijinsave'); ?>" method="post" enctype="multipart/form-data" id="frmijin" name="frmijin">
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

              <small>Kolom bertanda  <span class="text-danger">*)</span> wajib diisi.</small>

              <div class="form-group mt-4">
                <label for="tanggal">Tanggal <span class="text-danger">*)</span></label>
                <input type="date" name="tanggal" class="form-control" required id="tanggal" value="<?=old('tanggal')?>">
                
              </div>

              <div class="form-group mt-4">
                <label for="siswa">Cari Siswa <span class="text-danger">*)</span></label>
                
                <input type="text" class="form-control" placeholder="Masukkan NISN" aria-label="NISN" aria-describedby="basic-addon2" id="siswa" name="siswa">
                  
                    
                
                
                <!-- <select name="siswa" id="siswa" class="form-control select2" required placeholder="Masukkan NISN/Nama Siswa">
                    <option value="">Masukkan NISN / Nama Siswa <span class="text-danger">*)</span></option>
                    <?php
                    foreach($siswa as $row){
                        ?>
                        <option value="<?=$row->id_siswa?>|<?=$row->no_hp;?>|<?=$siswalib->get_kelas_id($row->id_siswa);?>"><?=$row->nama_siswa;?> (<?=$row->nis;?>) - <?=$row->kelas.' '.$row->jurusan;?></option>
                        <?php
                    }
                    ?>
                </select> -->
                
              </div>
              <div class="form-group mt-2">
                <button class="btn btn-outline-success" type="button" id="tampilsiswa">Tampilkan</button>
              </div>
              <div class="alert alert-info" id="loading"  style="display:none;"><b>Sedang Mengambil Data. Mohon tunggu...</b></div>
              <div id="showsiswa" style="display:none;"></div>    
              
              <div class="form-group mt-4">
                <label for="absensi">Pilih Tipe Permohonan  <span class="text-danger">*)</span></label><br>
                <input type="radio" name="tipe" id="ijin" required value="I"> <span class="badge bg-success text-white" checked>IZIN</span> &nbsp;
                <input type="radio" name="tipe" id="sakit" required value="S"> <span class="badge bg-danger text-white">SAKIT</span> &nbsp;
              </div>

              <div class="form-group mt-4">
                <label for="pesan">Keterangan <span class="text-danger">*)</span></label>
                <textarea name="pesan" class="form-control" rows="5" required id="input-message"></textarea>
                
              </div>

              <div class="form-group mt-2">
                <label for="gambar">File Dokumen / Surat Dokter (Sertakan Jika Ada)</label>
              </div>
              <div class="input-group">
                    <input type="file" id="filefoto" class="form-control" name="filefoto" placeholder="1234" accept=".jpg, .jpeg, .png, .webp" />
                    <div class="invalid-feedback">
                      <?= $validation->getError('filefoto'); ?>
                    </div>
              </div>
              <small class="text-danger">File yang diizinkan JPG, JPEG, PNG, WEBP, PDF. Maksimal File : 2 MB</small><br>

                     <button type="button" class="btn btn-primary btn-block" id="kirim">Kirim Permohonan</button>
                  </form>
                  </div>
               </div>
            </div>
   </div>
</div>

<script>
    $(document).ready(function(){
        <?php if (session()->getFlashdata('msg')) : ?>
          $('#infoModal').modal('show');
        <?php endif;?>

      $('#tampilsiswa').on('click',function(e){
        e.preventDefault();
        // var tombol = e.which();
          var siswa = $('#siswa').val();

            if(siswa != ""){
              $.ajax({
                url : '<?=site_url("ijintampilsiswa")?>',
                type : 'POST',
                data : 'siswa='+siswa,
                beforeSend : function(html){
                  $('#loading').show();
                },
                success : function(result){
                  $('#loading').hide(300);
                  $('#showsiswa').show(300);
                  $('#showsiswa').html(result);
                }
              })
            } else {
              $('#loading').hide();
              $('#showsiswa').hide(500);
            }
          });

           $('#kirim').on('click', function(){

            var tanggal = $('#tanggal').val();
            var siswa = $('#siswa').val();
            var pesan = $('#input-message').val();

            if(tanggal == ""){
              alert('Pilih Tanggal terlebih dahulu');
            } else if(siswa == ""){
              alert('Silahkan pilih siswa dahulu');
            } else if(pesan == ""){
              alert('Silahkan masukkan Keterangan Izin dahulu');
            }else {
              var konfirmasi = confirm('Silahkan periksa kembali sebelum mengirimkan data. Apakah anda yakin?');

              if(konfirmasi){
                $('#frmijin').submit();
              }
              
            }


           });
    });

   
</script>

<?= $this->endSection(); ?>