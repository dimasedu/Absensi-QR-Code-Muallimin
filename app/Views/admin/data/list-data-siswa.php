<?php
use App\Libraries\Siswalib;
$siswalib = new Siswalib;
?>
<div class="table-responsive">
   <div id="antriansukses"></div>
   <a href="<?=base_url('admin/siswa/excel/'.$kelasxls.'/'.$jurusanxls);?>" class="btn btn-success btn-sm">Export Excel</a><br>
   <!-- <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Pilih Kelas</label>
                <select class="form-control kelas" name="">
                    <option>-- Pilih --</option>
                    <option value="SEMUA">SEMUA</option>
                    <?php
                    $tempKelas = [];
                    foreach ($kelas as $value) : ?>
                    <option value="<?=$value['kelas']?>"><?=$value['kelas'];?></option>
                       
                      
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Pilih Jurusan</label>
                <select class="form-control jurusan" name="">
                    <option>-- Pilih --</option>
                    <option value="SEMUA">SEMUA</option>
                    <?php
                    $tempKelas = [];
                     foreach ($jurusan as $value) : ?>
                    <option value="<?=$value['jurusan']?>"><?=$value['jurusan'];?></option>
                       
                      
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div> -->
   <?php if (!$empty) : ?>
      <table class="table table-hover" id="tabledata">
         <thead class="text-primary">
            <th><b><input type="checkbox" id="checkall" /></b></th>
            <th><b>No</b></th>
            <!-- <th><b>Foto</b></th> -->
            <th><b>NIS</b></th>
            <th><b>Nama Siswa</b></th>
            <th><b>Jenis Kelamin</b></th>
            <th><b>Kelas</b></th>
            <th><b>No HP</b></th>
            <th width="1%"><b>Aksi</b></th>
         </thead>
         <tbody>
            <?php $i = 1;
            foreach ($data as $value) : ?>
               <tr>
                  <td><input type="checkbox" name="id[]" value="<?=$value['id_siswa'];?>"></td>
                  <td><?= $i; ?></td>
                  <!-- <td><img src="<?= $value['foto'] != NULL ? base_url('/public/uploads/fotosiswa/'.$value['foto']) : base_url('public/assets/img/new_logo.png'); ?>" class="img-fluid" width="70"></td> -->
                  <td><a href="<?=site_url('admin/siswa/detail/'.$value['id_siswa']);?>"><?= $value['nis']; ?></td>
                  <td><b><?= $value['nama_siswa']; ?></b></td>
                  <td><?= $value['jenis_kelamin']; ?></td>
                  <td><?=$siswalib->get_kelas($value['id_siswa']);?></td>
                  <td><?= $value['no_hp']; ?></td>
                  <td>
                     <div class="d-flex justify-content-center">
                        <a title="Edit" href="<?= base_url('admin/siswa/edit/' . $value['id_siswa']); ?>" class="btn btn-edit p-2" id="<?= $value['nis']; ?>">
                           <i class="material-icons">edit</i>
                        </a>

                        <a title="Edit" href="<?= base_url('admin/siswa/delete/' . $value['id_siswa']); ?>" class="btn btn-danger p-2" id="<?= $value['nis']; ?>" onclick="return confirm('Konfirmasi untuk menghapus data');" >
                           <i class="material-icons">delete_forever</i>
                        </a>
                        
                        <!-- <a title="Download QR Code" href="<?= base_url('admin/qr/siswa/' . $value['id_siswa'] . '/download'); ?>" class="btn btn-success p-2">
                           <i class="material-icons">qr_code</i>
                        </a> -->
                        <button class="btn btn-success p-2" onclick="pilih(<?=$value['id_siswa']?>)" type="button"> <i class="material-icons">check</i></button>
                     </div>
                  </td>
               </tr>
            <?php $i++;
            endforeach; ?>
         </tbody>
      </table>
   <?php else : ?>
      <div class="row">
         <div class="col">
            <h4 class="text-center text-danger">Data tidak ditemukan</h4>
         </div>
      </div>
   <?php endif; ?>
</div>


<script>
   
   function pilih(idsiswa){
      $(document).ready(function(){
         $.ajax({
            url :'<?=site_url('admin/siswa/cetakantrianadd/');?>'+idsiswa,
            dataType : 'JSON',
            success : function(result){
               if(result.status == "OK"){
                  $('#antriansukses').html('<div class="alert alert-success">Siswa NIS : <b>'+result.nisn+'</b> - <b>'+result.nama+'</b> berhasil dimasukan dalam antrian cetak.</div>');
               } else {
                  $('#antriansukses').html('<div class="alert alert-danger">'+result.msg+'</div>');
               }
               
            }

         });
      });
   }
   $(document).ready(function() {
      

      // function filterData () {
      //       var kelas = $('.status').val();
      //       var jurusan = $('.jurusan').val();
      //       if(kelas != "SEMUA"){
      //             $('#tabledata').DataTable().search(kelas).draw();
      //       } else if(jurusan != "SEMUA"){
      //          $('#tabledata').DataTable().search(jurusan).draw();
      //       } else {
      //           $('#tabledata').DataTable({
      //             pageLength: 50,
      //           });
      //       }
		    
		// }

		// $('.kelas').on('change', function () {
	   //      filterData();
	   //  });

      //  $('.jurusan').on('change', function () {
	   //      filterData();
	   //  });

      $('#tabledata').DataTable({
         pageLength: 50,
      });
      $('#checkall').click(function() {
         var checkboxes = $(this).closest('form').find(':checkbox');
         checkboxes.prop('checked', $(this).is(':checked'));
      });
   });
</script>