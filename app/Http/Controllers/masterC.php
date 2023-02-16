<?php

namespace App\Http\Controllers;

use App\Models\master;
use App\Models\kontrakM;
use App\Models\jadwal;
use Illuminate\Http\Request;
use Hash;

class masterC extends Controller
{
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
        $request->validate([
            'ruangan' => 'required|numeric',
        ]);

        try {
            $ruangan = $request->ruangan;

            $ambil = file_get_contents('https://api.ipify.org/?format=json');
            $ipJson = json_decode($ambil,TRUE);

            $ip = $ipJson["ip"];

            $key_post = str_replace("/", "", Hash::make('ruangan')).str_replace("/", "", Hash::make($ruangan));
            $key_post = str_replace("$","", $key_post);
            $key_post = str_replace(".","", $key_post);
            $key_post = str_replace(",","", $key_post);

            $computerId = str_replace("/","", $_SERVER['HTTP_USER_AGENT']);
            $computerId = str_replace(" ","", $computerId);
            $computerId = str_replace("$","", $computerId);
            $computerId = str_replace("%","", $computerId);
            $computerId = str_replace(".","", $computerId);
            $computerId = str_replace(",","", $computerId);
            $computerId = str_replace(";","", $computerId);
            $computerId = str_replace("(","", $computerId);
            $computerId = str_replace(")","", $computerId);
            $computerId = strtolower($computerId);
            
            $idruangan_master = uniqid().strtotime(date("Y-m-d H:i:s"));
            // dd(public_path().'/ruangan/');
            
            $tambah = new master;
            $tambah->idruangan_master = $idruangan_master;
            $tambah->idruangan = $ruangan;
            $tambah->ip = $ip;
            $tambah->key_post = $key_post;
            $tambah->computerId = $computerId;
            $tambah->save();

            if($tambah) {
                $location = public_path().'/ruangan/'.$idruangan_master;

                if(file_exists($location."Container.php")){
                    unlink($location."Container.php");
                }

                $myfile2 = fopen($location."Container.php", "w+") or die("Unable to open file!");

                $txt2 = "";
                
                fwrite($myfile2, $txt2);
                fclose($myfile2);
                chmod($location."Container.php", 0777);


                return redirect('/master')->with('toast_success', 'Master berhasil ditambahkan');
            }else {
                return redirect('/master')->with('toast_error', 'Master gagal ditambahkan');
            }

        } catch (\Throwable $th) {
            return redirect('/master')->with('toast_error', '(Error) - pastikan perangkat tidak terdaftar sebelumnya dan no ruangan tidak duplikat!')->withInput();
        }

    }


    public function reset(Request $request, $id)
    {
        try{
            $ambil = file_get_contents('https://api.ipify.org/?format=json');
            $ipJson = json_decode($ambil,TRUE);
            $ip = $ipJson["ip"];

            $update = master::where('idruangan_master', $id)->update([
                'ip' => $ip,
            ]);

            if($update) {
                return redirect()->back()->with('success', 'reset ip berhasil')->withInput();
            }
        
        }catch(\Throwable $th){
            return redirect('/master')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\master  $master
     * @return \Illuminate\Http\Response
     */
    public function show(master $master)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\master  $master
     * @return \Illuminate\Http\Response
     */
    public function edit(master $master)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\master  $master
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, master $master)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\master  $master
     * @return \Illuminate\Http\Response
     */
    public function destroy(master $master, $no_ruangan)
    {
        try {
            
            $delete = $master->where('idruangan_master', $no_ruangan)->delete();
            if ($delete) {
                return redirect('/master')->with('toast_success', 'Penghapusan Berhasil');
            }

        } catch (\Throwable $th) {
            return redirect('/master')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function destroyJadwal(jadwal $jadwal, $id)
    {
        try {
            
            $delete = $jadwal->where('id', $id)->delete();
            if ($delete) {
                return redirect()->back()->with('toast_success', 'Penghapusan Berhasil');
            }

        } catch (\Throwable $th) {
            return redirect('/jadwal')->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
