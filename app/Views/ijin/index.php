<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ;
use App\Libraries\Siswalib;
$siswalib = new Siswalib();
?>
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
                           <h4 class="card-title"><b>Daftar Izin/Sakit</b></h4>
                           <p class="card-category">Daftar siswa melakukan izin / masuk.</p>
                        </div>
                        <div class="col-auto">
                           <a class="btn btn-primary" id="tabBtn"  href="<?= site_url('admin/absensi-izin/add'); ?>">
                              <i class="material-icons">add</i> Data Baru
                              <div class="ripple-container"></div>
                           </a>
                           <!-- <div class="nav-tabs-wrapper">
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                 <li class="nav-item">
                                    <a class="nav-link" id="tabBtn"  href="<?= site_url('admin/absensi-izin/add'); ?>">
                                       <i class="material-icons">add</i> Data Baru
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
                            <th><b>Tanggal</b></th>
                            <th><b>NIS</b></th>
                            <th><b>Nama Siswa</b></th>
                            <th><b>Kelas</b></th>
                            <th><b>Status</b></th>
                            <th><b>Keterangan</b></th>
                            <th><b>File Lampiran (Jika Ada)</b></th>
                            <th width="1%"><b>Aksi</b></th>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($query as $value) : ?>
                            <tr>
                                
                                <td><?= $nomor++; ?></td>
                                <td><?= date('d/m/Y',strtotime($value['tanggal'])); ?></td>
                                <td><?= $value['nis']; ?></td>
                                <td><?= $value['nama_siswa']; ?></td>
                                <td><b>
                                    <?= $siswalib->get_kelas($value['id_siswa']);?>
                                 </b></td>
                                <td><?= $value['tipe'] == 'ijin' ? '<span class="badge bg-success text-white">IZIN</span>' : '<span class="badge bg-danger text-white">SAKIT</span>'; ?></td>
                                <td><?= $value['keterangan']; ?></td>
                                <td><?= $value['file_ijin'] != '' ? '<a href="'.base_url('public/uploads/ijin/'.$value['file_ijin']).'" class="btn btn-xs btn-info btn-round" target="_blank">Preview</a>' : 'N/A'; ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">

                                        <a title="detail" href="<?= base_url('admin/absensi-izin/edit/' . $value['id']); ?>" class="btn btn-edit p-2" id="<?= $value['id']; ?>" >
                                        <i class="material-icons">edit</i>
                                        </a>
                                        <a title="Edit" href="<?= base_url('admin/absensi-ijin/delete/' . $value['id']); ?>" class="btn btn-danger p-2" id="<?= $value['id']; ?>" onclick="return confirm('Konfirmasi untuk menghapus data');" >
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

                    <?= !empty($query) ? $pager->links('broadcast', 'bootstrap_pagination') : ''; ?>
                </div>
            
            
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>