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
            <h4 class="card-title"><b>Edit Data Mutasi Siswa</b></h4>
            Fitur ini digunakan untuk mengubah data mutasi siswa.
          </div>
          <div class="card-body mx-5 my-3">
            
            

                <table class="table table-hover" id="tabledata">
                    <thead class="text-primary">
                        <th><b>No</b></th>
                        <th><b>NIS</b></th>
                        <th><b>Nama Siswa</b></th>
                        <th><b>Kelas</b></th>
                        <th><b>Th. Ajar</b></th>
                        <th><b>Tipe</b></th>
                        <th><b>Aksi</b></th>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($data as $value) : ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $value['nis']; ?></td>
                            <td><b><?= $value['nama_siswa']; ?></b></td>
                            <td><?= $value['kelas'].' '.$value['jurusan']; ?></td>
                            <td><?= $value['th_ajar']; ?></td>
                            <td><?php
                            
                            if($value['tipe'] == 'masuk') echo '<span class="badge badge-info">Beri Kelas</span>';
                            if($value['tipe'] == 'naik') echo '<span class="badge badge-success">Naik Kelas</span>';
                            if($value['tipe'] == 'lulus') echo '<span class="badge badge-danger">Lulus / Keluar</span>';?>
                            </td>
                            <td>
                            <a href="<?= base_url('admin/mutasi-edit-ubah/' . $value['id']); ?>" class="btn btn-primary p-2">
                                <i class="material-icons">edit</i>
                            </a>&nbsp;
                            <a href="<?= base_url('admin/mutasi-edit-delete/' . $value['id']); ?>" class="btn btn-danger p-2" onclick="return confirm('PERHATIAN!! Data tidak dapat dikembalikan. Apakah yakin akan menghapus data ini?')">
                                <i class="material-icons">delete_forever</i>
                            </a>
                           
                            </td>
                        </tr>
                        <?php $i++;
                        endforeach; ?>
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
            pageLength : 50,
        });


        $('#checkall').click(function() {
            var checkboxes = $(this).closest('form').find(':checkbox');
            checkboxes.prop('checked', $(this).is(':checked'));
        });
    });
</script>
<?= $this->endSection() ?>