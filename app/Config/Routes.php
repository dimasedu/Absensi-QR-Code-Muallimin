<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

// Create a new instance of our RouteCollection class.
// $routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Scan
$routes->get('/', 'Scan::index');
// $routes->get('/daftar','Register::index');
// $routes->post('/simpandaftar','Register::simpan');



$routes->group('scan', function (RouteCollection $routes) {
   $routes->get('', 'Scan::index');
   $routes->get('masuk', 'Scan::index/Masuk');
   $routes->get('pulang', 'Scan::index/Pulang');

   $routes->get('devicemasuk', 'Scan::scan_device/Masuk');
   $routes->get('devicepulang', 'Scan::scan_device/Pulang');

   $routes->post('cek', 'Scan::cekKode');

   $routes->get('ceknomor/(:any)','Scan::format_nomor/$1');
   $routes->get('kirimwasender','Scan::teskirim');
});

// Admin
$routes->group('admin', function (RouteCollection $routes) {
   // Admin dashboard
   $routes->get('', 'Admin\Dashboard::index');
   $routes->get('dashboard', 'Admin\Dashboard::index');

   // data kelas & jurusan
   $routes->resource('kelas', ['controller' => 'Admin\KelasController']);
   $routes->resource('jurusan', ['controller' => 'Admin\JurusanController']);

   // admin lihat data siswa
   $routes->get('siswa', 'Admin\DataSiswa::index');
   $routes->post('siswa', 'Admin\DataSiswa::ambilDataSiswa');
   // admin tambah data siswa
   $routes->get('siswa/create', 'Admin\DataSiswa::formTambahSiswa');
   $routes->post('siswa/create', 'Admin\DataSiswa::saveSiswa');
   // admin edit data siswa
   $routes->get('siswa/edit/(:any)', 'Admin\DataSiswa::formEditSiswa/$1');
   $routes->post('siswa/edit', 'Admin\DataSiswa::updateSiswa');
   $routes->get('siswa/detail/(:any)', 'Admin\DataSiswa::show/$1');
   // admin hapus data siswa
   // $routes->delete('siswa/delete/(:any)', 'Admin\DataSiswa::delete/$1');
   $routes->add('siswa/delete/(:any)', 'Admin\DataSiswa::delete/$1');

   $routes->add('siswa/excel/(:any)/(:any)', 'Admin\DataSiswa::excel/$1/$2');
   // $routes->get('siswa/excel/', 'Admin\DataSiswa::excel');

   $routes->post('siswa/importsiswa', 'Admin\DataSiswa::importsiswa');

   $routes->get('siswa/cetakantrian', 'Admin\AntriancetakController::index');
   $routes->get('siswa/cetakproses', 'Admin\AntriancetakController::kartucetak');
   $routes->add('siswa/cetakhapus/(:num)', 'Admin\AntriancetakController::destroy/$1');
   $routes->add('siswa/cetakantrianadd/(:num)', 'Admin\AntriancetakController::store/$1');
   $routes->get('siswa/cetakclear', 'Admin\AntriancetakController::clear_antrian');

   $routes->post('kartupelajar', 'Admin\DataSiswa::kartucetak');
   $routes->post('kartuguru', 'Admin\DataGuru::kartucetak');

   $routes->get('siswa/pesan','Admin\DataSiswa::listpesan');
   $routes->add('siswa/kirimpesan/(:any)', 'Admin\DataSiswa::kirimpesan/$1');
   $routes->add('siswa/hapuspesan/(:num)', 'Admin\DataSiswa::hapus_pesan/$1');
   $routes->post('siswa/hapuspesanmulti', 'Admin\DataSiswa::hapus_pesan_multi');

   $routes->get('broadcast','Admin\BroadcastController::index');
   $routes->get('broadcast/add','Admin\BroadcastController::create');
   $routes->post('broadcast/save', 'Admin\BroadcastController::store');
   $routes->add('broadcast/delete/(:any)', 'Admin\BroadcastController::destroy/$1');
   $routes->add('broadcast/detail/(:any)', 'Admin\BroadcastController::detail/$1');
   $routes->add('broadcast/resend/(:num)','Admin\BroadcastController::resend/$1');

   $routes->get('absensi-izin','IjinController::index');
   $routes->get('absensi-izin/add','IjinController::create');
   $routes->post('absensi-ijin/save', 'IjinController::store');
   $routes->add('absensi-ijin/delete/(:any)', 'IjinController::destroy/$1');
   $routes->add('absensi-izin/edit/(:any)', 'IjinController::edit/$1');
   $routes->post('absensi-ijin/update', 'IjinController::update');

   $routes->add('absensi-ijin/tampilsiswa/(:any)', 'IjinController::tampil_siswa/$1');
   $routes->post('absensi-ijin/notif', 'IjinController::notif');


   // admin lihat data guru
   $routes->get('guru', 'Admin\DataGuru::index');
   $routes->post('guru', 'Admin\DataGuru::ambilDataGuru');
   // admin tambah data guru
   $routes->get('guru/create', 'Admin\DataGuru::formTambahGuru');
   $routes->post('guru/create', 'Admin\DataGuru::saveGuru');
   // admin edit data guru
   $routes->get('guru/edit/(:any)', 'Admin\DataGuru::formEditGuru/$1');
   $routes->post('guru/edit', 'Admin\DataGuru::updateGuru');

   $routes->get('guru/detail/(:any)', 'Admin\DataGuru::show/$1');
   // admin hapus data guru
   $routes->delete('guru/delete/(:any)', 'Admin\DataGuru::delete/$1');

   $routes->get('guru/excel', 'Admin\DataGuru::excel');
   $routes->post('guru/importproses', 'Admin\DataGuru::importproses');


   // admin lihat data absen siswa
   $routes->get('absen-siswa', 'Admin\DataAbsenSiswa::index');
   $routes->post('absen-siswa', 'Admin\DataAbsenSiswa::ambilDataSiswa'); // ambil siswa berdasarkan kelas dan tanggal
   $routes->post('absen-siswa/kehadiran', 'Admin\DataAbsenSiswa::ambilKehadiran'); // ambil kehadiran siswa
   $routes->post('absen-siswa/edit', 'Admin\DataAbsenSiswa::ubahKehadiran'); // ubah kehadiran siswa
   $routes->post('absen-siswa-lapharian', 'Admin\DataAbsenSiswa::laporan_harian',['as'=>'absen-siswa-lapharian']);
   $routes->post('absen-siswa-lapbulanan', 'Admin\DataAbsenSiswa::laporan_bulanan',['as'=>'absen-siswa-lapbulanan']);
   $routes->post('absen-siswa-lapijin', 'Admin\DataAbsenSiswa::laporan_ijin'); 

   // admin lihat data absen guru
   $routes->get('absen-guru', 'Admin\DataAbsenGuru::index');
   $routes->post('absen-guru', 'Admin\DataAbsenGuru::ambilDataGuru'); // ambil guru berdasarkan tanggal
   $routes->post('absen-guru/kehadiran', 'Admin\DataAbsenGuru::ambilKehadiran'); // ambil kehadiran guru
   $routes->post('absen-guru/edit', 'Admin\DataAbsenGuru::ubahKehadiran'); // ubah kehadiran guru

   $routes->get('absensi-siswa-reset', 'Admin\DataAbsenSiswa::resetabsensi');
   $routes->get('absensi-guru-reset', 'Admin\DataAbsenGuru::resetabsensi');

   // admin generate QR
   $routes->get('generate', 'Admin\GenerateQR::index');
   $routes->post('generate/siswa-by-kelas', 'Admin\GenerateQR::getSiswaByKelas'); // ambil siswa berdasarkan kelas

   // Generate QR
   $routes->post('generate/siswa', 'Admin\QRGenerator::generateQrSiswa');
   $routes->post('generate/guru', 'Admin\QRGenerator::generateQrGuru');

   // Download QR
   $routes->get('qr/siswa/download', 'Admin\QRGenerator::downloadAllQrSiswa');
   $routes->get('qr/siswa/(:any)/download', 'Admin\QRGenerator::downloadQrSiswa/$1');
   $routes->get('qr/guru/download', 'Admin\QRGenerator::downloadAllQrGuru');
   $routes->get('qr/guru/(:any)/download', 'Admin\QRGenerator::downloadQrGuru/$1');

   // admin buat laporan
   $routes->get('laporan', 'Admin\GenerateLaporan::index');
   $routes->post('laporan/siswa', 'Admin\GenerateLaporan::generateLaporanSiswa');
   $routes->post('laporan/guru', 'Admin\GenerateLaporan::generateLaporanGuru');

   // superadmin lihat data petugas
   $routes->get('petugas', 'Admin\DataPetugas::index');
   $routes->post('petugas', 'Admin\DataPetugas::ambilDataPetugas');
   // superadmin tambah data petugas
   $routes->get('petugas/register', 'Admin\DataPetugas::registerPetugas',['as'=>'petugas/register']);
   $routes->post('petugas/simpan', 'Admin\DataPetugas::store',['as'=>'petugas/simpan']);
   // superadmin edit data petugas
   $routes->get('petugas/edit/(:any)', 'Admin\DataPetugas::formEditPetugas/$1');
   $routes->post('petugas/edit', 'Admin\DataPetugas::updatePetugas');
   // superadmin hapus data petugas
   $routes->delete('petugas/delete/(:any)', 'Admin\DataPetugas::delete/$1');

   $routes->get('pengaturan', 'PengaturanController::index');
   $routes->post('simpanpengaturan', 'PengaturanController::update');

   $routes->get('pengaturanpesan', 'PengaturanController::pesan');
   $routes->post('simpanpengaturanpesan', 'PengaturanController::updatepesan');

   $routes->get('berikelas', 'Admin\SiswatransController::index');
   $routes->post('berikelas-proses', 'Admin\SiswatransController::berikelas');

   $routes->get('naik-kelas', 'Admin\SiswatransController::naik_kelas');
   $routes->post('naik-kelas-proses', 'Admin\SiswatransController::naik_kelas_proses');
      $routes->post('absen-siswa', 'Admin\DataAbsenSiswa::ambilDataSiswa'); 

   $routes->get('mutasi', 'Admin\SiswatransController::mutasi');
   $routes->post('mutasi-proses', 'Admin\SiswatransController::mutasi_proses');
   $routes->post('mutasi-data', 'Admin\SiswatransController::mutasi_data'); 

   $routes->get('mutasi-edit', 'Admin\SiswatransController::mutasi_edit');
   $routes->add('mutasi-edit-ubah/(:num)', 'Admin\SiswatransController::mutasi_edit_ubah/$1');
   $routes->post('mutasi-edit-proses', 'Admin\SiswatransController::mutasi_edit_simpan');
   $routes->get('mutasi-edit-delete/(:num)', 'Admin\SiswatransController::mutasi_edit_delete/$1');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
   require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
