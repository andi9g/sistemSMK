<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Ktm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('alat', function (Blueprint $table) {
            $table->bigIncrements('idalat');
            $table->String('namaalat')->unique();
            $table->String('perangkat');
            $table->text('key_post')->unique();
            $table->text('computerId')->unique();
            $table->timestamps();
        });
        
        Schema::create('card', function (Blueprint $table) {
            $table->char('uid', 10)->primary();
            $table->char('nis')->unique();
            $table->enum('ket', ['siswa','master']);
            $table->timestamps();
        });

        Schema::create('absen', function (Blueprint $table) {
            $table->bigIncrements('idabsen');
            $table->Integer('nis');
            $table->String('tanggal');
            $table->String('jammasuk')->nullable();
            $table->String('jamkeluar')->nullable();
            $table->enum('ket', ['H', 'I', 'S', 'A']);
            $table->timestamps();
        });

        Schema::create('ket', function (Blueprint $table) {
            $table->bigIncrements('idket');
            $table->enum('ket', ['H', 'I', 'S', 'A'])->unique();
            $table->String('namaket');
            $table->timestamps();
        });

        $ket = ["H_hadir", "I_izin", "S_sakit", "A_alfa"];
        foreach ($ket as $k) {
            $ex = explode("_", $k);
            DB::table('ket')->insert([
                'ket' => $ex[0],
                'namaket' => $ex[1],
            ]);
        }

        Schema::create('open', function (Blueprint $table) {
            $table->bigIncrements('idopen');
            $table->boolean('open')->default(true);
            $table->timestamps();
        });

        Schema::create('pengaturan', function (Blueprint $table) {
            $table->bigIncrements('idpengaturan');
            $table->Integer('keterlambatan');
            $table->time('jammasuk');
            $table->timestamps();
        });

        DB::table('open')->insert([
            'open' => true,
        ]);

        
        Schema::create('siswa', function (Blueprint $table) {
            $table->Integer('nis')->primary();
            $table->String('namasiswa');
            $table->enum('jk', ['L','P']);
            $table->year('tahunmasuk');
            $table->Integer('idjurusan');
            $table->Integer('idkelas');
            $table->timestamps();
        });

        
        Schema::create('admin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('username')->unique();
            $table->String('nama');
            $table->String('password');
            $table->String('key_post');
            $table->String('computerId')->unique();
            $table->String('perangkat')->unique();
            $table->timestamps();
        });

        Schema::create('superadmin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('username')->unique();
            $table->String('nama');
            $table->String('password');
            $table->timestamps();
        });


        DB::table('superadmin')->insert([
            'username' => 'superadmin',
            'nama' => 'superadmin',
            'password' => Hash::make('superadmin'.date('Y')),
        ]);

        Schema::create('jurusan', function (Blueprint $table) {
            $table->bigIncrements('idjurusan');
            $table->String('namajurusan')->unique();
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->bigIncrements('idkelas');
            $table->String('namakelas')->unique();
            $table->timestamps();
        });

        $jurusan = ['TKJ', 'ATPH', 'DPIB', 'LDP'];
        $kelas = ['X', 'XI', 'XII'];
        foreach ($jurusan as $j) {
            DB::table('jurusan')->insert([
                'namajurusan' => $j,
            ]);
        }
        foreach ($kelas as $k) {
            DB::table('kelas')->insert([
                'namakelas' => $k,
            ]);
        }


        
        

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ruangan');
        Schema::drop('ruangan_master');
        Schema::drop('card');
        Schema::drop('kelas');
        Schema::drop('absen');
        Schema::drop('absendetail');
        Schema::drop('mahasiswa');
        Schema::drop('prodi');
        Schema::drop('semester');
        Schema::drop('hari');
        Schema::drop('admin');
        Schema::drop('superadmin');
        Schema::drop('matkul');
        Schema::drop('tahun_ajaran');
        Schema::drop('kelas_mhs');

        
    }
}
