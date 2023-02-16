<?php

namespace App\Http\Controllers;

use App\Models\adminM;
use Illuminate\Http\Request;
use Hash;

class adminC extends Controller
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
            'username' => 'required|unique:admin,username',
            'nama' => 'required',
            'password' => 'required',
        ], [
            'required' => 'Tidak boleh kosong',
            'unique' => 'username telah terdaftar',
        ]);

        // try {
            $username = $request->username;
            $nama = $request->nama;
            $password = Hash::make($request->password);

            $perangkat = "";
            $cek = adminM::get();

            $nilai = 1;
            if(count($cek) > 0) {
                $total = count($cek);
                for($i = 1; $i <= $total; $i++) {
                    
                    $cek2 = adminM::where('perangkat', "perangkat".$i)->count();
                    if($cek2==0){
                        if(empty($perangkat)){
                            $perangkat = "perangkat".$i;
                        }
                    }
                }

                if(empty($perangkat)){
                    $perangkat = "perangkat".((int) $i + $nilai);
                }
            }else {
                $perangkat = "perangkat".$nilai;
            }

            $no_ruangan = $perangkat;
            $key_post = str_replace("/", "", Hash::make('ruangan')).str_replace("/", "", Hash::make($no_ruangan));
            $key_post = str_replace("$","", $key_post);
            $key_post = str_replace(".","", $key_post);
            $key_post = str_replace(",","", $key_post);
            $computerId = uniqid();


            $tambah = new adminM;
            $tambah->username = $username;
            $tambah->nama = $nama;
            $tambah->password = $password;
            $tambah->key_post = $key_post;
            $tambah->computerId = $computerId;
            $tambah->perangkat = $perangkat;
            $tambah->save();

            if($tambah) {
                $location = public_path().'/masterUID/'.$perangkat.".php";

                if(file_exists($location)){
                    unlink($location);
                }

                $myfile2 = fopen($location, "w+") or die("Unable to open file!");

                $txt2 = "
                        ";

                
                fwrite($myfile2, $txt2);
                fclose($myfile2);
                chmod($location, 0777);


                return redirect('/admin')->with('toast_success', 'Master Admin berhasil ditambahkan');
            }else {
                return redirect('admin')->with('toast_error', 'terjadi kesalahan');
            }
        // } catch (\Throwable $th) {
        //     return redirect('admin')->with('toast_error', 'terjadi kesalahan');
        // }
    }


    public function reset(Request $request, $id)
    {
    
        try {
            $password = Hash::make("admin".date('Y'));
            $default = "admin".date('Y');

            $update = adminM::where('id', $id)->update([
                'password' => $password
            ]);

            if ($update) {
                return redirect('/admin')->with('toast_success', 'Password telah direset menjadi : '.$default);
            }


        } catch (\Throwable $th) {
            return redirect('/admin')->with('toast_error', 'Terjadi Kesalahan ');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function show(adminM $adminM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function edit(adminM $adminM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, adminM $adminM, $id)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        try {
            $nama = $request->nama;

            $update = adminM::where('id', $id)->update([
                'nama' => $nama,
            ]);

            if ($update) {
                return redirect('/admin')->with('toast_success', 'Data berhasil di update');
            }
        } catch (\Throwable $th) {
            return redirect('/admin')->with('toast_error', 'Terjadi Kesalahan ');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function destroy(adminM $adminM, $id)
    {
        try {
            $delete = adminM::destroy($id);
            if($delete) {
                return redirect('/admin')->with('toast_success', 'Success ');
            }

        } catch (\Throwable $th) {
            return redirect('/admin')->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
