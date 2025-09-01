<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=laporan_harian_absensi_siswa_".date('dmY',strtotime($tanggal)).".xls");
use App\Libraries\Siswalib;
$siswalib = new Siswalib();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian Absensi</title>
</head>
<body>

    <b><span>LAPORAN KEHADIRAN SISWA</span><br>
    <span>Tanggal : <?= $tanggal; ?></span><br>
    </b>

    <?php if (!empty($query)) : ?>
            <table border="1" width="100%" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <thead class="text-primary">
                    <th><b>No.</b></th>
                    <th><b>NIS</b></th>
                    <th><b>Nama Siswa</b></th>
                    <th><b>Kelas</b></th>
                    <th><b>Kehadiran</b></th>
                    <th><b>Jam masuk</b></th>
                    <th><b>Jam pulang</b></th>
                    <th><b>Keterangan</b></th>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($query as $value) : ?>
                        <?php
                        $idKehadiran = intval($value['id_kehadiran'] ?? ($lewat ? 5 : 4));
                        $kehadiran = kehadiran($idKehadiran);
                        ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $value['nis']; ?></td>
                            <td><b><?= strtoupper($value['nama_siswa']); ?></b></td>
                            <td><b><?= strtoupper($siswalib->get_kelas($value['id_siswa'])); ?></b></td>
                            <td>
                                <span style="color:<?= $kehadiran['color'];?>; text-align:center;">
                                    <b><?= $kehadiran['text']; ?></b>
                                </span>
                            </td>
                            <td><b><?= $value['jam_masuk'] ?? '-'; ?></b></td>
                            <td><b><?= $value['jam_keluar'] ?? '-'; ?></b></td>
                            <td><?= $value['keterangan'] ?? '-'; ?></td>
                            
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

<?php
function kehadiran($kehadiran): array
{
    $text = '';
    $color = '';
    switch ($kehadiran) {
        case 1:
            $color = 'green';
            $text = 'Hadir';
            break;
        case 2:
            $color = 'orange';
            $text = 'Sakit';
            break;
        case 3:
            $color = 'blue';
            $text = 'Izin';
            break;
        case 4:
            $color = 'red';
            $text = 'Tanpa keterangan';
            break;
        case 5:
        default:
            $color = 'grey';
            $text = 'Belum tersedia';
            break;
    }

    return ['color' => $color, 'text' => $text];
}
?>
</body>
</html>