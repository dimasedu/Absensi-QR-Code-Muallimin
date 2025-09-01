<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=export_guru.xls");

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cetak Laporan Data Guru</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/cetak.css">
	</head>
	<body>
		
		<center>
			<h3 class="content">DATA GURU</h3>
		</center>

		
			<table width="100%" cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;">
            <thead class="text-success">
            <th><b>No</b></th>
            <th><b>NUPTK</b></th>
            <th><b>Nama Guru</b></th>
            <th><b>Jenis Kelamin</b></th>
            <th><b>No HP</b></th>
            <th><b>Alamat</b></th>
         </thead>
         <tbody>
            <?php $i = 1;
            foreach ($data as $value) : ?>
               <tr>
                  <td><?= $i; ?></td>
                  <td>'<?= $value['nuptk']; ?></td>
                  <td><b><?= $value['nama_guru']; ?></b></td>
                  <td><?= $value['jenis_kelamin']; ?></td>
                  <td><?= $value['no_hp']; ?></td>
                  <td><?= $value['alamat']; ?></td>
                  
               </tr>
            <?php $i++;
            endforeach; ?>
         </tbody>
            
			</table>
	</body>
</html>		