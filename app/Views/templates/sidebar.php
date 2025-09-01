<?php
$session = \Config\Services::session();
$context = $ctx ?? 'dashboard';
switch ($context) {
   case 'absen-siswa':
   case 'siswa':
   case 'kelas':
      case 'mutasi' :
         case 'berikelas' :
            case 'mutasi-edit' :
      $sidebarColor = 'purple';
      break;
   case 'absen-guru':
   case 'guru':
      $sidebarColor = 'green';
      break;

   case 'qr':
      $sidebarColor = 'danger';
      break;

   default:
      $sidebarColor = 'azure';
      break;
}
?>
<div class="sidebar" data-color="<?= $sidebarColor; ?>" data-background-color="black" data-image="<?= base_url('public/assets/img/sidebar/sidebar-1.jpg'); ?>">
   <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
   <div class="logo">
      <a class="simple-text logo-normal">
         <b>Absensi<br>Th. Ajar <?=$session->get('thajar');?></b>
      </a>
   </div>
   <div class="sidebar-wrapper">
      <ul class="nav">
         <li class="nav-item <?= $context == 'dashboard' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
               <i class="material-icons">dashboard</i>
               <p>Dashboard</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'absen-siswa' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/absen-siswa'); ?>">
               <i class="material-icons">checklist</i>
               <p>Absensi Siswa</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'absen-guru' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/absen-guru'); ?>">
               <i class="material-icons">checklist</i>
               <p>Absensi Guru</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'siswa' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/siswa'); ?>">
               <i class="material-icons">person</i>
               <p>Data Siswa</p>
            </a>
         </li>
         
         <li class="nav-item <?= $context == 'berikelas' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/berikelas'); ?>">
               <i class="material-icons">settings_backup_restore</i>
               <p>Beri Kelas</p>
            </a>
         </li>


         <li class="nav-item <?= $context == 'mutasi' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/mutasi'); ?>">
               <i class="material-icons">upload</i>
               <p>Mutasi Siswa</p>
            </a>
         </li>


         <li class="nav-item <?= $context == 'mutasi-edit' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/mutasi-edit'); ?>">
               <i class="material-icons">fact_check</i>
               <p>Edit Mutasi</p>
            </a>
         </li>


         <li class="nav-item <?= $context == 'guru' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/guru'); ?>">
               <i class="material-icons">person_4</i>
               <p>Data Guru</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'kelas' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/kelas'); ?>">
               <i class="material-icons">school</i>
               <p>Data Kelas & Jurusan</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'qr' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/generate'); ?>">
               <i class="material-icons">qr_code</i>
               <p>Generate QR Code</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'laporan' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/laporan'); ?>">
               <i class="material-icons">print</i>
               <p>Generate Laporan</p>
            </a>
         </li>
         <?php if (user()->toArray()['is_superadmin'] ?? '0' == '1') : ?>
            <li class="nav-item <?= $context == 'petugas' ? 'active' : ''; ?>">
               <a class="nav-link" href="<?= base_url('admin/petugas'); ?>">
                  <i class="material-icons">computer</i>
                  <p>Data Petugas</p>
               </a>
            </li>

            <li class="nav-item <?= $context == 'pesan' ? 'active' : ''; ?>">
               <a class="nav-link" href="<?= base_url('admin/siswa/pesan'); ?>">
                  <i class="material-icons">mail</i>
                  <p>Pesan Notifikasi</p>
               </a>
            </li>

            <li class="nav-item <?= $context == 'broadcast' ? 'active' : ''; ?>">
               <a class="nav-link" href="<?= base_url('admin/broadcast'); ?>">
                  <i class="material-icons">send</i>
                  <p>Pesan Broadcast</p>
               </a>
            </li>


            <li class="nav-item <?= $context == 'pengaturan' ? 'active' : ''; ?>">
               <a class="nav-link" href="<?= base_url('admin/pengaturan'); ?>">
                  <i class="material-icons">settings</i>
                  <p>Pengaturan</p>
               </a>
            </li>

            <li class="nav-item <?= $context == 'pengaturanpesan' ? 'active' : ''; ?>">
               <a class="nav-link" href="<?= base_url('admin/pengaturanpesan'); ?>">
                  <i class="material-icons">settings_applications</i>
                  <p>Pengaturan Pesan</p>
               </a>
            </li>
         <?php endif; ?>
         <!-- <li class="nav-item active-pro mb-3">
            <a class="nav-link" href="./upgrade.html">
               <i class="material-icons">unarchive</i>
               <p>Bottom sidebar</p>
            </a>
         </li> -->
      </ul>
   </div>
</div>