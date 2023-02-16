<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class siswa extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kel = ['L', "P"];
        for ($i=0; $i < 220; $i++) { 
            DB::table('siswa')->insert([
                'nis' => $i,
                'namasiswa' => 'namasiswa'.$i,
                'jk' => $kel[rand(0,1)],
                'tahunmasuk' => 2020,
                'idjurusan' => rand(1,4),
                'idkelas' => rand(1,3),
            ]);
        }
    }
}
