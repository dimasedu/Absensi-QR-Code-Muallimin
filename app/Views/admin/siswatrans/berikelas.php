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
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Beri Kelas</b></h4>
            Fitur ini digunakan untuk memberikan kelas kepada siswa setelah proses penambahan siswa baru pada modul Data Siswa.
          </div>
          <div class="card-body mx-5 my-3">
            
            <form action="<?= site_url('admin/berikelas-proses'); ?>" method="post" enctype="multipart/form-data" class="form-default">
            <?php if (!empty($data)) : ?>
                <table class="table table-hover" id="tabledata">
                    <thead class="text-primary">
                        <th><b><input type="checkbox" id="checkall" /></b></th>
                        <th><b>No</b></th>
                        <th><b>NIS</b></th>
                        <th><b>Nama Siswa</b></th>
                        <th><b>Jenis Kelamin</b></th>
                        <th><b>No HP</b></th>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($data as $value) : ?>
                        <tr>
                            <td><input type="checkbox" name="id[]" value="<?=$value['id_siswa'];?>"></td>
                            <td><?= $i; ?></td>
                            <td><?= $value['nis']; ?></td>
                            <td><b><?= $value['nama_siswa']; ?></b></td>
                            <td><?= $value['jenis_kelamin']; ?></td>
                            <td><?= $value['no_hp']; ?></td>
                            
                        </tr>
                        <?php $i++;
                        endforeach; ?>
                    </tbody>
                </table>
                

                <div class="row mt-4 align-items-end  ">
                    <div class="col-md-4 col-lg-3 form-group">
                        <label for="">Kelas</label>
                        <select name="kelas" id="kelas" class="form-control" required>
                            <option value="">--Pilih Kelas--</option>
                            <?php
                            foreach($kelas as $kel){
                                echo '<option value="'.$kel['kode'].'">'.$kel['kelas'].' ('.$kel['jurusan'].')</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3 form-group">
                        <label for="">Tahun Ajaran</label>
                        <input type="text" name="thajar" id="thajar" class="form-control" value="<?=$thajar?>" readonly>
                    </div>
                    <div class="col-lg-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons">download_done</i> Simpan Data
                    </button>
                    </div>
                
                    
                </div>

                <?php else : ?>
                    <div class="row">
                        <div class="col">
                            <h4 class="text-center text-danger">Data tidak ditemukan</h4>
                        </div>
                    </div>
                <?php endif; ?>
            
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
        $('#checkall').click(function() {
            var checkboxes = $(this).closest('form').find(':checkbox');
            checkboxes.prop('checked', $(this).is(':checked'));
        });
    });
</script>
<?= $this->endSection() ?>