<div class="card-body table-responsive">

      <a href="<?=base_url('admin/guru/excel/');?>" class="btn btn-success ml-1 pl-3 py-3"><i class="material-icons mr-1">file_download</i> Export Excel</a>&nbsp;
      <button type="button" class="btn btn-primary ml-1 pl-3 py-3" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="material-icons mr-1">file_upload</i> Import Data
                     </button>&nbsp;

         <!-- <button type="button" id="checkall" class="btn btn-danger ml-1 pl-3 py-3">
         <i class="material-icons mr-1">done_all</i> Pilih Semua
         </button> -->
         <button class="btn btn-danger ml-1 pl-3 py-3" type="submit"><i class="material-icons mr-2">print</i> Cetak Kartu</button>
      <table id="tabledata">
         <thead class="text-success">
            <tr>
               <th><b><input type="checkbox" id="checkall" /></b></th>
               <th>No</th>
               <th>NBM</th>
               <th>Nama Guru</th>
               <th>Jabatan / Unit Kerja</th>
               <th>Status</th>
               <th>No HP</th>
               <th>Alamat</th>
               <th width="1%">Aksi</th>
            </tr>
         </thead>
         <tbody>
            <?php $i = 1;
            foreach ($data as $value) : ?>
               <tr>
               <td><input type="checkbox" name="id[]" value="<?=$value['id_guru'];?>"></td>
                  <td><?= $i; ?></td>
                  <td><a href="<?=site_url('admin/guru/detail/'.$value['id_guru']);?>"><?= $value['nuptk']; ?></a></td>
                  <td><b><?= $value['nama_guru']; ?></b></td>
                  <td><?= $value['jabatan'] == 'tendik' ? 'Tendik' : 'Guru/Pendidik'; ?></td>
                  <td><?= $value['status_guru']; ?></td>
                  <td><?= $value['no_hp']; ?></td>
                  <td><?= $value['alamat']; ?></td>
                  <td>
                     <div class="d-flex justify-content-center">
                        <a title="Edit" href="<?= base_url('admin/guru/edit/' . $value['id_guru']); ?>" class="btn btn-success p-2" id="<?= $value['nuptk']; ?>">
                           <i class="material-icons">edit</i>
                        </a>
                        <form action="<?= base_url('admin/guru/delete/' . $value['id_guru']); ?>" method="post" class="d-inline">
                           <?= csrf_field(); ?>
                           <input type="hidden" name="_method" value="DELETE">
                           <button title="Delete" onclick="return confirm('Konfirmasi untuk menghapus data');" type="submit" class="btn btn-danger p-2" id="<?= $value['nuptk']; ?>">
                              <i class="material-icons">delete_forever</i>
                           </button>
                        </form>
                        <a title="Download QR Code" href="<?= base_url('admin/qr/guru/' . $value['id_guru'] . '/download'); ?>" class="btn btn-info p-2">
                           <i class="material-icons">qr_code</i>
                        </a>
                     </div>
                  </td>
               </tr>
            <?php $i++;
            endforeach; ?>
         </tbody>
      </table>
   
</div>

<script>
   $(document).ready(function() {
      $('#tabledata').DataTable({
         pageLength : 50,
         ordering : false,
      });

      $('#checkall').click(function() {
         var checkboxes = $(this).closest('form').find(':checkbox');
         checkboxes.prop('checked', $(this).is(':checked'));
      });
   });
</script>