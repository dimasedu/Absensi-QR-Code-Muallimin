<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content');
use App\Libraries\Siswalib;
$siswalib = new Siswalib();
?>
<style>
    .toolbar {
        width: 100%;
        height: 40px;
        background-color: #f2f2f2;
        border-radius: 3px 3px 0 0
    }

    .toolbar .item {
        float: left;
        height: 40px;
        padding: 0 10px;
        line-height: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all .5s;
        width: 30px;
        text-align: center
    }

    .toolbar .item:first-child {
        border-radius: 3px 0 0 0
    }

    .toolbar .item:hover {
        background-color: #39065a;
        color: #fff
    }

    .toolbar+textarea {
        border-top: none;
        border-radius: 0 0 3px 3px
    }
</style>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Form Tambah Izin / Sakit</b></h4>
          </div>
          <div class="card-body mx-5 my-3">

            <form action="<?= site_url('admin/absensi-ijin/save'); ?>" method="post" enctype="multipart/form-data">
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

              <small>Kolom bertanda  <span class="text-danger">*)</span> wajib diisi.</small>

              <div class="form-group mt-4">
                <label for="tanggal">Tanggal <span class="text-danger">*)</span></label>
                <input type="date" name="tanggal" class="form-control" required id="tanggal" value="<?=old('tanggal')?>">
                
              </div>

              <div class="form-group mt-4">
                <label for="siswa">Siswa <span class="text-danger">*)</span></label>
                <select name="siswa" id="siswa" class="form-control select2" required placeholder="Masukkan NISN/Nama Siswa">
                    <option value="">Masukkan NISN / Nama Siswa <span class="text-danger">*)</span></option>
                    <?php
                    foreach($siswa as $row){
                        ?>
                        <option value="<?=$row->id_siswa?>|<?=$row->no_hp;?>|<?=$siswalib->get_kelas_id($row->id_siswa);?>"><?=$row->nama_siswa;?> (<?=$row->nis;?>) - <?=$row->kelas.' '.$row->jurusan;?></option>
                        <?php
                    }
                    ?>
                </select>
                
              </div>
              <div class="alert alert-info" id="loading"  style="display:none;"><b>Sedang Mengambil Data. Mohon tunggu...</b></div>
              <div id="showsiswa" style="display:none;"></div>    
              
              <div class="form-group mt-4">
                <label for="absensi">Tipe Permohonan  <span class="text-danger">*)</span></label><br>
                <input type="radio" name="tipe" id="ijin" required value="I"> <span class="badge bg-success text-white">IZIN</span> &nbsp;
                <input type="radio" name="tipe" id="sakit" required value="S"> <span class="badge bg-danger text-white">SAKIT</span> &nbsp;
              </div>

              <div class="form-group mt-4">
                <label for="pesan">Keterangan <span class="text-danger">*)</span></label>
                <textarea name="pesan" class="form-control" rows="5" required id="input-message"></textarea>
                
              </div>

              <div class="form-group mt-2">
                <label for="gambar">File Dokumen (Sertakan Jika Ada)</label>
              </div>
              <div class="input-group">
                    <input type="file" id="filefoto" class="form-control" name="filefoto" placeholder="1234" accept=".jpg, .jpeg, .png, .webp" />
                    <div class="invalid-feedback">
                      <?= $validation->getError('filefoto'); ?>
                    </div>
              </div>
              <small class="text-danger">File yang diizinkan JPG, JPEG, PNG, WEBP, PDF. Maksimal File : 2 MB</small><br>
              <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </form>
          </div>

          
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){

      $('#siswa').on('change',function(e){
        e.preventDefault();

        var siswa = $('#siswa').val();

        if(siswa != ""){
          $.ajax({
            url : '<?=site_url("admin/absensi-ijin/tampilsiswa/")?>'+siswa,
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

    });
</script>
    
<?= $this->endSection() ?>