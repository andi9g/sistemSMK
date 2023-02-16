<?php

namespace App\Http\Controllers;

use App\Models\siswaM;
use App\Models\jurusanM;
use App\Models\kelasM;
use Illuminate\Http\Request;

class siswaC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $jurusan = empty($request->jurusan)?"":$request->jurusan; 
        $kelas = empty($request->kelas)?"":$request->kelas; 
        $tahun = empty($request->tahunmasuk)?"":$request->tahunmasuk; 
        $keyword = empty($request->keyword)?"":$request->keyword; 

        $kelas_ = kelasM::select('idkelas',"namakelas")->orderBy('idkelas', 'asc')->get();
        $jurusan_ = jurusanM::select('idjurusan',"namajurusan")->orderBy('idjurusan', 'asc')->get();
        $tahun_ = siswaM::select('tahunmasuk')->orderBy('tahunmasuk','asc')->groupBy('tahunmasuk')->get();

        $tampil = siswaM::join('kelas','kelas.idkelas','=','siswa.idkelas')
        ->join('jurusan', 'jurusan.idjurusan','=','siswa.idjurusan')
        ->select('siswa.*','kelas.namakelas','kelas.idkelas','jurusan.namajurusan','jurusan.idjurusan')
        ->where('siswa.nis','like', "$keyword%")
        ->where('jurusan.idjurusan','like', "$jurusan%")
        ->where('kelas.idkelas','like', "$kelas%")
        ->where('siswa.tahunmasuk','like', "$tahun%")
        ->paginate(15);

        $tampil->appends($request->only('keyword', 'tahun', 'jurusan', 'kelas', 'limit'));
        
        return view('pages.pagesSiswa',[
            'Dtahun' => $tahun_,
            'tahun' => $tahun,
            'kelas' => $kelas_,
            'jurusan' => $jurusan_,
            'tampil' => $tampil,
            'keyword' => $keyword,
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
            'nis' => 'required|numeric|unique:siswa,nis',
            'namasiswa' => 'required',
            'jk' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'tahun' => 'required|numeric',
        ]);

        try{
            $nis = $request->nis;
            $namasiswa = $request->namasiswa;
            $jk = $request->jk;
            $jurusan = $request->jurusan;
            $kelas = $request->kelas;
            $tahun = $request->tahun;


            $tambah = new siswaM;
            $tambah->nis = $nis;
            $tambah->namasiswa = $namasiswa;
            $tambah->jk = $jk;
            $tambah->idjurusan = $jurusan;
            $tambah->idkelas = $kelas;
            $tambah->tahunmasuk = $tahun;
            $tambah->save();

            if ($tambah) {
                return redirect()->back()->with('toast_success', 'success')->withInput();
            }

        }catch(\Throwable $th){
            return redirect('siswa')->with('toast_error', 'Terjadi kesalahan');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\siswaM  $siswaM
     * @return \Illuminate\Http\Response
     */
    public function show(siswaM $siswaM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\siswaM  $siswaM
     * @return \Illuminate\Http\Response
     */
    public function edit(siswaM $siswaM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\siswaM  $siswaM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, siswaM $siswaM, $nis)
    {
        $request->validate([
            'namasiswa' => 'required',
            'jk' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'tahun' => 'required',
        ]);
        
        
        try{
            $namasiswa = $request->namasiswa;
            $jk = $request->jk;
            $jurusan = $request->jurusan;
            $kelas = $request->kelas;
            $tahun = $request->tahun;
        
            $update = siswaM::where('nis', $nis)->update([
                'namasiswa' => $namasiswa,
                'jk' => $jk,
                'idjurusan' => $jurusan,
                'idkelas' => $kelas,
                'tahunmasuk' => $tahun,
            ]);
            if($update) {
                return redirect('siswa')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('siswa')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\siswaM  $siswaM
     * @return \Illuminate\Http\Response
     */
    public function destroy(siswaM $siswaM, $nis)
    {
        try{
            $destroy = siswaM::where('nis', $nis)->delete();
            if($destroy) {
                return redirect('siswa')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('siswa')->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
