<?php

namespace App\Http\Controllers;

use App\Models\absenM;
use App\Models\jurusanM;
use App\Models\kelasM;
use App\Models\siswaM;
use App\Models\ketM;
use App\Models\openM;
use App\Models\pengaturanM;
use Illuminate\Http\Request;

class absenC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggal = empty($request->tanggal)?date('Y-m-d'):$request->tanggal;
        $jurusan = empty($request->jurusan)?"":$request->jurusan;
        $kelas = empty($request->kelas)?"":$request->kelas;
        $keyword = empty($request->keyword)?"":$request->keyword;
        
        $Djurusan = jurusanM::get();
        $Dkelas = kelasM::get();

        $open = openM::first();
        $ket = ketM::get();

        $pengaturan = pengaturanM::first();
        // dd($pengaturan->keterlambatan);
        $siswa = siswaM::join('jurusan', 'jurusan.idjurusan', 'siswa.idjurusan')
        ->leftJoin('absen', 'absen.nis', 'siswa.nis')
        ->where('absen.ket', null)
        ->select('siswa.namasiswa', 'siswa.nis', 'jurusan.namajurusan')
        ->get();

        $jumlahSiswa = siswaM::join('jurusan', 'siswa.idjurusan', 'jurusan.idjurusan')
        ->join('kelas', 'kelas.idkelas', 'siswa.idkelas')
        ->where(function ($query) use ($keyword){
            $query->where('siswa.nis', 'like', "$keyword%")
            ->orWhere('siswa.namasiswa', 'like', "%$keyword%");
        })
        ->where('kelas.idkelas', 'like', $kelas."%")
        ->where('jurusan.idjurusan', 'like', $jurusan."%")
        ->count();

        $jumlahKehadiran = absenM::join('siswa', 'siswa.nis', 'absen.nis')
        ->join('jurusan', 'siswa.idjurusan', 'jurusan.idjurusan')
        ->join('kelas', 'kelas.idkelas', 'siswa.idkelas')
        ->where(function ($query) use ($keyword){
            $query->where('siswa.nis', 'like', "$keyword%")
            ->orWhere('siswa.namasiswa', 'like', "%$keyword%");
        })
        ->whereDate('absen.tanggal', $tanggal)
        ->where('kelas.idkelas', 'like', $kelas."%")
        ->where('jurusan.idjurusan', 'like', $jurusan."%")
        ->count();

        $absen = absenM::rightJoin('siswa', 'siswa.nis', 'absen.nis')
        ->join('jurusan', 'siswa.idjurusan', 'jurusan.idjurusan')
        ->join('kelas', 'kelas.idkelas', 'siswa.idkelas')
        ->where(function ($query) use ($keyword){
            $query->where('siswa.nis', 'like', "$keyword%")
            ->orWhere('siswa.namasiswa', 'like', "%$keyword%");
        })
        ->whereDate('absen.tanggal', $tanggal)
        ->where('kelas.idkelas', 'like', $kelas."%")
        ->where('jurusan.idjurusan', 'like', $jurusan."%")
        ->select('absen.*', 'siswa.namasiswa', 'kelas.namakelas', 'jurusan.namajurusan')
        ->paginate(10);

        $absen->appends($request->only(['limits', 'keyword', 'jurusan', 'kelas', 'tanggal']));

        return view('pages.pagesAbsen', [
            'tanggal' => $tanggal,
            'absen' => $absen,
            'jurusan' => $jurusan,
            'kelas' => $kelas,
            'siswa' => $siswa,

            'open' => $open,
            'ket' => $ket,
            'jumlahSiswa' => $jumlahSiswa,
            'jumlahKehadiran' => $jumlahKehadiran,

            'pengaturan' => $pengaturan,
            'Djurusan' => $Djurusan,
            'Dkelas' => $Dkelas,
        ]);
    }

    public function ubahjam(Request $request)
    {
        $idopen = openM::first()->idopen;
        $open = openM::first()->open;
        $pesan = "";
        if($open == true) {
            openM::where('idopen', $idopen)->update([
                'open' => false,
            ]);
            $pesan = "Telah dipindahkan ke <br> <h2>JAM KELUAR</h2>";
        }else{
            openM::where('idopen', $idopen)->update([
                'open' => true,
            ]);
            $pesan = "Telah dipindahkan ke <br> <h2>JAM MASUK</h2>";
        }

        return redirect()->back()->with('success', $pesan)->withInput();
    }


    public function keterangan(Request $request)
    {
        $request->validate([
            'siswa' => 'required',
            'keterangan' => 'required',
        ]);

        try{
            $siswa = $request->siswa;
            $keterangan = $request->keterangan;
            $tanggal = date('Y-m-d');
            $jammasuk = date('H:i');

            $cek = absenM::where('tanggal', $tanggal)->where('nis', $siswa)->count();
            if ($cek > 0) {
                return redirect()->back()->with('toast_warning', 'Siswa telah melakukan absen');
            }

            $tambah = new absenM;
            $tambah->nis = $siswa;
            $tambah->tanggal = $tanggal;
            $tambah->jammasuk = $jammasuk;
            $tambah->ket = $keterangan;
            $tambah->save();

            if ($tambah) {
                return redirect()->back()->with('toast_success', 'Success');
            }

        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function ubahketerangan(Request $request, $idabsen)
    {
        $request->validate([
            'keterangan' => 'required',
        ]);
        
        
        try{
            $keterangan = $request->keterangan;
        
            $update = absenM::where('idabsen', $idabsen)->update([
                'ket' => $keterangan,
            ]);
            if($update) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function hapusketerangan(Request $request, $idabsen)
    {
        try{
            $destroy = absenM::where('idabsen', $idabsen)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
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
     * @param  \App\Models\absenM  $absenM
     * @return \Illuminate\Http\Response
     */
    public function show(absenM $absenM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\absenM  $absenM
     * @return \Illuminate\Http\Response
     */
    public function edit(absenM $absenM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\absenM  $absenM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, absenM $absenM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\absenM  $absenM
     * @return \Illuminate\Http\Response
     */
    public function destroy(absenM $absenM)
    {
        //
    }
}
