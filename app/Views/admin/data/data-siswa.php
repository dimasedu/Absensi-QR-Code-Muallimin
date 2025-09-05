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
            <form action="<?=base_url('admin/kartupelajar')?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="card">
               <div class="card-header card-header-tabs card-header-primary">
                  <div class="d-md-flex justify-content-between align-items-center">
                     <div>
                        <h4 class="card-title"><b>Daftar Siswa</b></h4>
                        <p class="card-category">Angkatan <?= \Config\Services::session()->get('thajar'); ?></p>
                     </div>
                     <div class="mt-3 mt-md-0">
                        <a class="btn btn-primary ml-1 " href="<?= base_url('admin/siswa/create'); ?>">
                           <i class="material-icons mr-2">add</i> Tambah data siswa
                        </a>
                        <button class="btn btn-info ml-1 " type="submit"><i class="material-icons mr-2">print</i> Cetak Kartu</button>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success ml-1 " data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="material-icons mr-1">file_upload</i> Import Data Siswa
                        </button>
                        

                        <a class="btn btn-danger ml-1 " href="<?= base_url('admin/siswa/cetakantrian'); ?>">
                           <i class="material-icons mr-2">list</i> Antrian Cetak
                        </a>
                     </div>
                        <!-- <div class="col-md-6">
                           <div class="nav-tabs-wrapper">
                              <span class="nav-tabs-title">Jurusan:</span>
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                 <li class="nav-item">
                                    <a class="nav-link active" onclick="jurusan = 'all'; trig()" href="#" data-toggle="tab">
                                       <i class="material-icons">check</i> Semua
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 <?php foreach ($jurusan as $value) : ?>
                                    <li class="nav-item">
                                       <a class="nav-link" onclick="jurusan = '<?= $value['jurusan']; ?>'; trig();" href="#" data-toggle="tab">
                                          <i class="material-icons">work</i> <?= $value['jurusan']; ?>
                                          <div class="ripple-container"></div>
                                       </a>
                                    </li>
                                 <?php endforeach; ?>
                              </ul>
                           </div>
                        </div> -->
                  </div>
               </div>
               <div class="card-body">
                  <div class="nav-tabs-navigation">
                     <div class="nav-tabs-wrapper">
                        <!-- <span class="nav-tabs-title">Kelas:</span> -->
                        <h4><b>Pilih Kelas</b></h4>
                        <ul class="nav nav-tabs px-0 mb-4" data-tabs="tabs">
                           <li class="nav-item mr-2 mb-2">
                              <a class="nav-link active btn btn-primary" onclick="kelas = 'all'; trig()" href="#" data-toggle="tab">
                                 <i class="material-icons">check</i> Semua
                                 <div class="ripple-container"></div>
                              </a>
                           </li>
                           <?php
                           $tempKelas = [];
                           foreach ($kelas as $value) : ?>
                              <?php if (!in_array($value['kode'], $tempKelas)) : ?>
                                 <li class="nav-item mr-2 mb-2">
                                    <a class="nav-link btn btn-primary" onclick="kelas = '<?= $value['kode']; ?>'; trig()" href="#" data-toggle="tab">
                                       <i class="material-icons">school</i> <?= $value['kelas'].' '.$value['jurusan']; ?>
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 <?php array_push($tempKelas, $value['kode']) ?>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </ul>
                     </div>
                  </div>
                  <div id="dataSiswa">
                     <p class="text-center mt-3">Daftar siswa muncul disini</p>
                  </div>
               </div>
               
            </div>

            </form>
         </div>
      </div>
   </div>
</div>

<form method="post" action="<?=url_to('admin/siswa/importsiswa')?>" enctype="multipart/form-data">
            <!-- Modal -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                  <h4 class="modal-title fs-5" id="exampleModalLabel">Import Data Siswa</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                        
                        <!-- <div class="alert alert-info">Setelah melakukan import jangan lupa untuk meletakkan file foto pada folder <b>public/uploads/fotosiswa/</b></div> -->
                        <div class="input-group">
                           <input type="file" id="fileexcel" class="form-control" name="fileexcel" placeholder="1234" required accept=".xls, .xlsx" />
                        </div>
                        <small class="text-danger">File yang diijinkan XLS, XLSX</small>
                  </div>
                  <div class="modal-footer">
                  <a href="<?=base_url('public/uploads/importsiswa.xlsx');?>" class="btn btn-secondary">Contoh File</a>
                  <button type="submit" class="btn btn-primary">Proses</button>
                  </div>
               </div>
            </div>
            </div>
            </form>
<script>
   var kelas = "all";
   var jurusan = "all";

   getDataSiswa(kelas, jurusan);

   function trig() {
      getDataSiswa(kelas, jurusan);
   }

   function getDataSiswa(_kelas, _jurusan) {
      jQuery.ajax({
         url: "<?= base_url('/admin/siswa'); ?>",
         type: 'post',
         data: {
            'kelas': _kelas,
            'jurusan': _jurusan
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