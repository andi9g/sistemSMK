<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use App\Models\krsM;
use App\Models\krsMatkulM;
use App\Models\penyelenggaraM;
use App\Models\kelasjadwalM;
use App\Models\jadwal;
use Illuminate\Http\Request;

class krsC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tahun = empty($request->tahun)?date('Y'):$request->tahun;
        $keyword = empty($request->keyword)?"":$request->keyword;

        $tampil = krsM::join('mahasiswa', 'mahasiswa.nim', '=', 'krs.nim')
        ->where('krs.tahun', "$tahun")
        ->where('krs.nim', "like", "$keyword%")
        ->select('mahasiswa.nim', 'mahasiswa.nama', 'krs.tahun', 'krs.id_krs')
        ->paginate(15);

        $tampil->appends($request->only('keyword', 'ruangan', 'hari', 'kelas', 'tahun' ,'limit'));

        return view('pages.pagesMahasiswaKrs', [
            'tahun' => $tahun,
            'krs' => $tampil,
        ]);
    }


    public function import(Request $request, $id)
    {
        try{
            // dd($id);
            $data = file_get_contents("http://localhost/E-KTM/public/api/krs_matkul/1");
            $json = json_decode($data, TRUE);
            $jumlah = count($json);
            
            foreach ($json as $key) {
                $idpenyelenggara =  $key['idpenyelenggara'];
                $id_krs =  $key['id_krs'];
                $kmatkul =  $key['kmatkul'];
                $tahun =  $key['tahun'];

                $cek1 = krsM::where('id_krs', $id);
                if ($cek1->count() == 1 && $cek1->first()->id_krs == $id_krs) {
                    $cek2 = krsMatkulM::where('id_krs', $id_krs)
                    ->where('idpenyelenggara', $idpenyelenggara)
                    ->count();
                    $cek3 = krsMatkulM::where('id_krs', $id_krs)
                    ->count();

                    if($cek2 == 0) {
                        $tambah = new krsMatkulM;
                        $tambah->idpenyelenggara = $idpenyelenggara;
                        $tambah->id_krs = $id_krs;
                        $tambah->save();

                        $cek4 = penyelenggaraM::where('idpenyelenggara', $idpenyelenggara)->count();
                        if($cek4 == 0) {
                            $tambah2 = new penyelenggaraM;
                            $tambah2->idpenyelenggara = $idpenyelenggara;
                            $tambah2->kmatkul = $kmatkul;
                            $tambah2->tahun = $tahun;
                            $tambah2->save();
                        }
                    }
                }
            }

            return redirect()->back()->with('success', "success")->withInput();

        }catch(\Throwable $th){
            return redirect('/mahasiswa/krs')->with('toast_error', 'Terjadi kesalahan');
        }


        


    }

    public function importJadwal(Request $request)
    {
        $request->validate([
            'kelas' => 'required',
            'tahun' => 'required',
        ]);

        try{
            $reset = false;
            $tahun = $request->tahun;
            $kelas = $request->kelas;
            $data = file_get_contents("http://localhost/E-KTM/public/api/jadwal/".$tahun);
            $json = json_decode($data, TRUE);
            $jumlah = count($json);
            
            $cekIsi = kelasjadwalM::where('kelas', $kelas)
            ->where('tahun', $tahun);

            if($cekIsi->count() == 0) {
                $tambah = new kelasjadwalM;
                $tambah->kelas = $kelas;
                $tambah->tahun = $tahun;
                $tambah->save();
            }else {
                $reset = true;
            }

            $ambil = kelasjadwalM::where('kelas', $kelas)
            ->where('tahun', $tahun)->select('idkelasjadwal')->first();
            $idkelasjadwal = $ambil->idkelasjadwal;

            

            foreach ($json as $key) {
                $tahun_ = $key['tahun'];
                $kelas_ = $key['kelas'];
                $kmatkul = $key['kmatkul'];
                $hari = $key['hari'];
                $jam_mulai = $key['jam_mulai'];
                $jam_selesai = $key['jam_selesai'];
                $no_ruangan = $key['no_ruangan'];
                if($tahun_ == $tahun && $kelas_ == $kelas) {
                    if($reset == true) {
                        jadwal::where('idkelasjadwal', $idkelasjadwal)->delete();
                        $reset = false;
                    }

                    $tambah = new jadwal;
                    $tambah->idkelasjadwal = $idkelasjadwal;
                    $tambah->kd_matkul = $kmatkul;
                    $tambah->hari = $hari;
                    $tambah->jam_mulai = $jam_mulai;
                    $tambah->jam_selesai = $jam_selesai;
                    $tambah->no_ruangan = $no_ruangan;
                    $tambah->save();
                }
            }

            return redirect()->back()->with('success', "success")->withInput();
            
        }catch(\Throwable $th){
            return redirect()->back()->with('errors', "terjadi kesalahan")->withInput();
        }
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
