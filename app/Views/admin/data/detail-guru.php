<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
               <div class="nav-tabs-navigation">
                     <div class="row">
                        <div class="col">
                           <h4 class="card-title"><b>Detail Guru</b></h4>
                           <p class="card-category">Detail data Guru dan Staf.</p>
                        </div>
                        <div class="col-auto">
                           <div class="nav-tabs-wrapper">
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                <li class="nav-item">
                                    <a class="nav-link" id="backBtn"  href="<?=site_url('admin/guru/');?>">
                                       <i class="material-icons">close</i> Kembali
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>

               </div>
               <div class="card-body mx-5 my-3">

                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-4">
                                <label for="nis">NBM</label><br>
                                <b><?=$data['nuptk'];?></b>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-4">
                                <label for="nama">Nama Lengkap</label><br>
                                <b><?=$data['nama_guru'] ?></b>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-4">
                                <label for="jk">Jenis Kelamin</label><br>
                                <b><?php echo $data['jenis_kelamin'];?></b>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-4">
                                <label for="jk">Tempat / Tanggal Lahir</label><br>
                                <b><?php echo $data['tempat_lahir'].', '.date('d/m/Y',strtotime($data['tanggal_lahir']));?></b>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-4">
                            <label for="jabatan">Jabatan</label><br>
                            <b><?= $data['jabatan'] == "guru" ? 'Guru/Pendidik' : 'Tendik'; ?></b>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-4">
                            <label for="status">Status</label><br>
                            <b><?= $data['status_guru'] ?></b>
                            </div>
                        </div>                        
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-4">
                            <label for="hp">No HP</label><br>
                            <b><?= $data['no_hp'] ?></b>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mt-5">
                                <label for="foto">Foto Guru</label><br>
                                <img src="<?= $data['foto'] != NULL ? base_url('/public/uploads/fotoguru/'.$data['foto']) : base_url('public/assets/img/new_logo.png'); ?>" class="img-fluid" width="100">
                            </div>
                        </div>
                    </div>

                    
                     
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>