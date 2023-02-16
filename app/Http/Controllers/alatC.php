<?php

namespace App\Http\Controllers;

use App\Models\alatM;
use Hash;
use Illuminate\Http\Request;

class alatC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function alat(Request $request)
    {
        $post = empty($request->keyword)?"":$request->keyword;

        $alat = alatM::orderBy('namaalat', 'asc')->get();

        return view('pages.pagesAlat', [
            'alat' => $alat,
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
            'alat' => 'required|unique:alat,namaalat',
        ]);

        // try {
            $ruangan = $request->alat;

            $key_post = str_replace("/", "", Hash::make('ruangan')).str_replace("/", "", Hash::make($ruangan));
            $key_post = str_replace("$","", $key_post);
            $key_post = str_replace(".","", $key_post);
            $key_post = str_replace(",","", $key_post);

            $computerId = uniqid();
            $idruangan_master = uniqid().strtotime(date("Y-m-d H:i:s"));
            // dd(public_path().'/ruangan/');
            
            $tambah = new alatM;
            $tambah->namaalat = $ruangan;
            $tambah->perangkat = "absen";
            $tambah->key_post = $key_post;
            $tambah->computerId = $computerId;
            $tambah->save();

            if($tambah) {
                return redirect('/alat')->with('toast_success', 'Alat berhasil ditambahkan');
            }else {
                return redirect('/alat')->with('toast_error', 'Alat gagal ditambahkan');
            }

        // } catch (\Throwable $th) {
        //     return redirect('/alat')->with('toast_error', '(Error) - pastikan perangkat tidak terdaftar sebelumnya dan no ruangan tidak duplikat!')->withInput();
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\alatM  $alatM
     * @return \Illuminate\Http\Response
     */
    public function show(alatM $alatM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\alatM  $alatM
     * @return \Illuminate\Http\Response
     */
    public function edit(alatM $alatM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\alatM  $alatM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, alatM $alatM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\alatM  $alatM
     * @return \Illuminate\Http\Response
     */
    public function destroy(alatM $alatM, $idalat)
    {
        try{
            $destroy = alatM::where('idalat', $idalat)->delete();
            if($destroy) {
                return redirect('alat')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('alat')->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
