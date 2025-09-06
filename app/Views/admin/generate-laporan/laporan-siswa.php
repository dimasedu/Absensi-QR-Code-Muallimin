<?= $this->extend('templates/laporan') ?>

<?= $this->section('content') ;
use App\Libraries\Siswalib;
$siswalib = new Siswalib();

?>
<table>
   <tr>
      <td><img src="<?= base_url('public/assets/img/logomtsn1bycanva.png'); ?>" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center" style="padding-top: 20px;margin: 0;">DAFTAR HADIR SISWA</h2>
         <h4 align="center" style="margin-top: 10px;margin-bottom:0"><?= $namaSekolah; ?></h4>
         <h4 align="center" style="margin-top: 10px;">TAHUN AJARAN <?= $tahunAjaran; ?></h4>
      </td>
      <td>
         <div style="width:100px"></div>
      </td>
   </tr>
</table>
<span>Bulan : <?= $bulan; ?> | Kelas : <?= "{$kelas['kelas']} {$kelas['jurusan']}"; ?></span>
<table align="center" border="1" style="font-size:9pt;margin-top:10px" cellpadding="3" >
   <thead>
   <tr>
      <td rowspan="4">No</td>
      <td rowspan="4">Nama Siswa</td>
      <td rowspan="4">NISN/NIS</td>
      <th colspan="<?= (count($tanggal) * 2); ?>">Hari/Tanggal</th>
   </tr>
   <tr>
   <?php foreach ($tanggal as $value) : ?>
         <td align="center" colspan="2"><b><?= $value->toLocalizedString('E'); ?></b></td>
      <?php endforeach; ?>
   </tr>
   <tr>
   <?php foreach ($tanggal as $value) : ?>
         <td align="center" colspan="2"><b><?= $value->toLocalizedString('d'); ?></b></td>
      <?php endforeach; ?>
   </tr>
   <tr>
   <?php foreach ($tanggal as $value) : ?>
         <td align="center"><b>M</b></td>
         <td align="center"><b>P</b></td>
      <?php endforeach; ?>
   </tr>
   </thead>
   <tbody>
   <?php 
   $no=0;
   
   foreach ($listSiswa as $siswa) : 
   
   ?>
      <tr>
         <td><?=$no;?></td>
         <td><?=$siswa['nama_siswa'];?></td>
         <td><?=$siswa['nis']?></td>
         <?php foreach ($listAbsen as $absen) : ?>
            <td><?= $absen[$no]['jam_masuk']; ?>
            <td><?= $absen[$no]['jam_keluar']; ?>
         <?php endforeach; ?>
      </tr>
   <?php 
$no++;
endforeach;?>
   
   </tbody>
</table>

<?= $this->endSection() ?>