<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <!-- <meta http-equiv="refresh" content="2; url=<?=site_url('admin/siswa/cetakclear')?>"> -->
    <title>CCS</title>
    <style>
        .div1 {
            rotate: 90deg;   
        }
    </style>

</head>

<body style="font-family: arial;font-size: 12px; margin:0;">
    <?php

use CodeIgniter\Database\Query;

    foreach($siswa as $row){
    ?>
    <div style="width:750px; height:243px;">
    <div style="width: 243px;height: 375px;background-image: url('<?=base_url();?>/public/assets/kapel/<?=$queryset->background_kartu;?>'); text-align:center; border:1px solid #ddd; rotate: 90deg; margin-left:0;margin-right:0;">
                <!-- <img style="position: absolute;padding-left: 5px;padding-top: 5px;" class="img-responsive img" alt="Responsive image" src="../../dist/img/kpel.png"> -->
                    <img style="padding-left: 12px;padding-top: 12px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/assets/kapel/'.$queryset->logo1);?>" width="50px">
                    <!-- <img style="position: absolute;margin-left: 312px;padding-top: 12px;" class="img-responsive img" alt="Responsive image" src="<?=base_url('public/assets/kapel/'.$queryset->logo2);?>" width="50px">  -->
                    <p style="font-family: arial; font-size: 10px; color: #000; margin-top:5px;text-transform: uppercase; text-align: center;"><b style="font-size: 10px">KEMENTERIAN AGAMA REPUBLIK INDONESIA</b><br><b style="font-size: 10px">KEMENTERIAN AGAMA KOTA BATAM<br><b style="font-size: 10px"><?=$queryset->nama_sekolah;?></b></p>
                    <img style="border: 1px solid #ffffff;margin-top: 0px;" src="<?=$row['foto'] !=NULL ?base_url('public/uploads/fotosiswa/'.$row['foto']) : base_url('public/assets/img/paspoto.jpeg');?>" width="80px">
                    <!-- <img src="<?=base_url('public/uploads/qrcode/'.$row['qrcode'])?>" width="150" style="border: 1px solid #ffffff;position: absolute;margin-left: 20px;margin-top: -10px;"> -->
                    
                        <center>
                        <span style="font-size:12pt; font-weight:bold; margin-top:10px;"><?=$row['nama_guru'];?></span>
                        </center>
                    <img src="<?=base_url('public/uploads/qrcode/'.$row['qrcode'])?>" width="100"><br>
                    <span style="font-size:10pt;"><b>NIP. <?=$row['nip']?></b></span>
                    
                </div>
                <div style="width: 243px;height: 375px;background-image: url('<?=base_url();?>/public/assets/kapel/<?=$queryset->background_kartu;?>'); text-align:center; border:1px solid #ddd; rotate: -90deg; margin-left:-110px; float:right;">
                <p style="padding-top: 1px;"><br><b>TATA TERTIB PENGGUNAAN</b><br>
                    <ol style="font-family: arial;font-size: 11px;text-align: justify;padding-right: 10px">
                                        <li>Kartu ini berfungsi sebagai kartu identitas;</a></li>
                                        <li>Kartu identitas ini wajib digunakan selama bertugas;</li>
                                        <li>Kartu identitas ini berlaku selama aktif mengajar/menjadi pegawai MTsN 1 Batam;</li>
                                        <li>Apabila Katu identitas ini hilang, dan bagi yang menemukan harap dikembalikan ke MTsN 1 Batam</li>
                                        
                                        </ol>
                            </p>
        <table border="0" width="100%" style="">
            <tr>
                
                <td style="font-size: 10px; font-family: arial;"><br><br><?=$queryset->kota;?>, <?=date('F Y')?><br>
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
