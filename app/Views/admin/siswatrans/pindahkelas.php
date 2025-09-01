<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <?php if (session()->getFlashdata('msg')) : ?>
          <div class="pb-2 px-3">
            <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success'  ?> ">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <?= session()->getFlashdata('msg') ?>
            </div>
          </div>
        <?php endif; ?>
           
        <div class="card">
          <div class="card-header card-header-primary">
            <div class="nav-tabs-navigation">
                <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title"><b>Pindah Kelas</b></h4>
                    <p class="card-category">Fitur ini digunakan untuk memindahkan siswa yang sudah memiliki kelas pada tahun ajaran yang sama.</p>
                </div>
                <div class="col-md-12">
                    <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title"><b>PILIH KELAS :</b></span>
                       
                    </div>
                </div>
                
                </div>
            </div>
          </div>
          <div class="card-body mx-5 my-3">
            
            <form action="<?= site_url('admin/mutasi-proses'); ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Pilih Kelas</label>
                      <select name="kelas" id="kelas" class="form-control" onchange="getDataSiswa()">
                        <option value="">--Pilih Kelas--</option>
                      
                          <?php
                              $tempKelas = [];
                              foreach ($kelas as $value) : ?>
                             
                                  
                                  <option value="<?=$value['kode'];?>"><?=$value['kelas'];?><?=$value['jurusan'];?></option>
                              
                              <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4"><br>
                    <div class="form-group">
                      <label>Th. Ajar Aktif</label>
                      <input type="text" name="thajar_aktif" id="thajar_aktif" value="<?=$thajar_aktif;?>" readonly class="form-control">
                    </div>
                  </div>

                </div>

                <div id="dataSiswa"></div>
            
            </form>

            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
   var kelas = "all";

  

   function getDataSiswa() {
    var kelas = $('#kelas').val();
    var thajar_aktif = '<?=$thajar_aktif?>';

    if(kelas != ""){
      jQuery.ajax({
         url: "<?= base_url('/admin/mutasi-data'); ?>",
         type: 'post',
         data: {
            'kelas': kelas,
            'thajar_aktif' : thajar_aktif 
         },
         success: function(response, status, xhr) {
            // console.log(status);
            $('#dataSiswa').html(response);

            $('html, body').animate({
               scrollTop: $("#dataSiswa").offset().top
            }, 500);
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#dataSiswa').html(thrown);
         }
      });
    }else{
      $('#dataSiswa').html('<p>Data tidak ditemukan. Silahkan coba kembali.</p>');
    }
      
   }
   
    
</script>
<?= $this->endSection() ?>