<?php

/**
 * File ini merupakan file helper untuk fungsi-fungsi yang sering digunakan
 * 
 * @author : Mas Edu Bae
 * @email : edudimas1@gmail.com
 * @date : 2025-07-02
 * @version : 1.0.0
 * 
 * 
 */


 function bulan_simple($param)
 {
 
 $bulan = array(
 '01'=>'Jan',
 '02'=>'Feb',
 '03'=>'Mar',
 '04'=>'Apr',
 '05'=>'Mei',
 '06'=>'Jun',
 '07'=>'Jul',
 '08'=>'Aug',
 '09'=>'Sep',
 '10'=>'Okt',
 '11'=>'Nop',
 '12'=>'Des',
 );
 
 $show = $bulan[$param];
 
 return $show;
 
 }
 
  function bulan_full($param)
 {
 $paramx = $param;
if($param == 0){
$show = '-';
}else{
if($paramx <10){
$paramy = "0".$paramx;
}else{
 $paramy = $paramx;
}

$bulan = array(
'01'=>'Januari',
'02'=>'Februari',
'03'=>'Maret',
'04'=>'April',
'05'=>'Mei',
'06'=>'Juni',
'07'=>'Juli',
'08'=>'Augustus',
'09'=>'September',
'10'=>'Oktober',
'11'=>'Nopember',
'12'=>'Desember',
);

$show = $bulan[$paramy];
}
 
 return $show;
 
 }
 
 
 function get_tgl()
 {
     for($i=1;$i<=31;$i++)
     {
             if($i<=9){
             $i = "0$i";
             }else{
             $i = $i;
             }
             
             echo "<option value=\"$i\">$i</option>";
     }			
 }


/**
* -------------------------------------------------
* Fungsi untuk menampilkan combobox
* 
* -------------------------------------------------
**/ 


function option_tanggal()
{
for($i=1; $i<=31; $i++)
{
if($i <=9)
{
 $i = "0$i";
}else
{
 $i = $i;
}

if(date('d') == $i)
{
 $selected = "selected='selected'";
}else
{
 $selected = '';
}
echo "<option $selected>$i</option>";
}
}

function option_bulan(){
 
 $bulan = array(
 '01'=>'Januari',
 '02'=>'Februari',
 '03'=>'Maret',
 '04'=>'April',
 '05'=>'Mei',
 '06'=>'Juni',
 '07'=>'Juli',
 '08'=>'Agustus',
 '09'=>'September',
 '10'=>'Oktober',
 '11'=>'Nopember',
 '12'=>'Desember',
 );
 
 $parambl = date('m');
 
 for($i=1;$i<=12;$i++){
 if($i <=9){
 $i = "0$i";
 }else{
 $i = $i;
 }
 
 if($parambl == $i){
     $ceked = "selected=\"selected\"";
     }else{
         
         $ceked = "";
         }
 echo "<option value=\"$i\" $ceked>$bulan[$i]</option>";
 }
 }
 
 
 function option_tahun()
 {
     $y = date('Y');
     
     
 
     for($i=2013;$i<=2030;$i++){
         
     if($i == $y){
         
     $ceked = " selected=\"selected\"";
     }else{
         
         $ceked = "";
         }
     echo "<option value=\"$i\"$ceked>$i</option>";
     }
 
 }


function option_tahun_full()
 {
     $y = date('Y');
     
     
 
     for($i=1950;$i<=2000;$i++){
         
     if($i == $y){
         
     $ceked = " selected=\"selected\"";
     }else{
         
         $ceked = "";
         }
     echo "<option value=\"$i\"$ceked>$i</option>";
     }
 
 }


/**
* -------------------------------------------------
* Fungsi untuk menampilkan combobox edit
* 
* -------------------------------------------------
**/

function option_tgl_edit($param)
  {
     for($i=1;$i<=31;$i++)
     {
             if($i<=9){
             $i = "0$i";
             }else{
             $i = $i;
             }
             
   if($i == $param)
   {
     $select = 'selected="selected"';
   }else
   {
   
     $select = '';
   
   }
   
   
             echo "<option value=\"$i\" $select>$i</option>";
     }			
 }

function option_bulan_edit($param){
 
 $bulan = array(
 '01'=>'Januari',
 '02'=>'Februari',
 '03'=>'Maret',
 '04'=>'April',
 '05'=>'Mei',
 '06'=>'Juni',
 '07'=>'Juli',
 '08'=>'Augustus',
 '09'=>'September',
 '10'=>'Oktober',
 '11'=>'Nopember',
 '12'=>'Desember',
 );
 
 
 for($i=1;$i<=12;$i++){
 if($i <=9){
 $i = "0$i";
 }else{
 $i = $i;
 }
 
 if($param == $i){
     $ceked = "selected=\"selected\"";
     }else{
         
         $ceked = "";
         }
 echo "<option value=\"$i\" $ceked>$bulan[$i]</option>";
 }
 }


function option_tahun_edit($y)
 {
     for($i=2010;$i<=2020;$i++){
         
     if($y == $i){
         
     $ceked = "selected=\"selected\"";
     }else{
         
         $ceked = "";
         }
     echo "<option $ceked>$i</option>";
     }
 
 }


function option_tahun_full_edit($y)
 {
     for($i=1950;$i<=2000;$i++){
         
     if($y == $i){
         
     $ceked = "selected=\"selected\"";
     }else{
         
         $ceked = "";
         }
     echo "<option $ceked>$i</option>";
     }
 
 }

/**
* -------------------------------------------------
* Fungsi untuk format tanggal semau kita
* 
* -------------------------------------------------
**/

function format_tanggal($tanggal=null, $tipe=null)
{

if(empty($tanggal) || $tanggal == null)
{
 $jadinya = "-";
}else
{
 $tgl = strtotime($tanggal);
 if(empty($tipe))
 {
   $jadinya = date("d-M-Y", $tgl); 
 }elseif($tipe == "gampang")
 {
   $jadinya = date("d-m-Y", $tgl);
 }elseif($tipe == "komplit")
 {
   $jadinya = date("d-F-Y", $tgl);
 }else
 {
   $jadinya = "error";
 }
}




return $jadinya;
}

/**
* -------------------------------------------------
* Fungsi untuk memunculkan terbilang
* 
* -------------------------------------------------
**/

function Terbilang($x)
{
$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
 if ($x < 12)
   $bilang =  " " . $abil[$x];
 elseif ($x < 20)
   $bilang =  $this->Terbilang($x - 10) . " belas";
 elseif ($x < 100)
   $bilang =  $this->Terbilang($x / 10) . " puluh" . $this->Terbilang($x % 10);
 elseif ($x < 200)
   $bilang =  " seratus" . $this->Terbilang($x - 100);
 elseif ($x < 1000)
   $bilang =  $this->Terbilang($x / 100) . " ratus" . $this->Terbilang($x % 100);
 elseif ($x < 2000)
   $bilang =  " seribu" . $this->Terbilang($x - 1000);
 elseif ($x < 1000000)
   $bilang =  $this->Terbilang($x / 1000) . " ribu" . $this->Terbilang($x % 1000);
 elseif ($x < 1000000000)
   $bilang =  $this->Terbilang($x / 1000000) . " juta" . $this->Terbilang($x % 1000000);
 
 return strtoupper($bilang);
}