<?php if (!empty($data)) : ?>
<table class="table table-hover" id="tabledata">
    <thead class="text-primary">
        <th><b><input type="checkbox" id="checkall" /></b></th>
        <th><b>No</b></th>
        <th><b>NIS</b></th>
        <th><b>Nama Siswa</b></th>
        <th><b>Jenis Kelamin</b></th>
        <th><b>Kelas</b></th>
        <th><b>Th. Ajar</b></th>
        <th><b>Keterangan</b></th>
        
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
            <td><?= $value['kelas'].' '.$value['jurusan']; ?></td>
            <td><?= $value['th_ajar']; ?></td>
            <td><textarea name="keterangan[]" id="keterangan" class="form-control" placeholder="Masukkan Keterangan (Opsional)"></textarea></td>
        </tr>
        <?php $i++;
        endforeach; ?>
    </tbody>
</table>


<div class="row mt-3">
    <div class="col-lg-4">
        <label>Naik / Pindah Ke Kelas</label>
        <select name="kelas" id="kelas" class="form-control" required>
            <option value="">--Pilih Kelas--</option>
            <option value="lulus">SISWA LULUS / KELUAR</option>
            <?php
            foreach($kelas as $kel){
                echo '<option value="'.$kel['kode'].'">'.$kel['kelas'].' ('.$kel['jurusan'].')</option>';
            }
            ?>
        </select>
    </div>
    <div class="col-lg-4">
        <label>Tahun Ajaran</label>
        <input type="text" name="thajar" id="thajar" class="form-control" value="<?=$thajar_next;?>" readonly>
    </div>
    <div class="col-lg-4">
    <button type="submit" class="btn btn-success">
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

<script>
    $(document).ready(function(){
        $('#checkall').click(function() {
            var checkboxes = $(this).closest('form').find(':checkbox');
            checkboxes.prop('checked', $(this).is(':checked'));
        });
    });
</script>