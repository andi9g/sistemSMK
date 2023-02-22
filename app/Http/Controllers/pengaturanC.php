<?php

namespace App\Http\Controllers;

use App\Models\pengaturanM;
use Illuminate\Http\Request;

class pengaturanC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pengaturan = pengaturanM::first();

        return view('pages.pagesPengaturan', [
            'pengaturan' => $pengaturan,
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
            'keterlambatan'=>'required|numeric',
            'jammasuk'=>'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
            'numeric' => 'Masukan Angka!',
        ]);


        try{
            $keterlambatan = $request->keterlambatan;
            $jammasuk = $request->jammasuk;

            
            pengaturanM::truncate();

            $tambah = new pengaturanM;
            $tambah->keterlambatan = $keterlambatan;
            $tambah->jammasuk = $jammasuk;
            $tambah->save();

            if ($tambah) {
                return redirect()->back()->with('success', 'success')->withInput();
            }

        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pengaturanM  $pengaturanM
     * @return \Illuminate\Http\Response
     */
    public function show(pengaturanM $pengaturanM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pengaturanM  $pengaturanM
     * @return \Illuminate\Http\Response
     */
    public function edit(pengaturanM $pengaturanM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pengaturanM  $pengaturanM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pengaturanM $pengaturanM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pengaturanM  $pengaturanM
     * @return \Illuminate\Http\Response
     */
    public function destroy(pengaturanM $pengaturanM)
    {
        //
    }
}
