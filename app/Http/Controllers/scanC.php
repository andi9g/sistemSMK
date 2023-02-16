<?php

namespace App\Http\Controllers;

use App\Models\master;
use App\Models\adminM;
use App\Models\alatM;
use App\Models\siswaM;
use App\Models\absenM;
use App\Models\openM;
use Illuminate\Http\Request;
use Hash;

class scanC extends Controller
{

    public function adminscan($uid, Request $request)
    {
        $uid = str_replace(" ","",$uid);
        $ex = explode("___", $uid);
        $UID = $ex[0];
        $key_post = $ex[1];
        $computerId = $ex[2];
        $ruangan = $ex[3];

        $cek = adminM::where('computerId', $computerId)->where('key_post', $key_post)->count();
       
        if($cek === 1) {
            $Write= "<?php
                        session_start();
                        if($"."_SESSION['perangkat'] === '$ruangan' && $"."_SESSION['computerId']==='$computerId'){
                                $"."UIDresult= '$UID';
                                echo $"."UIDresult; 
                            }
                        ?>
                    ";
            $url = public_path().'/masterUID/'.$ruangan.'.php';
            file_put_contents($url,$Write);
            echo "berhasil";
        }else{
            echo "gagal";
        }
    }

    public function scan($uid, Request $request) 
    {
        $uid = str_replace(" ","",$uid);
        $ex = explode("___", $uid);
        $UID = $ex[0];
        $key_post = $ex[1];
        $computerId = $ex[2];
        $perangkat = $ex[3];

        $cek = alatM::where('computerId', $computerId)
        ->where('key_post', $key_post)
        ->where('perangkat', $perangkat)
        ->count();
        
        if($cek === 1) {
            $open = openM::first();
            $tanggal = date('Y-m-d');
            $jam = date('H:i');

            $ambil = siswaM::join('card', 'card.nis', 'siswa.nis')
            ->select('siswa.nis')
            ->where('card.uid', $UID);
            

            if ($ambil->count() == 0) {
                $lanjut = "merah";
            }elseif($ambil->count() == 1) {
                $nis = $ambil->first()->nis;
                
                if($open->open == true) {
                    $cek = absenM::where('nis', $nis)->where('tanggal', $tanggal)->count();
                    if($cek == 1) {
                        $lanjut = "kuning";
                    }else if($cek == 0) {
                        $absen = new absenM;
                        $absen->nis = $nis;
                        $absen->tanggal = $tanggal;
                        $absen->jammasuk = $jam;
                        $absen->ket = "H";
                        $absen->save();
                        $lanjut = "hijau";
                    }
                }elseif($open->open == false){
                    $cek = absenM::where('nis', $nis)->where('tanggal', $tanggal);
                    if($cek->count() == 1) {
                        $jamkeluar = $cek->first()->jamkeluar;
                        if($jamkeluar == null) {
                            $update = absenM::where('nis', $nis)->where('tanggal', $tanggal)
                            ->update([
                                'jamkeluar' => $jam,
                            ]);
                            $lanjut = "hijau";
                        }else {
                            $lanjut = "kuning";
                        }
                        
                    }else {
                        $absen = new absenM;
                        $absen->nis = $nis;
                        $absen->tanggal = $tanggal;
                        $absen->jamkeluar = $jam;
                        $absen->ket = "A";
                        $absen->save();
                        $lanjut = "hijau";
                    }
                }

            }
            
            echo $lanjut;
            
        }else {
            echo "merah";
        }

        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
