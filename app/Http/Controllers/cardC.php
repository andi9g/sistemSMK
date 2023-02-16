<?php

namespace App\Http\Controllers;

use App\Models\siswaM;
use App\Models\jurusanM;
use App\Models\kelasM;
use App\Models\cardM;
use Illuminate\Http\Request;

class cardC extends Controller
{

    public function cardSiswa(Request $request)
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
        ->where(function ($query) use ($keyword) {
            $query->where('siswa.nis','like', "$keyword%")
                  ->orWhere('siswa.namasiswa','like', "%$keyword%");
        })
        ->where('jurusan.idjurusan','like', "$jurusan%")
        ->where('kelas.idkelas','like', "$kelas%")
        ->where('siswa.tahunmasuk','like', "$tahun%")
        ->paginate(15);

        $tampil->appends($request->only('keyword', 'tahun', 'jurusan', 'kelas', 'limit'));

        session_start();
        $Write= "";
        $url = public_path().'/masterUID/'.$_SESSION['perangkat'].'.php';
        file_put_contents($url,$Write);


        return view('pages.pagesSiswaCard', [
            'Dtahun' => $tahun_,
            'tahun' => $tahun,
            'kelas' => $kelas_,
            'jurusan' => $jurusan_,
            'siswa' => $tampil,
            'keyword' => $keyword,
        ]);
    }

    public function proses(Request $request)
    {           

        $request->validate([
            'uid' => 'required',
            'nis' => 'required|numeric',
        ]);


        try {
            $uid = $request->uid;
            $nis = $request->nis;

            $mhs = siswaM::where('nis', $nis)->count();

            $cekCard = cardM::join('siswa','siswa.nis','=', 'card.nis')
                        ->select('card.nis','siswa.namasiswa','siswa.tahunmasuk')
                        ->where('card.uid', $uid);

            if($cekCard->count() == 1) {
                $ambil = $cekCard->first();
                return redirect()->back()->with('warning', 'Kartu telah terdaftar sebagai '.$ambil->nis." - ".$ambil->namasiswa." | Angkatan : ".$ambil->tahunmasuk)->withInput();
            }

            if($mhs === 1) {
                $tambah = new cardM;
                $tambah->uid = $uid; 
                $tambah->nis = $nis; 
                $tambah->ket = 'siswa'; 
                $tambah->save();
                if ($tambah) {
                    return redirect()->back()->with('toast_success', 'CARD Berhasil ditambahkan');
                }
            }else {
                return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
            }
            
            
        } catch (\Throwable $th) {
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function cardCek(Request $request)
    {

        session_start();
        $Write= "";
        $url = public_path().'/masterUID/'.$_SESSION['perangkat'].'.php';
        file_put_contents($url,$Write);


        return view('pages.pagesCekCard');
    }

    public function cardData(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
        ]);

        $keyword = empty($request->keyword)?"":$request->keyword;

        $cek = cardM::join('siswa', 'siswa.nis', 'card.nis')
        ->select('siswa.namasiswa', 'card.*')
        ->where('card.uid', $keyword);

        if($cek->count() == 0) {
            return redirect()->back()->with('toast_error', 'Tidak ada data yang ditemukan')->withInput();
        }

        session_start();
        $Write= "";
        $url = public_path().'/masterUID/'.$_SESSION['perangkat'].'.php';
        file_put_contents($url,$Write);

        return view('pages.pagesCekCard',[
            'data' => $cek->first(),
        ]);

    }

    public function proses2(Request $request)
    {
        $request->validate([
            'uid' => 'required|unique:card,uid',
        ]);

        // try{
            $uid = $request->uid;

            $tambah = new cardM;
            $tambah->uid = $uid; 
            $tambah->nim = $uid; 
            $tambah->ket = 'master'; 
            $tambah->save();
            if ($tambah) {
                return redirect()->back()->with('toast_success', 'Master berhasil ditambahkan');
            }
        
        // }catch(\Throwable $th){
        //     return redirect('/mahasiswa/card')->with('toast_error', 'Terjadi kesalahan');
        // }
    }


    public function reset($nis)
    {
        try {
            $cek = cardM::where('nis', $nis)->count();
            if ($cek == 1) {
                $reset = cardM::where('nis', $nis)->delete();

                if($reset) {
                    return redirect()->back()->with('toast_success', 'Reset Berhasil');
                }

            }else {
                return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
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
     * @param  \App\Models\mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(mahasiswa $mahasiswa)
    {
        //
    }
}
