<?= $this->extend('templates/starting_page_layout'); ?>

<?= $this->section('content'); ?>

<div class="row m-2">
                
    <div class="col-lg-12 col-12 p-0">
        <div class="card" style="min-height:600px;">
            
            <div class="card-content">
                <div class="card-body">
                    <center>
                        <img src="https://www.mtsn1batam.sch.id/media_library/images/4eabf8862bdc0eb968762b344d1024ba.png" width="64"><br>
                        <h1 class="mt-1">ABSENSI MASUK</h1>
                        <p class="px-2">Selamat Datang, Silahkan arahkan kode antrian anda pada kamera.</p>
                        <div class="col-6">
                        <div class="input-group">
                            
                            <input type="text" class="form-control form-control-lg" placeholder="Masukkan NIS/NISN" autofocus id="kode">
                            
                            </div>
                        </div>

                        <button class="btn btn-success mt-2" type="button" id="cari">PROSES ABSEN</button>
                    </center>    
                    <div class="card-text">
                       <div id="alertdata" class="alert alert-success text-center mt-5">
                            Silahkan arahkan ID CARD anda pada kolom yang tersedia terlebih dahulu!
                       </div>
                       <div id="loading" class="text-center" style="display:none;">
                            <img src="{{url('images/loading.gif')}}">
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('public/assets/js/core/jquery-3.5.1.min.js') ?>"></script>
<script type="text/javascript">
   
</script>

<?= $this->endSection(); ?>