<div class="card-body table-responsive">
  <?php if (!$empty) : ?>
    <table class="table table-hover" id="tablekelas">
      <thead class="text-primary">
        <th><b>No</b></th>
        <th><b>Kode</b></th>
        <th><b>Kelas / Tingkat</b></th>
        <th><b>Jurusan</b></th>
        <th><b>Walikelas</b></th>
        <th><b>Aksi</b></th>
      </thead>
      <tbody>
        <?php $i = 1;
        foreach ($data as $value) : ?>
          <tr>
            <td><?= $i; ?></td>
            <td><?= $value['kode']; ?></td>
            <td><b><?= $value['kelas']; ?></b></td>
            <td><?= $value['jurusan']; ?></td>
            <td><?= $value['nama_guru']; ?></td>
            <td>
              <a href="<?= base_url('admin/kelas/' . $value['id_kelas'] . '/edit'); ?>" type="button" class="btn btn-primary p-2" id="<?= $value['id_kelas']; ?>">
                <i class="material-icons">edit</i>
              </a>
              <form action="<?= base_url('admin/kelas/' . $value['id_kelas']); ?>" method="post" class="d-inline">
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" value="DELETE">
                <button onclick="return confirm('Konfirmasi untuk menghapus data');" type="submit" class="btn btn-danger p-2" id="<?= $value['id_kelas']; ?>">
                  <i class="material-icons">delete_forever</i>
                </button>
              </form>
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
  $(document).ready(function(){
    $('#tablekelas').DataTable()
  });
</script>