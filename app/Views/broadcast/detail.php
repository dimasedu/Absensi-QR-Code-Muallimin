<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Detail Broadcast</b></h4>
          </div>
          <div class="card-body mx-5 my-3">
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
           

              <div class="form-group mt-4">
                <label for="judul"><b>Judul Broadcast</b></label><br>
                <?=$query->judul;?>
              </div>

              <div class="form-group mt-4">
                <label for="tanggal"><b>Tanggal Kirim</b></label><br>
                <?=date('Y-m-d H:i:s',strtotime($query->sent_at))?> WIB
                
              </div>

              <div class="form-group mt-4">
                <label for="kelas"><b>Group Kontak</b></label><br>
                <?=$query->id_kelas == "gr" ? 'Guru & Staf' : $query->kelas. ' ('.$query->jurusan.')';?>
                
              </div>

              <div class="form-group mt-4">
                <label for="pesan"><b>Pesan Broadcast</b></label><br>
                <div class="alert alert-light">
                    <?=$query->isi_pesan;?>
                </div>
                
              </div>

              <div class="form-group mt-2">
                <label for="gambar"><b>Gambar (Opsional)</b></label><br>
                <?php
                if($query->gambar != "" || !empty($query->gambar)):
                ?>
                  <img src="<?=base_url('public/uploads/broadcast/'.$query->gambar)?>" class="img-fluid" width="250">
                <?php
                endif;
                ?>  
                </div>

            <hr>
            <h4>Statistik Broadcast</h4>
            <table class="table table-striepd">
                    <tr>
                        <td><span class="text-info"><b>Total Kontak</b></span></td>
                        <td>&nbsp;<td>
                        <td><?=number_format($query->total_kontak);?><td>
                    </tr>

                    <tr>
                        <td><span class="text-success"><b>Total Sukses</b></span></td>
                        <td><?=number_format($sukses);?><td>
                        <td><?=$sukses > 0 ? number_format(($sukses / $query->total_kontak) * 100,2) : 0;?> %<td>
                    </tr>

                    <tr>
                        <td><span class="text-danger"><b>Total Gagal</b></span></td>
                        <td><?=number_format($gagal);?><td>
                        <td><?=$gagal > 0 ? number_format(($gagal / $query->total_kontak) * 100,2) : 0;?> %<td>
                    </tr>

                    <tr>
                        <td><span class="text-warning"><b>Total Pending</b></span></td>
                        <td><?=number_format($pending);?><td>
                        <td><?=$pending > 0 ? number_format(($pending / $query->total_kontak) * 100,2) : 0;?> %<td>
                    </tr>
                </table>

                <h4>Log Pesan</h4>
                <table class="table table-bordered table-striped">
                  <tr>
                    <th>No</th>
                    <th>Nomor</th>
                    <th>Nama Penerima</th>
                    <th>Status</th>
                  </tr>
                  <?php
                  $no =1;
                  foreach($qdetail as $row):
                    ?>
                    <tr>
                      <td><?=$no++;?></td>
                      <td><?=$row->tujuan;?></td>
                      <td><?=$row->nama;?></td>
                      <td><?php if($row->is_sent == "Y") echo '<span class="badge bg-success text-white">Sukses</span>';
                       if($row->is_sent == "F") echo '<span class="badge bg-danger text-white">Gagal</span><br><a href="'.site_url('admin/broadcast/resend/'.$row->id).'" class="btn btn-info btn-sm">RESEND</a>';
                       if($row->is_sent == "N") echo '<span class="badge bg-warning">Menunggu...</span>';?></td>
                    </tr>
                    <?php
                  endforeach;
                  ?>
                </table>
          </div>
          <div class="card-footer mx-5 my-3">
            <a href="<?=site_url('admin/broadcast')?>" class="btn btn-danger">Kembali</a>
          </div>
        </div>
      </div>

     </div>
    </div>
  </div>
</div>



    
<?= $this->endSection() ?>