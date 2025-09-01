<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <!-- <meta http-equiv="refresh" content="2; url=<?=site_url('admin/siswa/cetakclear')?>"> -->
    <title>CCS</title>
    

</head>

<body style="font-family: arial;font-size: 12px;position:absolute;">
    <?php

use CodeIgniter\Database\Query;

    foreach($siswa as $row){
    ?>

    <div style="width: 750px;height: 243px;margin: 10px;background-image: url('<?=base_url();?>/public/assets/kapel/<?=$queryset->background_kartu;?>');">
<!-- <img style="position: absolute;padding-left: 5px;padding-top: 5px;" class="img-responsive img" alt="Responsive image" src="../../dist/img/kpel.png"> -->
    <img style="position: absolute;padding-left: 12px;padding-top: 12px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/assets/kapel/'.$queryset->logo1);?>" width="50px">
     <img style="position: absolute;margin-left: 312px;padding-top: 12px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/assets/kapel/'.$queryset->logo2);?>" width="50px"> 
    <p style="position: absolute; font-family: arial; font-size: 10px; color: #000; padding-left: 70px;margin-top:18px;text-transform: uppercase; text-align: center;"><b style="font-size: 10px">KEMENTERIAN AGAMA REPUBLIK INDONESIA</b><br>KEMENTERIAN AGAMA KOTA BATAM<br><b style="font-size: 10px"><?=$queryset->nama_sekolah;?></b></p>
    <p style="padding-left: 110px;padding-top: 60px;color: #000; "><b style="font-size: 13px">STUDENT CARE CARD</b></p><br>
     <img style="border: 1px solid #ffffff;position: absolute;margin-left: 20px;margin-top: 0px;" src="<?=$row['foto'] !=NULL ?base_url('public/uploads/fotosiswa/'.$row['foto']) : base_url('public/assets/img/paspoto.jpeg');?>" width="80px">
     <!-- <img src="<?=base_url('public/uploads/qrcode/'.$row['qrcode'])?>" width="150" style="border: 1px solid #ffffff;position: absolute;margin-left: 20px;margin-top: -10px;"> -->
      
		<table style="margin-top: -20px;margin-left: 120px; position: relative;font-family: arial;font-size: 11px;">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><b><?=$row['nama_siswa'];?></b></td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>:</td>
               <td><b><?=$row['nis']?></b></td>
            </tr>
           
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><b><?=$row['jenis_kelamin']?></b></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td><b><?=$row['kelas']?> <?=$row['jurusan']?></b></td>
            </tr>

            <tr>
                <td>No. Whatsapp</td>
                <td>:</td>
                <td><b><?=$row['no_hp']?></b></td>
            </tr>
            <tr>
                <td>Berlaku</td>
                <td>:</td>
                <td><b>Selama Menjadi Siswa</b></td>
            </tr>
            <tr>
                <td colspan="3"><p style="font-size: 8px; font-family: arial;position: absolute;">Alamat : <?=$queryset->alamat?> | Email : <?=$queryset->email;?> | Telp.<?=$queryset->no_telp;?> | Website : <?=$queryset->website;?></p></td>
            </tr>
        </table>
		
       <!-- <p style="padding-left: 20px;font-size: 8px; font-family: arial;position: absolute;">Alamat: Jalan Golden Prawn,  Kecamatan Bengkong - Batam<br> Email: mtsnbatam1@gmail.com | Telp. +62 8127668889| Website: www.mtsn1batam.sch.id</p> -->
      <br><br>
	   <p style="margin-top: -206px;padding-left: 480px;padding-top: 1px;"><br><b>TATA TERTIB PENGGUNAAN</b><br>
<ol style="padding-left: 400px; font-family: arial;font-size: 9px;text-align: justify;padding-right: 10px">
                      <li>Student Care Card ini wajib dipakai selama menjadi siswa aktif MTsN 1 Batam, hanya berlaku bagi pemiliknya serta wajib dibawa setiap hari masuk dan pulang madrasah;</a></li>
                      <li>Student Care Card berfungsi sebagai Card System seperti kartu pelajar, perpustakaan, presensi dll;</li>
                      <li>Segala bentuk penyalahgunaan Student Care Card ini dikenakan sanksi;</li>
                      <li>Apabila Student Care Card ini hilang, dan bagi yang menemukan harap dikembalikan ke MTsN 1 Batam</li>
                      
                    </ol>
        </p>
        <br>
        <div style="position: absolute;padding-left: 430px; width:390px; margin-top:-30px;">
        <table border="0" width="100%" style="">
            <tr>
                <td><img src="<?=base_url('public/uploads/qrcode/'.$row['qrcode'])?>" width="95"></td>
                <br><td style="font-size: 10px; font-family: arial;"><?=$queryset->kota;?>, Oktober 2024<br>
                Kepala Madrasah, <!--<br>Koordinator KD,--><br><img style="margin-top: -50px; margin-bottom:-40px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/uploads/ttd/'.$queryset->stempel);?>" width="88px"><img style="margin-top: -50px; margin-bottom:-20px; margin-left:-30px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/uploads/ttd/'.$queryset->ttd_kepsek);?>" width="90px" ><br><br><u><?=$queryset->nama_kepsek;?></u></b><br>NIP. <?=$queryset->nip_kepsek;?></td>
            </tr>
        </table>
        </div>
</div>

<?php
    }
?>

</body>
</html>
