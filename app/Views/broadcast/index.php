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
               <div class="card-header card-header-tabs card-header-success">
                  <div class="nav-tabs-navigation">
                     <div class="row align-items-center">
                        <div class="col">
                           <h4 class="card-title"><b>Daftar Broadcast WA</b></h4>
                           <p class="card-category">Daftar pesan broadcast</p>
                        </div>
                        <div class="col-auto">
                           <a class="btn btn-primary" id="tabBtn"  href="<?= base_url('admin/broadcast/add'); ?>">
                              <i class="material-icons">add</i> Buat Baru
                              <div class="ripple-container"></div>
                           </a>
                           <a class="btn btn-info ml-md-3 mt-3 mt-md-0" id="refreshBtn" href="<?=site_url('admin/broadcast')?>" data-toggle="tab">
                              <i class="material-icons">refresh</i> Refresh
                              <div class="ripple-container"></div>
                           </a>
                           <!-- <div class="nav-tabs-wrapper">
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                 <li class="nav-item">
                                    <a class="nav-link" id="tabBtn"  href="<?= base_url('admin/broadcast/add'); ?>">
                                       <i class="material-icons">add</i> Buat Baru
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 <li class="nav-item">
                                    <a class="nav-link" id="refreshBtn" href="<?=site_url('admin/broadcast')?>" data-toggle="tab">
                                       <i class="material-icons">refresh</i> Refresh
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                              </ul>
                           </div> -->
                        </div>
                     </div>
                  </div>
               </div>
               
                
                <div class="card-body table-responsive">
                 <?php if (!empty($query)) : ?>
                    <table class="table table-hover">
                        <thead class="text-primary">
                            <th><b>No</b></th>
                            <th><b>Judul Broadcast</b></th>
                            <th><b>Tanggal Kirim</b></th>
                            <th><b>Group Kontak</b></th>
                            <th><b>Total Kontak</b></th>
                            <th><b>Status</b></th>
                            <th width="1%"><b>Aksi</b></th>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($query as $value) : ?>
                            <tr>
                                
                                <td><?= $nomor++; ?></td>
                                <td><b><?= $value['judul']; ?></td>
                                <td><?= date('d/m/Y H:i:s',strtotime($value['sent_at'])); ?> WIB</td>
                                <td><b>
                                 <?php
                                 if($value['id_kelas'] != "gr"):
                                 ?> 
                                    <?= $value['kelas']; ?> (<?=$value['jurusan']?>)
                                 <?php
                                 else:
                                    echo 'Guru & Staf';
                                 endif;
                                 ?>   
                                 </b></td>
                                <td><?= number_format($value['total_kontak']); ?> Nomor</td>
                                <td><?= $value['is_finished'] == 'Y' ? '<span class="badge bg-success text-white">Selesai</span>' : '<span class="badge bg-danger text-white">Proses</span>'; ?></td>
                               
                                <td>
                                    <div class="d-flex justify-content-center">

                                        <a title="detail" href="<?= base_url('admin/broadcast/detail/' . $value['bc_id']); ?>" class="btn btn-edit p-2" id="<?= $value['bc_id']; ?>" >
                                        <i class="material-icons">pageview</i>
                                        </a>
                                        <a title="Edit" href="<?= base_url('admin/broadcast/delete/' . $value['bc_id']); ?>" class="btn btn-danger p-2" id="<?= $value['bc_id']; ?>" onclick="return confirm('Konfirmasi untuk menghapus data');" >
                                        <i class="material-icons">delete_forever</i>
                                        </a>
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

                <div class="card-footer clearfix">

                    <?= $pager->links('broadcast', 'bootstrap_pagination'); ?>
                </div>
            
            
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>