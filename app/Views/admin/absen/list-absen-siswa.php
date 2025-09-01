<div class="card-body">
    <div class="row">
        <div class="col-auto me-auto">
            <div class="pt-3 pl-3">
                <h4><b>Absen Siswa</b></h4>
                <p>Daftar siswa muncul disini</p>
            </div>
        </div>
        <div class="col">
            <a href="#" class="btn btn-primary pl-3 mr-3 mt-3" onclick="kelas = onDateChange()" data-toggle="tab">
                <i class="material-icons mr-2">refresh</i> Refresh
            </a>

            <a href="#" class="btn btn-success pl-3 mr-3 mt-3" data-bs-toggle="modal" data-bs-target="#harianModal">
                <i class="material-icons mr-2">fact_check</i> LAPORAN HARIAN
            </a>

            <a href="#" class="btn btn-danger pl-3 mr-3 mt-3" data-bs-toggle="modal" data-bs-target="#ijinModal">
                <i class="material-icons mr-2">medical_information</i> LAPORAN IJIN/SAKIT
            </a>

            <a href="#" class="btn btn-info pl-3 mr-3 mt-3" data-bs-toggle="modal" data-bs-target="#bulananModal">
                <i class="material-icons mr-2">print</i> LAPORAN BULANAN
            </a>
        </div>
        <div class="col-auto">
            <div class="px-4">
                <h3 class="text-end">
                    <b class="text-primary"><?= $kelas; ?></b>
                </h3>
            </div>
        </div>
    </div>

    <div id="dataSiswa" class="card-body table-responsive pb-5">
        <?php if (!empty($data)) : ?>
            <table class="table table-hover">
                <thead class="text-primary">
                    <th><b>No.</b></th>
                    <th><b>NIS</b></th>
                    <th><b>Nama Siswa</b></th>
                    <th><b>Kehadiran</b></th>
                    <th><b>Jam masuk</b></th>
                    <th><b>Jam pulang</b></th>
                    <th><b>Keterangan</b></th>
                    <th><b>Aksi</b></th>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($data as $value) : ?>
                        <?php
                        $idKehadiran = intval($value['id_kehadiran'] ?? ($lewat ? 5 : 4));
                        $kehadiran = kehadiran($idKehadiran);
                        ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $value['nis']; ?></td>
                            <td><b><?= $value['nama_siswa']; ?></b></td>
                            <td>
                                <p class="p-2 w-100 btn btn-<?= $kehadiran['color']; ?> text-center">
                                    <b><?= $kehadiran['text']; ?></b>
                                </p>
                            </td>
                            <td><b><?= $value['jam_masuk'] ?? '-'; ?></b></td>
                            <td><b><?= $value['jam_keluar'] ?? '-'; ?></b></td>
                            <td><?= $value['keterangan'] ?? '-'; ?></td>
                            <td>
                                <?php if (!$lewat) : ?>
                                    <button data-toggle="modal" data-target="#ubahModal" onclick="getDataKehadiran(<?= $value['id_presensi'] ?? '-1'; ?>, <?= $value['id_siswa']; ?>)" class="btn btn-info p-2" id="<?= $value['nis']; ?>">
                                        <i class="material-icons">edit</i>
                                        Edit
                                    </button>
                                <?php else : ?>
                                    <button class="btn btn-disabled p-2">No Action</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php $no++;
                    endforeach ?>
                </tbody>
            </table>
        <?php
        else :
        ?>
            <div class="row">
                <div class="col">
                    <h4 class="text-center text-danger">Data tidak ditemukan</h4>
                </div>
            </div>
        <?php
        endif; ?>
    </div>
</div>

<!---laporan harian--->
<form 
    action="<?=site_url('admin/absen-siswa-lapharian')?>" 
    name="laporanAbsen" 
    id="laporanAbsen"
    method="POST"
>

<input 
    type="hidden"
    name="idkelas"
    value="<?=$id_kelas;?>"
/>
<input 
    type="hidden"
    name="tanggal"
    value="<?=$tanggal;?>"
/>

<div class="modal fade" id="harianModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Laporan Harian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
                <div class="form-group">
                    <label>Format Laporan</label>
                    <select class="form-control" id="format" name="format" required>
                        <option value="">--Pilih--</option>
                        <option value="excel">Download Excel</option>
                        <option value="pdf">Download PDF</option>
                    </select>
                </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Proses Laporan</button>
        </div>
    </div>
    </div>
</div>

</form>

<!---laporan harian--->
<form 
    action="<?=site_url('admin/absen-siswa-lapbulanan')?>" 
    name="laporanAbsenBulanan" 
    id="laporanAbsenBulanan"
    method="POST"
>

<input 
    type="hidden"
    name="idkelas"
    value="<?=$id_kelas;?>"
/>


<div class="modal fade" id="bulananModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Laporan Bulanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

                <div class="form-group">
                    <label>Pilih Bulan</label>
                    <select class="form-control" id="bulan" name="bulan" required>
                        <option value="">--Pilih--</option>
                        <?php echo option_bulan(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Tahun</label>
                    <select class="form-control" id="format" name="format" required>
                        <option value="">--Pilih--</option>
                        <?php for ($i = 2024; $i <= date('Y') + 3; $i++) : ?>
                            <option value="<?=$i;?>"><?=$i;?></option>
                        <?php endfor; ?>
                        
                    </select>
                </div>
            
                <div class="form-group">
                    <label>Format Laporan</label>
                    <select class="form-control" id="format" name="format" required>
                        <option value="">--Pilih--</option>
                        <option value="excel">Download Excel</option>
                        <option value="pdf">Download PDF</option>
                    </select>
                </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Proses Laporan</button>
        </div>
    </div>
    </div>
</div>

</form>

<!---laporan harian--->
<form 
    action="<?=site_url('admin/absen-siswa-lapijin')?>" 
    name="laporanAbsen" 
    id="laporanAbsen"
    method="POST"
>
<input 
    type="hidden"
    name="idkelas"
    value="<?=$id_kelas;?>"
/>
<input 
    type="hidden"
    name="tanggal"
    value="<?=$tanggal;?>"
/>

<div class="modal fade" id="ijinModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Laporan Siswa Ijin/Sakit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
                <div class="form-group">
                    <label>Format Laporan</label>
                    <select class="form-control" id="format" name="format" required>
                        <option value="">--Pilih--</option>
                        <option value="excel">Download Excel</option>
                        <option value="pdf">Download PDF</option>
                    </select>
                </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Proses Laporan</button>
        </div>
        
    </div>
    </div>
</div>
</form>

<?php
function kehadiran($kehadiran): array
{
    $text = '';
    $color = '';
    switch ($kehadiran) {
        case 1:
            $color = 'success';
            $text = 'Hadir';
            break;
        case 2:
            $color = 'warning';
            $text = 'Sakit';
            break;
        case 3:
            $color = 'info';
            $text = 'Izin';
            break;
        case 4:
            $color = 'danger';
            $text = 'Tanpa keterangan';
            break;
        case 5:
        default:
            $color = 'disabled';
            $text = 'Belum tersedia';
            break;
    }

    return ['color' => $color, 'text' => $text];
}
?>