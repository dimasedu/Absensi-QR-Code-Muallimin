<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=laporan_ijin_siswa_".date('dmY',strtotime($tanggal)).'-'.strtolower($namakelas).".xls");
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

    <b><span>LAPORAN IJIN/SAKIT SISWA</span><br>
    <span>Tanggal : <?= $tanggal; ?></span><br>
    </b>

    <?php if (!empty($query)) : ?>
            <table border="1" width="100%" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <thead class="text-primary">
                    <th><b>No.</b></th>
                    <th><b>NIS</b></th>
                    <th><b>Nama Siswa</b></th>
                    <th><b>Kelas</b></th>
                    <th><b>Ijin/Sakit</b></th>
                    <th><b>Keterangan</b></th>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($query as $value) : ?>
                        <?php
                        $kehadiran = kehadiran($value['tipe']);
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
        case "I":
            $color = 'green';
            $text = 'Ijin';
            break;
        default:
            $color = 'red';
            $text = 'Sakit';
            break;
    }

    return ['color' => $color, 'text' => $text];
}
?>
</body>
</html>