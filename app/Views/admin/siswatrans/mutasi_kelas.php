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
                    <h4 class="card-title"><b>Mutasi Kelas</b></h4>
                    <p class="card-category">Fitur ini digunakan untuk mutasi siswa Naik Kelas ataupun Kelulusan.</p>
                </div>
                <div class="col-md-12">
                    <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title"><b>PILIH KELAS :</b></span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                            <li class="nav-item">
                            <a class="nav-link active" onclick="kelas = 'all'; trig()" href="#" data-toggle="tab">
                                <i class="material-icons">check</i> Semua
                                <div class="ripple-container"></div>
                            </a>
                            </li>
                            <?php
                            $tempKelas = [];
                            foreach ($kelas as $value) : ?>
                            <?php if (!in_array($value['kelas'], $tempKelas)) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="kelas = '<?= $value['kelas']; ?>'; trig()" href="#" data-toggle="tab">
                                        <i class="material-icons">school</i> <?= $value['kelas'].' '.$value['jurusan']; ?>
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <?php array_push($tempKelas, $value['kelas']) ?>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                </div>
            </div>
          </div>
          <div class="card-body mx-5 my-3">
            
            <form action="<?= site_url('admin/mutasi-proses'); ?>" method="post" enctype="multipart/form-data">
            
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

   getDataSiswa(kelas);

   function trig() {
      getDataSiswa(kelas);
   }

   function getDataSiswa(_kelas) {
      jQuery.ajax({
         url: "<?= base_url('/admin/mutasi-data'); ?>",
         type: 'post',
         data: {
            'kelas': _kelas,
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
   }
   
    
</script>
<?= $this->endSection() ?>