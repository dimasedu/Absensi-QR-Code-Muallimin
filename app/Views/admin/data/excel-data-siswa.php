<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=export_siswa.xls");

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cetak Laporan Data Siswa</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/cetak.css">
	</head>
	<body>
		
		<center>
			<h3 class="content">DATA SISWA</h3>
		</center>

		
			<table width="100%" cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;">
                <tr>
                    <th><b>No</b></th>
                    <th><b>NIS</b></th>
                    <th><b>Nama Siswa</b></th>
                    <th><b>Jenis Kelamin</b></th>
                    <th><b>Kelas</b></th>
                    <th><b>Jurusan</b></th>
                    <th><b>No HP</b></th>
				</tr>
				<?php $i = 1;
            foreach ($data as $value) : ?>
               <tr>
                  <td><?= $i; ?></td>
                  
                  <td><?= $value['nis']; ?></td>
                  <td><b><?= $value['nama_siswa']; ?></b></td>
                  <td><?= $value['jenis_kelamin']; ?></td>
                  <td><?= $value['kelas']; ?></td>
                  <td><?= $value['jurusan']; ?></td>
                  <td><?= $value['no_hp']; ?></td>
                  
               </tr>
            <?php $i++;
            endforeach; ?>
			</table>
	</body>
</html>		