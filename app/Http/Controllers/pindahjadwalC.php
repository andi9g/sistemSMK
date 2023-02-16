<?php

namespace App\Http\Controllers;

use App\Models\pindahjadwalM;
use App\Models\master;
use App\Models\kelasmhsM;
use App\Models\semesterM;
use App\Models\penyelenggaraM;
use App\Models\hariM;
use App\Models\ruanganM;
use App\Models\absenDetailM;
use App\Models\absen;
use Illuminate\Http\Request;

class pindahjadwalC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;

        $tahun = date('Y');

        // $master = master::get();

        // $tampil = pindahjadwalM::join('kelas_mhs', 'kelas_mhs.idkelas_mhs', '=', 'pindahjadwal.idkelas_mhs')
        // ->join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs', '=', 'pindahjadwal.idkelas_mhs')
        // ->join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
        // ->where('tahun_ajaran.tahun', $tahun)
        // ->where(function ($query) use ($keyword){
        //     $query->where('kelas_mhs.nidn', 'like', "$keyword%")
        //           ->orWhere('pindahjadwal.idkelas_mhs', 'like', "$keyword%")
        //           ->orWhere('kelas_mhs.idkelas_mhs', 'like', "$keyword%")
        //           ->orWhere('matkul.nmatkul', 'like', "$keyword%");
        // })->paginate(15);

        // $tampil->appends($request->only(['limit', 'keyword']));
        
        $datanidn = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
        ->join('penyelenggara_smt', 'penyelenggara_smt.idsmt', '=', 'tahun_ajaran.idsmt')
        ->where('tahun_ajaran.tahun', $tahun)
        ->where(function ($query) use ($keyword) {
            $query->where('kelas_mhs.nidn', 'like', "$keyword%")
                  ->orWhere('kelas_mhs.nama_dosen', 'like', "%$keyword%");
        })
        ->groupBy('kelas_mhs.nama_dosen')
        ->groupBy('kelas_mhs.nidn')
        ->select('kelas_mhs.nidn', 'kelas_mhs.nama_dosen')
        ->paginate(25);
        
        $datanidn->appends($request->only(['limit', 'keyword']));
        return view('pages.pagesPindahjadwal', [
            'tampil' => $datanidn,
        ]);


    }

    public function jadwal(Request $request, $nidn)
    {
        $hari = empty($request->hari)?"":$request->hari;
        $tahun = empty($request->tahun)?date('Y'):$request->tahun;

        $dosen = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
        // ->join('penyelenggara_smt', 'penyelenggara_smt.idsmt', '=', 'tahun_ajaran.idsmt')
        // ->join('semester', 'tahun_ajaran.idsmt', '=', 'semester.idsmt')
        // ->where('semester.idsmt', 'like', "%$idmst%")
        ->where('tahun_ajaran.tahun', $tahun)
        ->where('kelas_mhs.nidn', $nidn)
        ->groupBy('kelas_mhs.nama_dosen')
        ->groupBy('kelas_mhs.nidn')
        ->select('kelas_mhs.nidn', 'kelas_mhs.nama_dosen')
        ->first();

        $tampil = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
        // ->join('penyelenggara_smt', 'penyelenggara_smt.idsmt', '=', 'tahun_ajaran.idsmt')
        ->join('semester', 'tahun_ajaran.idsmt', '=', 'semester.idsmt')
        ->join('hari', 'hari.idhari', '=', 'kelas_mhs.idhari')
        ->join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
        ->join('kelas', 'kelas.idkelas', '=', 'kelas_mhs.idkelas')
        // ->where('semester.idsmt', 'like', "%$idsmt%")
        ->where(function ($query) use ($hari, $tahun) {
            $query->where('kelas_mhs.idhari', 'like', $hari."%")
                  ->where('tahun_ajaran.tahun', 'like', $tahun."%");
        })
        ->where('kelas_mhs.nidn', $nidn)
        ->select('kelas_mhs.*','tahun_ajaran.*','hari.*', 'matkul.nmatkul', 'kelas.nama_kelas','semester.namasmt')
        ->get()
        ;

        $ttahun = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs', '=', 'kelas_mhs.idkelas_mhs')->groupBy('tahun_ajaran.tahun')->select('tahun_ajaran.tahun')->get();

        $thari = hariM::get();
        // dd($tampil);

        return view('pages.pagesDataJadwal', [
            'dosen' => $dosen,
            'tampil' => $tampil,
            'hari' => $hari,
            'tahun' => $tahun,

            //select
            'ttahun' => $ttahun,
            'thari' => $thari,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pindahjadwal(Request $request, $nidn, $idkelas_mhs)
    {
        $tahun = empty($request->tahun)?date('Y'):$request->tahun;

        $dosen = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
        ->join('penyelenggara_smt', 'penyelenggara_smt.idsmt', '=', 'tahun_ajaran.idsmt')
        // ->join('semester', 'tahun_ajaran.idsmt', '=', 'semester.idsmt')
        // ->where('semester.idsmt', 'like', "%$idmst%")
        ->where('tahun_ajaran.tahun', $tahun)
        ->where('kelas_mhs.nidn', $nidn)
        ->groupBy('kelas_mhs.nama_dosen')
        ->groupBy('kelas_mhs.nidn')
        ->select('kelas_mhs.nidn', 'kelas_mhs.nama_dosen')
        ->first();

        $tampil = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
        ->join('penyelenggara_smt', 'penyelenggara_smt.idsmt', '=', 'tahun_ajaran.idsmt')
        ->join('semester', 'tahun_ajaran.idsmt', '=', 'semester.idsmt')
        ->join('hari', 'hari.idhari', '=', 'kelas_mhs.idhari')
        ->join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
        ->join('kelas', 'kelas.idkelas', '=', 'kelas_mhs.idkelas')
        // ->where('semester.idsmt', 'like', "%$idsmt%")
        ->where('kelas_mhs.idkelas_mhs', $idkelas_mhs)
        ->where('kelas_mhs.nidn', $nidn)
        ->select('kelas_mhs.*','tahun_ajaran.*','hari.*', 'matkul.nmatkul', 'kelas.nama_kelas', 'kelas.jam_mulai', 'kelas.jam_selesai','penyelenggara_smt.tanggal')
        ->first()
        ;

        $data = kelasmhsM::join('matkul','matkul.kode_matkul','=','kelas_mhs.kode_matkul')
        ->join('tahun_ajaran','tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
        ->join('semester', 'semester.idsmt', '=', 'tahun_ajaran.idsmt')
        ->join('prodi', 'prodi.id_prodi', '=', 'tahun_ajaran.id_prodi')
        ->join('hari', 'hari.idhari','=','kelas_mhs.idhari')
        ->select('matkul.kode_matkul','matkul.nmatkul','semester.namasmt', 'tahun_ajaran.tahun', 'kelas_mhs.nama_dosen','kelas_mhs.nidn', 'prodi.nama_prodi', 'kelas_mhs.namakelas', 'tahun_ajaran.idsmt', 'kelas_mhs.idkelas','hari.nama_hari_en','kelas_mhs.idkelas_mhs')
        ->where('kelas_mhs.idkelas_mhs', $idkelas_mhs)
        ->first();
        
        $idkelas_mhs = $data->idkelas_mhs;
        $idsmt = $data->idsmt;
        $idkelas = $data->idkelas;
        $hari = $data->nama_hari_en;
        $tanggal_mulai = strtotime(penyelenggaraM::where('idsmt', $idsmt)->where('idkelas',$idkelas)->first()->tanggal);

        $ruangan = ruanganM::get();
        
        $hari = hariM::get();
        
        //rumus
        // $tanggal_mulai = strtotime($tampil->tanggal);
        $tanggal_selesai = strtotime(date('Y-m-d', strtotime('+16 week', $tanggal_mulai)));
        // dd(date('Y-m-d',$tanggal_mulai));
        $tanggal = [];
        $tanggal2 = [];

        $no = 0;
        $no2 = 0;
        for($i = $tanggal_mulai;$i<=$tanggal_selesai; $i += 86400) {
            $cekTgl = date('l', $i);
            if($cekTgl == $tampil->nama_hari_en) {
                $cek1 = absenDetailM::join('absen', 'absen.id_absen', '=', 'absendetail.idabsen')
                        ->where('absen.idkelas_mhs', $tampil->idkelas_mhs)
                        ->where('absendetail.created_at', 'like', (date('Y-m-d', $i))."%")
                        ->count();
                $cek2 = pindahjadwalM::where('idkelas_mhs', $tampil->idkelas_mhs)
                        ->where('tanggalmulai', date('Y-m-d', $i))
                        ->count();
                
                if($cek1 == 0 && $cek2 == 0) {
                    $tanggal[$no2]['tanggal'] = date('Y-m-d', $i);
                    $tanggal[$no2]['pertemuan'] = $no+1;
                    $tanggal2[] = $cekTgl;
                    $no2++;
                }
                
                $no++;
            }
        }
        // dd($tanggal);

        $jam_mulai = strtotime($tampil->jam_mulai);
        $jam_selesai = strtotime($tampil->jam_selesai);

        $tampung_jam = [];
        $tampung_menit = [];
        for ($i=$jam_mulai; $i < $jam_selesai; $i) { 
            $tampung_jam[] = date('H', $i);

            $i = strtotime('+1 hour',$i);
        }
        


        // dd(sprintf('%02s', $coba));

        for ($i=0; $i < 60; $i++) { 
            if($i % 5 == 0) {
                $tampung_menit[] = sprintf('%02s', $i);
            }
        }

        //1662166800

        return view('pages.pagesJadwalPindah', [
            'dosen' => $dosen,
            'matkul' => $tampil,
            'hari' => $hari,
            'tanggal' => $tanggal,
            'tampung_jam' => $tampung_jam,
            'tampung_menit' => $tampung_menit,
            'ruangan' => $ruangan,
        ]);
    }

    public function prosespindahjadwal(Request $request, $nidn, $idkelas_mhs)
    {
        $request->validate([
            'idkelas_mhs' => 'required',
            'tanggalmulai' => 'required',
            'tanggalubah' => 'required',
            'jam' => 'required',
            'menit' => 'required',
            'idruangan' => 'required',
        ]);
        
        
        try{
            $idkelas_mhs = $request->idkelas_mhs;
            $tanggalM = explode("---", $request->tanggalmulai);
            $tanggalmulai = $tanggalM[0];
            $pertemuan = $tanggalM[1];
            
            $tanggalubah = $request->tanggalubah;

            //jam
            $jam = $request->jam;
            $menit = $request->menit;
            $jam_masuk = $jam.":".$menit;
            
            //proses jam keluar
            $kelasmhs = kelasmhsM::where('idkelas_mhs', $idkelas_mhs)
                        ->select('jam_masuk', 'jam_keluar')->first();
            $jamM = strtotime($kelasmhs->jam_masuk);
            $jamK = strtotime($kelasmhs->jam_keluar);

            //menghitung sks sebelumnya
            $sks = 0;
            for ($i=$jamM; $i < $jamK ; $i) { 
                $sks++;
                $i = strtotime('+1 minutes', $i);
            }
            //-------------------------------------
            $jam_keluar = date('H:i', strtotime('+'.$sks.' minutes', strtotime($jam_masuk)));
            $idruangan = $request->idruangan;
        
            $store = new pindahjadwalM;
            $store->idkelas_mhs = $idkelas_mhs;
            $store->pertemuan = $pertemuan;
            $store->tanggalmulai = $tanggalmulai;
            $store->tanggalubah = $tanggalubah;
            $store->jam_masuk = $jam_masuk;
            $store->jam_keluar = $jam_keluar;
            $store->idruangan = $idruangan;
            $store->save();
            if($store) {
                return redirect('pindahjadwal/'.$nidn)->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('pindahjadwal/'.$nidn)->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function hapuspindahjadwal($idpindahjadwal)
    {
        try{
            $destroy = pindahjadwalM::where('idpindahjadwal', $idpindahjadwal)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
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
     * @param  \App\Models\pindahjadwalM  $pindahjadwalM
     * @return \Illuminate\Http\Response
     */
    public function show(pindahjadwalM $pindahjadwalM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pindahjadwalM  $pindahjadwalM
     * @return \Illuminate\Http\Response
     */
    public function edit(pindahjadwalM $pindahjadwalM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pindahjadwalM  $pindahjadwalM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pindahjadwalM $pindahjadwalM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pindahjadwalM  $pindahjadwalM
     * @return \Illuminate\Http\Response
     */
    public function destroy(pindahjadwalM $pindahjadwalM)
    {
        //
    }
}
