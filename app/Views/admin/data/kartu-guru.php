<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="2; url=<?=site_url('admin/guru')?>">
    <title>Cetak Kartu Guru</title>
</head>
<body style="font-family: arial;font-size: 12px;" onload="window.print();">
    
<?php

use CodeIgniter\Database\Query;

foreach($siswa as $row){
?>
    <div style="width:760px; height:243px;  margin-bottom:5px;border:1px solid #000;">
        
        <div style="width: 375px;height: 243px;background-image: url('<?=base_url();?>/public/assets/kapel/<?=$queryset->background_kartu;?>'); text-align:center; margin-left:0;margin-right:0; float:left; ">

            <img style="rotate:90deg; margin-left:300px; margin-top:85px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/assets/kapel/'.$queryset->logo1);?>" width="50px">

            <p style="font-family: arial; font-size: 9px; color: #000; margin-top:-30px; margin-left:170px;text-transform: uppercase; text-align: center; rotate:90deg; width:240px"><b>KEMENTERIAN AGAMA REPUBLIK INDONESIA<br>KEMENTERIAN AGAMA KOTA BATAM<br><?=$queryset->nama_sekolah;?></b></p>

            <img style="border: 1px solid #ffffff;margin-top: -80px; rotate:90deg; margin-left:50px;" src="<?=$row['foto'] !=NULL ?base_url('public/uploads/fotoguru/'.$row['foto']) : base_url('public/assets/img/paspoto.jpeg');?>" width="80px">

            <p style="font-family: arial; font-size: 12pt; color: #000; margin-top:-50px; margin-left:20px;text-transform: uppercase; text-align: center; rotate:90deg; width:240px">
                <b><?=$row['nama_guru'];?></b>
            </p>
            <img src="<?=base_url('public/uploads/qrcode/'.$row['qrcode'])?>" width="100" style="margin-top: -80px; rotate:90deg; margin-left:-220px;">
            <p style="font-family: arial; font-size: 10px; color: #000; margin-top:-60px; margin-left:-110px;text-transform: uppercase; text-align: center; rotate:90deg; width:240px">
                <b><b>NIP. <?=$row['nip']?></b>
            </p>
        </div>
        <div style="width: 375px;height: 243px;background-image: url('<?=base_url();?>/public/assets/kapel/<?=$queryset->background_kartu;?>'); text-align:center;;margin-right:0; float:right;">
            <p style="padding-top: 1px; rotate:-90deg;  margin-left:-100px; margin-top:110px; font-size:8pt; width:240px"><br><b>TATA TERTIB PENGGUNAAN</b>
            
            </p>
            <ol style="font-family: arial;font-size: 11px;text-align: justify; margin-left:-150px; margin-top:-240px; rotate:-90deg;">
                <li style="padding:5px;">Kartu ini berfungsi sebagai kartu<br>identitas;</a></li>
                <li style="padding:5px;">Kartu identitas ini wajib digunakan <br>selama bertugas;</li>
                <li style="padding:5px;">Kartu identitas ini berlaku selama <br>aktif mengajar/menjadi pegawai<br>MTsN 1 Batam;</li>
                <li style="padding:5px;">Apabila Katu identitas ini hilang, <br>dan bagi yang menemukan harap <br>dikembalikan ke MTsN 1 Batam</li>
                
            </ol>
            <p style="font-size: 11px; font-family: arial; width:240px; margin-left:100px; margin-top:30px;rotate:-90deg;"><br><br><?=$queryset->kota;?>, <?=date('F Y')?><br>
                Kepala Madrasah, </p>
                <img style="margin-top: -100px; margin-bottom:-40px; rotate:-90deg; margin-left:170px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/uploads/ttd/'.$queryset->stempel);?>" width="88px">
                <img style="margin-top: -190px; margin-left:230px; rotate:-90deg;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/uploads/ttd/'.$queryset->ttd_kepsek);?>" width="90px" >
                <p style="font-size: 11px; font-family: arial; width:240px; margin-left:210px; margin-top:-80px;rotate:-90deg;"><u><?=$queryset->nama_kepsek;?></u></b><br>NIP. <?=$queryset->nip_kepsek;?></p>
        </div>
    
    </div>



    
<?php
}
?>
    

</body>
</html>