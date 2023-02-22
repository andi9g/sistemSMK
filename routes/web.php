<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//scan kartu
Route::get('scan', 'scanC@scan');
Route::get('adminScan', 'scanC@adminscan');

//post kartu
Route::get('/', 'indexController@root');
// Route::get('/', 'indexController@index');

//login and logout
Route::get('logout', 'aksesC@logout');
Route::get('login', 'aksesC@login');
Route::post('login', 'aksesC@proses')->name('login.proses');

//API
Route::get('api/krs_matkul/{id}','APIC@krs_matkul');
Route::get('api/jadwal/{tahun}','APIC@APIMatkul');

Route::get('token_csrf', 'aksesC@csrf');

Route::middleware(['Gerbang'])->group(function () {
    //tambah admin
    Route::get('admin', 'indexController@admin')->middleware('GerbangSuperadmin');
    Route::post('admin/tambah', 'adminC@store')->name('tambah.admin')->middleware('GerbangSuperadmin');
    Route::post('admin/reset/{id}', 'adminC@reset')->name('reset.admin')->middleware('GerbangSuperadmin');
    Route::put('admin/edit/{id}', 'adminC@update')->name('update.admin')->middleware('GerbangSuperadmin');
    Route::delete('admin/delete/{id}', 'adminC@destroy')->name('delete.admin')->middleware('GerbangSuperadmin');
    
    //siswa
    Route::resource('siswa', 'siswaC');

    // Route::get('mahasiswa/keseluruhan', 'indexController@seluruhMahasiswa');
    Route::get('siswacard/card/', 'cardC@cardSiswa');
    Route::patch('siswacard/card/proses', 'cardC@proses')->name('tambah.card');
    Route::patch('siswacard/cardMaster/proses', 'cardC@proses2')->name('tambah.cardMaster');
    Route::post('siswacard/card/reset/{nim}', 'cardC@reset')->name('reset.card');


    //cekcard
    Route::get('card/cek/', 'cardC@cardCek');
    Route::post('card/cek/', 'cardC@cardData')->name('cek.card');

    //kelolah Alat
    Route::get('/alat', 'alatC@alat');
    Route::post('/alat/tambah', 'alatC@store')->name('tambah.alat');
    Route::post('/alat/ubah/{id}', 'alatC@reset')->name('ubah.ip');
    Route::delete('/alat/delete/{id}', 'alatC@destroy')->name('hapus.alat');


    Route::get('absen', 'absenC@index');
    Route::post('absen', 'absenC@keterangan')->name('tambah.keterangan');
    Route::put('absen/ubah/{idabsen}', 'absenC@ubahketerangan')->name('ubah.keterangan');
    Route::delete('absen/hapus/{idabsen}', 'absenC@hapusketerangan')->name('hapus.keterangan');
    Route::post('ubahjam', 'absenC@ubahjam')->name('ubah.jam');


    Route::get('pengaturan', 'pengaturanC@index');
    Route::post('pengaturan', 'pengaturanC@store')->name('pengaturan.store');
    
    //dashboard
    // Route::get('/welcome', 'indexController@welcome');
    
    //ganti password
    Route::put('gantipassword', 'aksesC@ubahPassword')->name('ubah.password');
    
    
    
    
    
    
    
    
    
    //laporan
    Route::get('laporan', 'cetakC@index');
    Route::get('laporan/cetak', 'cetakC@cetak')->name('cetak.laporan');
    
    
    
    
    
    
    
    
    
    //krs mahasiswa
    Route::get('mahasiswa/krs', 'krsC@index');
    Route::post('mahasiswa/krs/import/{id}', 'krsC@import')->name('import.krsmatkul');
    // Route::get('mahasiswa/krs/detail/{id_krs}', 'krsC@detail');
    
    
    
    
    
    
    
    
    //superadmin
    Route::get('superadmin', 'superadminC@index')->middleware('GerbangSuperadmin');
    Route::post('superadmin/tambah', 'superadminC@store')->name('tambah.superadmin')->middleware('GerbangSuperadmin');
    Route::post('superadmin/reset/{id}', 'superadminC@reset')->name('reset.superadmin')->middleware('GerbangSuperadmin');
    Route::patch('superadmin/update/{id}', 'superadminC@update')->name('update.superadmin')->middleware('GerbangSuperadmin');
    Route::delete('superadmin/hapus/{id}', 'superadminC@destroy')->name('hapus.superadmin')->middleware('GerbangSuperadmin');
    
    //faker
    // Route::get('faker', 'indexController@faker')->middleware('GerbangSuperadmin');
});
