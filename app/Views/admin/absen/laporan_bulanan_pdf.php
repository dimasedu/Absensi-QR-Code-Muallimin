<?php
use App\Libraries\Siswalib;
$siswalib = new Siswalib();

$tahun = date('Y');
$bulan = date('m');
$jmlhari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
$jmlkolom = $jmlhari * 2;
?>
<b>
    <span>LAPORAN KEHADIRAN SISWA</span><br>
    <span>BULAN : <?= bulan_full(intval($bulan)); ?> <?= $tahun; ?></span><br>
</b>

<table border="1" width="100%" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th rowspan="4">No.</th>
            <th rowspan="4">NIS</th>
            <th rowspan="4">Nama Lengkap</th>
            <th colspan="<?= $jmlhari; ?>">Hari / Tanggal</th>
        </tr>
        <tr>
        <?php for ($i = 1; $i <= $jmlhari; $i++) : ?>

                
                    <td colspan="2"><?=date('D',strtotime($tahun.'-'.$bulan.'-'.$i))?></td>
                

            <?php endfor; ?>

        </tr>
            
            
    </thead>
</table>