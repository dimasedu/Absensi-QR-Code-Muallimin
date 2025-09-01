<?php
use App\Libraries\Siswalib;
$siswalib = new Siswalib();
?>
<div class="alert alert-info text-white">
    <div class="row">
       
        <div class="col-lg-6 col-sm-12">
            <div class="form-group mt-2">
                <label class="text-white"><b>NISN</b></label><br>
                <?=$query->nis;?>
            </div>
            <div class="form-group mt-2">
                <label class="text-white"><b>Nama Siswa</b></label><br>
                <?=$query->nama_siswa;?>
            </div>
            <div class="form-group mt-2">
                <label class="text-white"><b>Kelas</b></label><br>
                <?=$siswalib->get_kelas($query->id_siswa);?>
            </div>
            <div class="form-group mt-2">
                <label class="text-white"><b>No. HP</b></label><br>
                <?=$query->no_hp;?>
            </div>
        </div>
        
        <div class="col-lg-6 col-sm-12">
        <img src="<?php
                    if($query->foto != "-") : 
                       echo  base_url('/public/uploads/fotosiswa/'.$query->foto);
                     
                    else : 
                        echo base_url('public/assets/img/new_logo.png'); 
                    endif;
                ?>" class="img-fluid img-thumbnail">
        </div>
    </div>
</div>