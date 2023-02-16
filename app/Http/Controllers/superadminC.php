<?php

namespace App\Http\Controllers;

use App\Models\superadmin;
use Hash;
use Illuminate\Http\Request;

class superadminC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $superadmin = superadmin::get();

        return view('pages.pagesSuperadmin', [
            'superadmin' => $superadmin,
        ]);


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
            'username' => 'required|unique:superadmin,username',
            'nama' => 'required',
            'password' => 'required',
        ]);


        try {
            $username = $request->username;
            $nama = $request->nama;
            $password = Hash::make($request->password);


            $tambah = new superadmin;
            $tambah->username = $username;
            $tambah->nama = $nama;
            $tambah->password = $password;
            $tambah->save();

            if($tambah) {
                return redirect('superadmin')->with('toast_success', 'Data berhasil ditambahkan');
            }

        } catch (\Throwable $th) {
            return redirect('superadmin')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function reset($id, Request $request)
    {
        try{
            $password = Hash::make("superadmin".date('Y'));

            $reset = superadmin::where('id', $id)->update([
                'password' => $password,
            ]);

            if($reset){
                return redirect('superadmin')->with('toast_success', 'Success');
            }

        
        }catch(\Throwable $th){
        return redirect('superadmin')->with('toast_error', 'Terjadi kesalahan');
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\superadmin  $superadmin
     * @return \Illuminate\Http\Response
     */
    public function show(superadmin $superadmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\superadmin  $superadmin
     * @return \Illuminate\Http\Response
     */
    public function edit(superadmin $superadmin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\superadmin  $superadmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, superadmin $superadmin, $id)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        try{
            $nama = $request->nama;

            $update = superadmin::where('id', $id)->update([
                'nama' => $nama,
            ]);

            if($update){
                return redirect('superadmin')->with('toast_success', 'Success');
            }

        }catch(\Throwable $th){
            return redirect('superadmin')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\superadmin  $superadmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(superadmin $superadmin, $id)
    {
        try{
            $hapus = superadmin::destroy($id);
            if($hapus){
                return redirect('superadmin')->with('toast_success', 'Success');
            }   
        }catch(\Throwable $th){
            return redirect('superadmin')->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
