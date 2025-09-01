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
                     <div class="row">
                        <div class="col">
                           <h4 class="card-title"><b>Antrian Cetak Kartu Pelajar</b></h4>
                           <p class="card-category">Daftar antrian siswa cetak kartu pelajar.</p>
                        </div>
                        <div class="col-auto">
                           <div class="nav-tabs-wrapper">
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                <li class="nav-item">
                                    <a class="nav-link" id="backBtn"  href="<?=site_url('admin/siswa/');?>">
                                       <i class="material-icons">close</i> Kembali
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 <li class="nav-item">
                                    <a class="nav-link"  href="<?= base_url('admin/siswa/cetakproses'); ?>">
                                       <i class="material-icons">print</i> Cetak Kartu
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="dataSiswa" class="card-body table-responsive pb-5">
                    <table class="table table-hover" id="tabledata">
                        <thead class="text-primary">
                            <th><b>No.</b></th>
                            <th><b>NIS</b></th>
                            <th><b>Nama Siswa</b></th>
                            <th><b>Kelas</b></th>
                            <th><b>Jurusan</b></th>
                            <th><b>Aksi</b></th>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($data as $value) : ?>
                            
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= $value['nis']; ?></td>
                                    <td><b><?= $value['nama_siswa']; ?></b></td>
                                    
                                    <td><b><?= $value['kelas'] ?? '-'; ?></b></td>
                                    <td><b><?= $value['jurusan'] ?? '-'; ?></b></td>
                                    <td>
                                    <a title="Hapus Data" href="<?= base_url('admin/siswa/cetakhapus/' . $value['id']); ?>" class="btn btn-danger p-2" id="<?= $value['nis']; ?>" onclick="return confirm('Konfirmasi untuk menghapus data');" >
                                <i class="material-icons">delete_forever</i>
                                </a>
                                    </td>
                                </tr>
                            <?php $no++;
                            endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
         </div>
      </div>
   </div>
</div>


<script>
    $(document).ready(function(){
        $('#tabledata').DataTable({
            pageLength: 50,
        });
    });
</script>
<?= $this->endSection() ?>