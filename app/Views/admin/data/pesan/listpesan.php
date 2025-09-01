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
          
                       

            <form method="post" action="<?=url_to('admin/siswa/hapuspesanmulti')?>" enctype="multipart/form-data">
            
            <div class="card">
               <div class="card-header card-header-tabs card-header-primary">
                  <div class="nav-tabs-navigation">
                     <div class="row">
                        <div class="col-md-12">
                           <h4 class="card-title"><b>Notifikasi Whatsapp</b></h4>
                           <p class="card-category">Daftar pesan notifikasi whatsapp yang belum terkirim</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-2">
                  <button class="btn btn-danger ml-3 mt-3 " type="submit" onlick="return confirm('Apakah yakin akan menghapus data ini?');"><i class="material-icons mr-2">delete</i> Hapus Pesan</button>
                  </div>
               </div>

               
               
                <div class="card-body table-responsive">
                <?php
               if(count($query) > 0):
               ?>
               
                    <table class="table table-hover">
                        <thead class="text-primary">
                            <th><b><input type="checkbox" id="checkall" /></b></th>
                            <th><b>No</b></th>
                            <th><b>No. WA</b></th>
                            <th><b>Pesan</b></th>
                            <th><b>Tipe</b></th>
                            <th><b>Status</b></th>
                            <th width="1%"><b>Aksi</b></th>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($query as $value) : ?>
                            <tr>
                                <td><input type="checkbox" name="id[]" value="<?=$value['id'];?>"></td>
                                <td><?= $i; ?></td>
                                <td><?= $value['tujuan']; ?></td>
                                <td><?= $value['isi_pesan']; ?></td>
                                <td><?php 
                                if($value['tipe'] == "registrasi") echo '<span class="badge badge-pill bg-warning">PENDAFTARAN</span>';
                                if($value['tipe'] =="masuk") echo '<span class="badge badge-pill bg-success text-white">MASUK</span>';
                                if($value['tipe'] =="pulang") echo '<span class="badge badge-pill bg-primary text-white">PULANG</span>'; 
                                ?></td>
                                <td><?php 
                                if($value['status'] =="F") echo '<span class="badge badge-danger">GAGAL</span>';
                                if($value['status'] =="N") echo '<span class="badge badge-danger">BELUM TERKIRIM</span>';
                                ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a title="Edit" href="<?= base_url('admin/siswa/kirimpesan/' . $value['id']); ?>" class="btn btn-primary" id="<?= $value['id']; ?>">
                                       RESEND
                                        </a>
                                        <!-- &nbsp;
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?=$value['id'];?>">
                                          HAPUS
                                          </button> -->
                                                                                 
                                          <div class="modal fade" id="hapusModal<?=$value['id'];?>" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="false">
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
                                                   <b>Apakah yakin akan menghapus data ini?</b>
                                                </div>
                                             </div>
                                             <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

                                                <!-- <a title="Edit" href="<?= base_url('admin/siswa/hapuspesan/' . $value['id']); ?>" class="btn btn-primary" id="<?= $value['id']; ?>">
                                                Hapus
                                                </a> -->
                                             </div>
                                          </div>
                                          </div>
                                       </div>
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

               </form>


                <script>
                $(document).ready(function() {
                    $('#checkall').click(function() {
                        var checkboxes = $(this).closest('form').find(':checkbox');
                        checkboxes.prop('checked', $(this).is(':checked'));
                    });
                });
                </script>
            </div>

            </form>
         </div>
      </div>
   </div>
</div>

<script>
   $(document).ready(function() {
      $('#checkall').click(function() {
         var checkboxes = $(this).closest('form').find(':checkbox');
         checkboxes.prop('checked', $(this).is(':checked'));
      });
   });
</script>

<?= $this->endSection() ?>