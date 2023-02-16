<?php

namespace App\Http\Controllers;

use App\Models\master;
use App\Models\kelasM;
use App\Models\matkulM;
use App\Models\jurusanM;
use App\Models\adminM;
use App\Models\siswaM;
use App\Models\semesterM;
use App\Models\prodiM;
use App\Models\cardM;
use App\Models\absen;
use App\Models\hariM;
use App\Models\kelasmhsM;
use App\Models\ruanganM;
use App\Models\pesertamhsM;
use App\Models\penyelenggaraM;
use App\Models\tahunajaranM;
use App\Models\absenDetailM;
use App\Models\pindahjadwalM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker;

class indexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function root()
    {
        return redirect('login');
    }

    public function index(Request $request)
    {
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

        $open = master::join('ruangan', 'ruangan.idruangan','=','ruangan_master.idruangan')
        ->select('ruangan.nama_ruangan', 'ruangan_master.idruangan_master','ruangan.idruangan')
        ->where('computerId', $computerId);

        $total = $open->count();

        $ambil = $open->first();
        $idruangan = empty($ambil->idruangan)?0:$ambil->idruangan;
        // dd($idruangan);
        if($total > 0 && $idruangan!=0){
            $Write= "";
            $url = public_path().'/ruangan/'.$ambil->idruangan_master.'Container.php';
            file_put_contents($url,$Write);
        }else {
            return redirect('login');
        }


        $hari = date('l');
        $tahun = date('Y');
        $kelasdata = kelasM::get();
        $kelas = [];
        $matkul = [];
        $idkelas_mhs = [];
        $tanggal_sekarang = date('Y-m-d');
        $idruangan_master = $ambil->idruangan_master;

        foreach ($kelasdata as $kls) {
            $jam_mulai = strtotime(date('H:i', strtotime($kls->jam_mulai)));
            $jam_selesai = strtotime(date('H:i', strtotime($kls->jam_selesai)));
            $jam_sekarang = strtotime(date('H:i'));

            // dd($jam_mulai."    ".$jam_selesai."    ".$jam_sekarang."    ".$kls->kelas);
            //kelas di jam tertentu
            if(($jam_sekarang>= $jam_mulai) && $jam_sekarang <= $jam_selesai) {
                $kelas[] = $kls->idkelas;
            }
        }

        // dd($kelas);
        foreach ($kelas as $kls) {
            $idsmt = penyelenggaraM::where('idkelas', $kls)->first()->idsmt;

            $cek = kelasmhsM::join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
            ->join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
            ->join('hari', 'hari.idhari','=','kelas_mhs.idhari')
            ->join('kelas', 'kelas.idkelas','=','kelas_mhs.idkelas')
            ->join('ruangan', 'ruangan.idruangan','=','kelas_mhs.idruangan')
            ->select('kelas_mhs.*', 'matkul.nmatkul', 'kelas.nama_kelas', 'ruangan.nama_ruangan', 'kelas_mhs.idkelas_mhs')
            ->where('tahun_ajaran.tahun', $tahun)
            ->where('kelas_mhs.idkelas', $kls)
            ->where('tahun_ajaran.idsmt', $idsmt)
            ->where('hari.nama_hari_en', $hari)
            ->where('kelas_mhs.idruangan', $idruangan)->get();

            if(count($cek) > 0) {
                foreach ($cek as $dt) {
                    // $sks = $dt->sks * (60*45);

                    $pindahjadwalCek = pindahjadwalM::where('idkelas_mhs', $dt->idkelas_mhs)
                    ->where('idruangan', $idruangan)->where('tanggalmulai', $tanggal_sekarang)
                    ->count();

                    $jam_masuk = strtotime($dt->jam_masuk); 
                    $jam_keluar = strtotime($dt->jam_keluar);
                    $jam_sekarang = strtotime(date('H:i'));

                    if($pindahjadwalCek===0){
                        if($jam_sekarang >= $jam_masuk && $jam_sekarang <= $jam_keluar){
                            $matkul[] = $dt->nama_kelas."  -  ".$dt->kode_matkul."  -  ".$dt->nmatkul;
                            $idkelas_mhs[] = $dt->idkelas_mhs;
                        }
                    }
                    
                }

                

            }

            $pindahjadwalCek = pindahjadwalM::join('kelas_mhs', 'kelas_mhs.idkelas_mhs','=','pindahjadwal.idkelas_mhs')
                ->join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
                ->join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
                ->join('kelas', 'kelas.idkelas', '=', 'kelas_mhs.idkelas')
                ->where('tahun_ajaran.tahun', $tahun)
                ->where('kelas_mhs.idkelas', $kls)
                ->where('tahun_ajaran.idsmt', $idsmt)
                ->where('pindahjadwal.tanggalubah', $tanggal_sekarang)
                ->where('pindahjadwal.idruangan', $idruangan)
                ->select('kelas_mhs.*', 'matkul.*', 'pindahjadwal.*','kelas.nama_kelas')
                ->get();
            
            
            foreach ($pindahjadwalCek as $dt) {
                $jam_masuk = strtotime($dt->jam_masuk); 
                $jam_keluar = strtotime($dt->jam_keluar);
                $jam_sekarang = strtotime(date('H:i'));

                // dd($jam_masuk." - ".$jam_keluar." - ".$jam_sekarang);
                if($jam_sekarang >= $jam_masuk && $jam_sekarang <= $jam_keluar){
                    // dd($berhasil);
                    $matkul[] = $dt->nama_kelas."  -  ".$dt->kode_matkul."  -  ".$dt->nmatkul;
                    $idkelas_mhs[] = $dt->idkelas_mhs;
                }
            }
        }


        return view('pages.pagesAbsen', [
            'total' => $total,
            'no_ruangan' => $idruangan_master,
            'matkul' => $matkul,
            'idkelas_mhs' => $idkelas_mhs,
        ]);
    }

    public function menuabsen(Request $request)
    {
        if($request->session()->has('open')){
            return view('pages.pagesMenuAbsen');
        }else {
            return redirect('/');
        }
    }

    public function prosesmenuabsen(Request $request)
    {
        if($request->session()->has('open')){
            $request->validate([
                'keterlambatan' => 'required',
            ]);
            
            
            try{
                $request->session()->put('keterlambatan', $request->keterlambatan);
                return redirect('menuabsen')->with('toast_success', 'success');
            }catch(\Throwable $th){
                return redirect('menuabsen')->with('toast_error', 'Terjadi kesalahan');
            }
        }else {
            return redirect('/');
        }
        
    }
    


    public function proses(Request $request)
    {

        $jam_sekarang = strtotime(date('H:i'));
        
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

        $open = master::join('ruangan', 'ruangan.idruangan','=','ruangan_master.idruangan')
        ->select('ruangan.nama_ruangan', 'ruangan_master.idruangan_master','ruangan.idruangan')
        ->where('computerId', $computerId);

        
        $total = $open->count();
        $ambil = $open->first();
        $idruangan = empty($ambil->idruangan)?0:$ambil->idruangan;

        // dd($idruangan." ".$total);
        // dd($total.$idruangan);
        if($total > 0 && $idruangan!=0){

            $tidak_ada_jadwal = false;
            $tidak_ada_jadwal2 = false;

            $uid = $request->uid;
            $tahun = date('Y');
            $hari = date('l');
            $kelasdata = kelasM::get();
            $kelas = [];
            $matkul = [];
            $kelasM = "";
            $gagal = [];
            $tanggal_sekarang = date('Y-m-d');

            $ambil_nim = cardM::join('mahasiswa', 'mahasiswa.nim', '=', 'card.nim')
            ->join('prodi','prodi.id_prodi','=','mahasiswa.id_prodi')
            ->join('kelas', 'kelas.idkelas','=','mahasiswa.idkelas')
            ->where('card.uid', $uid)
            ->select('card.nim','mahasiswa.nama_mhs','prodi.id_prodi','kelas.idkelas', 'card.ket');
            
            $ambil_master = empty(cardM::where('uid', $uid)->first()->ket)?'none':cardM::where('uid', $uid)->first()->ket;

            if ($ambil_master === "master") {
                
                if($request->session()->get('open')===true){
                    // $request->session()->flush();
                    return redirect('menuabsen');
                }else {
                    $request->session()->put('open', true);
                    if($request->session()->has('keterlambatan')==false && $request->session()->has('keterlambatan_jam')==false) {
                        $request->session()->put('keterlambatan', 0);
                        $request->session()->put('keterlambatan_jam', date('H:i'));
                    }
                    return redirect('menuabsen');
                }
                

            }else if($ambil_master === "mahasiswa") {
                $request->session()->forget('open');

            }else if($ambil_nim->count() == 0){
                return redirect()->back()->with('warning', '<h5>Anda tidak terdaftar sebagai mahasiswa<br>STT Indonesia Tanjungpinang</h5>')->withInput();
            }else if($ambil_master === 'none'){
                return redirect()->back()->with('warning','kartu tidak terdaftar');
            }


            if($request->session()->has('keterlambatan') && $request->session()->has('keterlambatan_jam')) {
                $keterlambatan = $request->session()->get('keterlambatan');
                // dd($keterlambatan);
                $keterlambatan_jam = strtotime("+".$keterlambatan." minutes", strtotime($request->session()->get('keterlambatan_jam')));
                
                if($jam_sekarang > $keterlambatan_jam && $keterlambatan != 0) {
                    return redirect()->back()->with('warning',"Maaf, anda terlambat!");
                }

            }else {
                return redirect('/')->with('warning','Silahkan Menunggu dosen membuka absensi');
            }

            $data_nim = $ambil_nim->first()->nim;
            $data_prodi = $ambil_nim->first()->id_prodi;
            $data_idkelas = $ambil_nim->first()->id_idkelas;
            
            $telah_melakukan_absen = false;
            $absenPindah = false;

            foreach ($kelasdata as $kls) {
                $jam_mulai = strtotime(date('H:i', strtotime($kls->jam_mulai)));
                $jam_selesai = strtotime(date('H:i', strtotime($kls->jam_selesai)));
                //kelas di jam tertentu
                if(($jam_sekarang>= $jam_mulai) && $jam_sekarang <= $jam_selesai) {
                    $kelas[] = $kls->idkelas;
                }
            }
            

            foreach ($kelas as $kls) {
                $idsmt = penyelenggaraM::where('idkelas', $kls)->first()->idsmt;

                $cek = kelasmhsM::join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
                ->join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
                ->join('hari', 'hari.idhari','=','kelas_mhs.idhari')
                ->join('kelas', 'kelas.idkelas','=','kelas_mhs.idkelas')
                ->join('ruangan', 'ruangan.idruangan','=','kelas_mhs.idruangan')
                ->select('kelas_mhs.*', 'matkul.nmatkul', 'kelas.nama_kelas', 'ruangan.nama_ruangan', 'hari.nama_hari_en')
                ->where('tahun_ajaran.tahun', $tahun)
                ->where('kelas_mhs.idkelas', $kls)
                ->where('tahun_ajaran.idsmt', $idsmt)
                ->where('hari.nama_hari_en', $hari)
                ->where('kelas_mhs.idruangan', $idruangan)
                ->get();

                // dd($cek);
                //proses pindah jadwal
                $bukajadwal = false;
                $pindahjadwalCek = pindahjadwalM::join('kelas_mhs', 'kelas_mhs.idkelas_mhs','=','pindahjadwal.idkelas_mhs')
                ->join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
                ->join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
                ->join('kelas', 'kelas.idkelas', '=', 'kelas_mhs.idkelas')
                ->where('tahun_ajaran.tahun', $tahun)
                ->where('kelas_mhs.idkelas', $kls)
                ->where('tahun_ajaran.idsmt', $idsmt)
                ->where('pindahjadwal.tanggalubah', $tanggal_sekarang)
                ->where('pindahjadwal.idruangan', $idruangan)
                ->count();

                if($pindahjadwalCek > 0) {
                    $pindahjadwal = pindahjadwalM::join('kelas_mhs', 'kelas_mhs.idkelas_mhs','=','pindahjadwal.idkelas_mhs')
                    ->join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
                    ->join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
                    ->join('kelas', 'kelas.idkelas', '=', 'kelas_mhs.idkelas')
                    ->where('tahun_ajaran.tahun', $tahun)
                    ->where('kelas_mhs.idkelas', $kls)
                    ->where('tahun_ajaran.idsmt', $idsmt)
                    ->where('pindahjadwal.tanggalubah', $tanggal_sekarang)
                    ->where('pindahjadwal.idruangan', $idruangan)
                    ->select('kelas_mhs.*', 'matkul.*', 'pindahjadwal.*')
                    ->get();

                    foreach ($pindahjadwal as $dt) {
                        $hariCek = date('l', strtotime($dt->tanggalmulai));
                        $idhari = hariM::where('nama_hari_en', $hariCek)->first()->idhari;
                        $nama_matkul_ = $dt->nmatkul;

                        $jam_mulai = strtotime($dt->jam_masuk); 
                        $jam_selesai = strtotime($dt->jam_keluar);

                        //masing-masing kelas mahasiswa di ambil dan di cek jam masuk dan keluar
                        if($jam_sekarang >= $jam_mulai && $jam_sekarang <= $jam_selesai){
                            $kmatkul = $dt->kode_matkul;
                            $harilama = date('l', strtotime($dt->tanggalmulai));

                            //cekpeserta yang mengontrak
                            $cekNim = pesertamhsM::join('kelas_mhs', 'kelas_mhs.idkelas_mhs','=','kelas_mhs_peserta.idkelas_mhs')
                            ->join('mahasiswa', 'mahasiswa.nim', '=', 'kelas_mhs_peserta.nim')
                            ->join('tahun_ajaran','tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
                            ->join('prodi', 'prodi.id_prodi','=','mahasiswa.id_prodi')
                            ->join('kelas', 'kelas.idkelas','=','mahasiswa.idkelas')
                            ->join('hari', 'hari.idhari','=','kelas_mhs.idhari')
                            ->join('ruangan', 'ruangan.idruangan','=','kelas_mhs.idruangan')
                            ->join('matkul', 'matkul.kode_matkul','=','kelas_mhs.kode_matkul')
                            ->select('kelas_mhs.idkelas_mhs','kelas_mhs_peserta.id_peserta', 'mahasiswa.nim', 'hari.nama_hari_en')
                            ->where('kelas_mhs.kode_matkul', $kmatkul)
                            ->where('tahun_ajaran.tahun', $tahun)
                            ->where('tahun_ajaran.idsmt', $idsmt)
                            ->where('hari.nama_hari_en', $harilama)
                            ->where('kelas_mhs.idkelas', $kls)
                            ->where('kelas_mhs.idruangan', $idruangan)
                            ->where('kelas_mhs_peserta.nim', $data_nim);
                            
                            // dd($kmatkul." - " . $tahun." - ". $idsmt." - ".$harilama." - ". $kls. " - ". $idruangan. " - ". $data_nim);
                            if($cekNim->count() == 1) {
                                $idkelas_mhs = $cekNim->first()->idkelas_mhs;
                                $id_peserta = $cekNim->first()->id_peserta;

                                $cekAbsen = absen::where('idkelas_mhs', $idkelas_mhs)
                                ->where('id_peserta', $id_peserta)->count();

                                if($cekAbsen == 0) {
                                    $tambah = new absen;
                                    $tambah->idkelas_mhs = $idkelas_mhs;
                                    $tambah->id_peserta = $id_peserta;
                                    $tambah->save();
                                    $tidak_ada_jadwal = true;                  
                                }

                                $ambilAbsen = absen::where('idkelas_mhs', $idkelas_mhs)->where('id_peserta', $id_peserta)->first();
                                $id_absen = $ambilAbsen->id_absen;

                                
                                $cekAbsen2 = absenDetailM::join('absen', 'absen.id_absen', '=', 'absendetail.idabsen')
                                ->join('kelas_mhs', 'kelas_mhs.idkelas_mhs', '=','absen.idkelas_mhs')
                                ->join('pindahjadwal', 'pindahjadwal.idkelas_mhs','=', 'absen.idkelas_mhs')
                                ->where('pindahjadwal.tanggalubah', $tanggal_sekarang)
                                ->where('kelas_mhs.idkelas_mhs', $dt->idkelas_mhs)
                                ->where('absendetail.idabsen', $id_absen)
                                ->orderBy('absendetail.updated_at', 'desc')
                                ->orderBy('absendetail.created_at', 'desc')
                                ->select('absendetail.*', 'pindahjadwal.tanggalubah');

                                
                                // dd($cekAbsen2->count());
                                $tambahPindah = false;
                                if($cekAbsen2->count() == 0) {
                                    $pindahjadwal = pindahjadwalM::where('idkelas_mhs', $idkelas_mhs)
                                    ->where('tanggalubah', date('Y-m-d'));
                                    foreach ($pindahjadwal->get() as $item) {
                                        $tambahPindah = new absenDetailM;
                                        $tambahPindah->idabsen = $id_absen;
                                        $tambahPindah->idhari = hariM::where('nama_hari_en', date('l', strtotime($item->tanggalmulai)))->first()->idhari;
                                        $tambahPindah->jam_absen = date('H:i',$jam_sekarang);
                                        $tambahPindah->idkehadiran = 1;
                                        $tambahPindah->created_at = $item->tanggalmulai;
                                        $tambahPindah->updated_at = date('Y-m-d H:i:s');
                                        $tambahPindah->save();
                                    }

                                }else if($cekAbsen2->count() > 0) {
                                    $tanggalAbsen = strtotime(date('Y-m-d',strtotime($cekAbsen2->first()->updated_at)));
                                    $tanggalSekarang = strtotime(date('Y-m-d'));

                                    // dd($tanggalAbsen."  ".$tanggalSekarang);
                                    $pindahjadwal = pindahjadwalM::where('idkelas_mhs', $idkelas_mhs)
                                    ->where('tanggalubah', date('Y-m-d'));

                                    foreach ($pindahjadwal->get() as $item) {
                                        if($tanggalAbsen == $tanggalSekarang) {
                                            $telah_melakukan_absen = true;
                                        }else if($tanggalAbsen < $tanggalSekarang) {
                                            $tambahPindah = new absenDetailM;
                                            $tambahPindah->idabsen = $id_absen;
                                            $tambahPindah->idhari = hariM::where('nama_hari_en', date('l', strtotime($item->tanggalmulai)))->first()->idhari;
                                            $tambahPindah->jam_absen = date('H:i',$jam_sekarang);
                                            $tambahPindah->idkehadiran = 1;
                                            $tambahPindah->created_at = $item->tanggalmulai;
                                            $tambahPindah->updated_at = date('Y-m-d H:i:s');
                                            $tambahPindah->save();
                                        }

                                        
                                    }
                                }
                                
                                if($tambahPindah){
                                    $absenPindah = true;
                                }


                            }else {
                                // $tidak_ada_jadwal = true;
                            }
                            
                            // dd($cekNim->kd_matkul);

                        }
                    }
                }



                if(count($cek) > 0 && $bukajadwal == false) {    
                    foreach ($cek as $dt) {
                        $hariCek = $dt->nama_hari_en;
                        $idhari = $dt->idhari;
                        $nama_matkul_ = $dt->nmatkul;

                        $jam_mulai = strtotime($dt->jam_masuk); 
                        $jam_selesai = strtotime($dt->jam_keluar);

                        //masing-masing kelas mahasiswa di ambil dan di cek jam masuk dan keluar
                        if($jam_sekarang >= $jam_mulai && $jam_sekarang <= $jam_selesai){
                            $kmatkul = $dt->kode_matkul;

                            $cekNim = pesertamhsM::join('kelas_mhs', 'kelas_mhs.idkelas_mhs','=','kelas_mhs_peserta.idkelas_mhs')
                            ->join('mahasiswa', 'mahasiswa.nim', '=', 'kelas_mhs_peserta.nim')
                            ->join('tahun_ajaran','tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
                            ->join('prodi', 'prodi.id_prodi','=','mahasiswa.id_prodi')
                            ->join('kelas', 'kelas.idkelas','=','mahasiswa.idkelas')
                            ->join('hari', 'hari.idhari','=','kelas_mhs.idhari')
                            ->join('ruangan', 'ruangan.idruangan','=','kelas_mhs.idruangan')
                            ->join('matkul', 'matkul.kode_matkul','=','kelas_mhs.kode_matkul')
                            ->select('kelas_mhs.idkelas_mhs','kelas_mhs_peserta.id_peserta', 'mahasiswa.nim', 'hari.nama_hari_en')
                            ->where('kelas_mhs.kode_matkul', $kmatkul)
                            ->where('tahun_ajaran.tahun', $tahun)
                            ->where('tahun_ajaran.idsmt', $idsmt)
                            ->where('hari.nama_hari_en', $hari)
                            ->where('kelas_mhs.idkelas', $kls)
                            ->where('kelas_mhs.idruangan', $idruangan)
                            ->where('kelas_mhs_peserta.nim', $data_nim);
                            
                            
                            if($cekNim->count() == 1) {
                                $idkelas_mhs = $cekNim->first()->idkelas_mhs;
                                $id_peserta = $cekNim->first()->id_peserta;
                                $cekAbsen = absen::where('idkelas_mhs', $idkelas_mhs)
                                ->where('id_peserta', $id_peserta)->count();

                                if($cekAbsen == 0) {
                                    $tambah = new absen;
                                    $tambah->idkelas_mhs = $idkelas_mhs;
                                    $tambah->id_peserta = $id_peserta;
                                    $tambah->save();
                                    $tidak_ada_jadwal = true;                  
                                }

                                $ambilAbsen = absen::where('idkelas_mhs', $idkelas_mhs)->where('id_peserta', $id_peserta)->first();
                                $id_absen = $ambilAbsen->id_absen;

                                $cekAbsen2 = absenDetailM::where('idabsen', $id_absen)
                                ->orderBy('created_at', 'desc')
                                ->where('created_at', 'like', date('Y-m-d')."%");
                                
                                $tambahAbsen = false;
                                // dd($cekAbsen2->count());
                                if($cekAbsen2->count() == 0) {
                                    $tambahAbsen = new absenDetailM;
                                    $tambahAbsen->idabsen = $id_absen;
                                    $tambahAbsen->idhari = $idhari;
                                    $tambahAbsen->jam_absen = date('H:i',$jam_sekarang);
                                    $tambahAbsen->idkehadiran = 1;
                                    $tambahAbsen->created_at = date('Y-m-d  H:i:s');
                                    $tambahAbsen->updated_at = date('Y-m-d  H:i:s');
                                    $tambahAbsen->save();
                                }else if($cekAbsen2->count() > 0) {
                                    $tanggalAbsen = strtotime(date('Y-m-d',strtotime($cekAbsen2->first()->updated_at)));
                                    $tanggalSekarang = strtotime(date('Y-m-d'));
                                    
                                    if($tanggalAbsen == $tanggalSekarang) {
                                        // return redirect()->back()->with('warning', '<h4>Anda telah melakukan absensi<br>untuk matakuliah '.$kmatkul.'-'.$nama_matkul_.'</h4>')->withInput();
                                        $telah_melakukan_absen = true;
                                    }else if($tanggalAbsen < $tanggalSekarang) {
                                        $tambahAbsen = new absenDetailM;
                                        $tambahAbsen->idabsen = $id_absen;
                                        $tambahAbsen->idhari = $idhari;
                                        $tambahAbsen->jam_absen = date('H:i',$jam_sekarang);
                                        $tambahAbsen->idkehadiran = 1;
                                        $tambahAbsen->created_at = date('Y-m-d  H:i:s');
                                        $tambahAbsen->updated_at = date('Y-m-d  H:i:s');
                                        $tambahAbsen->save();
                                    }


                                }

                                if($tambahAbsen){
                                    return redirect()->back()->with('success',"<h4>(".$data_nim.")"."<br>".ucwords($ambil_nim->first()->nama_mhs. "<br>Absen Berhasil</h4>"))->withInput();
                                }


                            }else {
                                $tidak_ada_jadwal = true;
                            }
                            
                            // dd($cekNim->kd_matkul);

                        }
                        
                    }
                    $tidak_ada_jadwal2 = false;
                }else {
                    $tidak_ada_jadwal2 = true;
                }
            }



            if ($absenPindah) {
                return redirect()->back()->with('success',"<h4>(".$data_nim.")"."<br>".ucwords($ambil_nim->first()->nama_mhs. "<br>Absen Berhasil</h4>"))->withInput();
            }

            if ($telah_melakukan_absen == true) {
                return redirect()->back()->with('warning', 'Anda telah melkukan absen')->withInput();
            }
            

            if ($tidak_ada_jadwal2) {
                return redirect()->back()->with('warning', 'Tidak ada jadwal yang ditemukan')->withInput();
            }

            

            if($tidak_ada_jadwal === true){
                return redirect()->back()->with('warning', 'Tidak ada matakuliah yang dikontrak')->withInput();
            }
            return redirect()->back()->with('warning', 'Tidak ada jadwal yang ditemukan')->withInput();

        
        }else {
            return redirect()->back()->with('warning','terjadi kesalahan')->withInput();
        }

        
    }

    public function updateAbsen(Request $request, $idkelas_mhs, $id_peserta)
    {

        $request->validate([
            'kehadiran' => 'required',
        ]);
        
        
        // try{
            if($request->session()->get('open')===true){
                $kehadiran = $request->kehadiran;
                $ambilAbsen = absen::where('idkelas_mhs', $idkelas_mhs)
                ->where('id_peserta', $id_peserta)->count();
                // dd($ambilAbsen);
                if($ambilAbsen==0){
                    $tambah = new absen;
                    $tambah->idkelas_mhs = $idkelas_mhs;
                    $tambah->id_peserta = $id_peserta;
                    $tambah->save();
                }

                $idabsen = absen::where('idkelas_mhs', $idkelas_mhs)
                ->where('id_peserta', $id_peserta)->first()->id_absen;
                $hari = hariM::where('nama_hari_en', date('l'))->first()->idhari;
                $jam = date('H:i');
                $tanggal = date('Y-m-d');

                $cekAbsen = absenDetailM::where('idabsen', $idabsen)
                ->where('updated_at', 'like', "$tanggal%")
                ->take(1);
                // dd($kehadiran);
                if($cekAbsen->count() == 1) {
                    $ambil = $cekAbsen->first();
                    $update = absenDetailM::where('idabsen', $idabsen)
                    ->where('created_at', $ambil->created_at)->update([
                        'idkehadiran' => $kehadiran, 
                    ]);

                    if($update) {
                        return redirect()->back()->with('toast_success', 'success');
                    }
                }else if($cekAbsen->count() == 0) {
                    $pindahjadwal = pindahjadwalM::where('idkelas_mhs', $idkelas_mhs)
                    ->where('tanggalubah', date('Y-m-d'));
                    
                    if($pindahjadwal->count() > 0) {
                        if($pindahjadwal->count() > 1) {
                            foreach ($pindahjadwal->get() as $item) {
                                $tambah = new absenDetailM;
                                $tambah->idabsen = $idabsen;
                                $tambah->idhari = hariM::where('nama_hari_en', date('l', strtotime($item->tanggalmulai)))->first()->idhari;
                                $tambah->jam_absen = $jam;
                                $tambah->idkehadiran = $kehadiran;
                                $tambah->created_at = $item->tanggalmulai;
                                $tambah->updated_at = date('Y-m-d H:i:s');
                                $tambah->save();
                            }
                            
                        }else {
                            
                            $tambah = new absenDetailM;
                            $tambah->idabsen = $idabsen;
                            $tambah->idhari = hariM::where('nama_hari_en', date('l', strtotime($pindahjadwal->first()->tanggalmulai)))->first()->idhari;
                            $tambah->jam_absen = $jam;
                            $tambah->idkehadiran = $kehadiran;
                            $tambah->created_at = $pindahjadwal->first()->tanggalmulai;
                            $tambah->updated_at = date('Y-m-d H:i:s');
                            $tambah->save();
                        }
                        
                    }else {
                        $tambah = new absenDetailM;
                        $tambah->idabsen = $idabsen;
                        $tambah->idhari = $hari;
                        $tambah->jam_absen = $jam;
                        $tambah->idkehadiran = $kehadiran;
                        $tambah->save();
                    }

                    if($tambah) {
                        return redirect()->back()->with('toast_success', 'success');
                    }

                }else {
                    return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
                }

            }else {
                return redirect()->back()->with('warning','Terjadi kesalahan')->withInput();
            }

            
        // }catch(\Throwable $th){
        //     return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        // }

    }

    public function keluar(Request $request)
    {
        $request->session()->flush();
        return redirect()->back()->withInput();
    }

    public function absensipeserta(Request $request)
    {
        if($request->session()->has('open')) {
            $request->session()->forget('open');

            return redirect('/');
        }
    }


   


    public function welcome()
    {
        $card = cardM::count();
        $ruangan = master::count();
        $matkul = matkulM::count();
        $mahasiswa = mahasiswa::count();
        return view('pages.pagesWelcome', [
            'card' => $card,
            'ruangan' => $ruangan,
            'matkul' => $matkul,
            'mahasiswa' => $mahasiswa,
        ]);
    }


    public function master(Request $request)
    {
        $post = empty($request->keyword)?"":$request->keyword;

        $ruangan = ruanganM::select('idruangan', 'nama_ruangan')->get();

        $master = master::join('ruangan','ruangan.idruangan','=','ruangan_master.idruangan')
        ->select('ruangan_master.*','ruangan.nama_ruangan')
        ->where('ruangan.nama_ruangan','LIKE',"$post%")->get();

        return view('pages.pagesMaster', [
            'master' => $master,
            'ruangan' => $ruangan,
        ]);
    }




    //matkul
    public function matkul(Request $request)
    {
        $post = empty($request->keyword)?"":$request->keyword;
        $matkul = matkulM::where(function($query) use ($post) {
            $query->where('kode_matkul', 'like', "$post%")
                  ->orWhere('nmatkul', 'like', "%$post%");
        })->paginate(15);

        $matkul->appends($request->only('keyword'));
        return view('pages.pagesMatkul', [
            'matkul' => $matkul,
        ]);
    }

    public function import()
    {
        try {
            DB::table('matkul')->truncate();

            $i = 1;
            $dataAll = fopen(public_path().'/csv/matkul.csv',"r");
            $column=fgetcsv($dataAll);
            while(!feof($dataAll)){
                $productsData[]=fgetcsv($dataAll);
            }
            // dd($productsData);
            foreach ($productsData as $key => $value) {
                $ex = explode(";",$value[0]);

                $id = $ex[0];
                $kd_matkul = $ex[1];
                $nama_matkul = $ex[2];
                $sks = $ex[3];
                
                
                $pcreate_data=array('id'=>$id,
                'kd_matkul'=>$kd_matkul,
                'nama_matkul'=>$nama_matkul,
                'sks'=>$sks,
                );

                // // dd($value[0]);
                DB::table('matkul')->insert($pcreate_data);

                

                if($i == 68){
                    return redirect('/matkul')->with('success', 'Import berhasil');
                }
                $i++;
            }
            


        } catch (\Throwable $th) {
            return redirect('/matkul');
        }
    }





    //jadwal
    public function jadwal(Request $request)
    {
        $ruangan = empty($request->ruangan)?"":$request->ruangan; 
        $hari = empty($request->hari)?"":$request->hari; 
        $kelas = empty($request->kelas)?"":$request->kelas; 
        $tahun = empty($request->tahun)?date('Y'):$request->tahun; 
        $semester = empty($request->semester)?"":$request->semester; 
        $keyword = empty($request->keyword)?"":$request->keyword; 

        $kelas_ = kelasM::select('idkelas',"nama_kelas")->orderBy('idkelas', 'asc')->get();
        $prodi_ = prodiM::orderBy('id_prodi', 'asc')->get();
        $semester_ = semesterM::orderBy('idsmt', 'asc')->get();
        $ruangan_ = ruanganM::select("idruangan","nama_ruangan")->get();
        $hari_ = DB::table('hari')->get();

        $tampil = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs', '=', 'kelas_mhs.idkelas_mhs')
            ->join('prodi', 'prodi.id_prodi', '=', 'tahun_ajaran.id_prodi')
            ->join('semester', 'semester.idsmt','=','tahun_ajaran.idsmt')
            ->join('hari', 'hari.idhari','=','kelas_mhs.idhari')
            ->join('matkul', 'matkul.kode_matkul','=','kelas_mhs.kode_matkul')
            ->join('kelas', 'kelas.idkelas','=','kelas_mhs.idkelas')
            ->join('ruangan', 'ruangan.idruangan','=','kelas_mhs.idruangan')
            ->select('kelas_mhs.idkelas_mhs','kelas_mhs.kode_matkul','matkul.nmatkul','kelas.nama_kelas','hari.nama_hari','kelas_mhs.jam_masuk','kelas_mhs.jam_keluar','ruangan.nama_ruangan', 'semester.namasmt')
            ->where('tahun_ajaran.tahun', $tahun)
            ->where('kelas_mhs.kode_matkul', 'like', "$keyword%")
            ->where('kelas_mhs.idruangan', 'like', "$ruangan%")
            ->where('kelas_mhs.idhari', 'like', "$hari%")
            ->where('tahun_ajaran.idsmt', 'like', "$semester%")
            ->where('kelas_mhs.idkelas', 'like', "$kelas%")->paginate(15)
        ;
        // $tampil = kelasjadwalM::join('jadwal', 'jadwal.idkelasjadwal','=','kelasjadwal.idkelasjadwal')
        // ->join('kelas', 'kelas.kelas', '=','kelasjadwal.kelas')
        // ->join('matkul', 'matkul.kd_matkul', '=', 'jadwal.kd_matkul')
        // ->select('jadwal.*', 'kelasjadwal.tahun', 'matkul.sks', 'kelas.kelas','matkul.nama_matkul')
        // ->where('jadwal.no_ruangan', 'like', "$ruangan%")
        // ->where('jadwal.hari', 'like', "$hari%")
        // ->where('kelasjadwal.kelas', 'like', "$kelas%")
        // ->where('kelasjadwal.tahun', 'like', "$tahun%")
        // ->where('jadwal.kd_matkul', 'like', "$keyword%")
        // ->paginate(15);

        $tampil->appends($request->only(['keyword', 'ruangan', 'hari', 'tahun', 'kelas' ,'limit']));

        return view('pages.pagesTampil', [
            'ruangan' => $ruangan_,
            'hari' => $hari_,
            'kelas' => $kelas_,
            'prodi' => $prodi_,
            'semester' => $semester_,
            'semester_' => $semester,
            'tampil' => $tampil,
            'tahun' => $tahun,
        ]);
    }

    public function laporan(Request $request)
    {
        $ruangan = empty($request->ruangan)?"":$request->ruangan; 
        $hari = empty($request->hari)?"":$request->hari; 
        $kelas = empty($request->kelas)?"":$request->kelas; 
        $tahun = empty($request->tahun)?date('Y'):$request->tahun; 
        $semester = empty($request->semester)?"":$request->semester; 
        $keyword = empty($request->keyword)?"":$request->keyword; 

        $kelas_ = kelasM::select('idkelas',"nama_kelas")->orderBy('idkelas', 'asc')->get();
        $prodi_ = prodiM::orderBy('id_prodi', 'asc')->get();
        $semester_ = semesterM::orderBy('idsmt', 'asc')->get();
        $ruangan_ = ruanganM::select("idruangan","nama_ruangan")->get();
        $hari_ = DB::table('hari')->get();

        $tampil = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs', '=', 'kelas_mhs.idkelas_mhs')
            ->join('prodi', 'prodi.id_prodi', '=', 'tahun_ajaran.id_prodi')
            ->join('semester', 'semester.idsmt','=','tahun_ajaran.idsmt')
            ->join('hari', 'hari.idhari','=','kelas_mhs.idhari')
            ->join('matkul', 'matkul.kode_matkul','=','kelas_mhs.kode_matkul')
            ->join('kelas', 'kelas.idkelas','=','kelas_mhs.idkelas')
            ->join('ruangan', 'ruangan.idruangan','=','kelas_mhs.idruangan')
            ->select('kelas_mhs.idkelas_mhs','kelas_mhs.kode_matkul','matkul.nmatkul','kelas.nama_kelas','hari.nama_hari','kelas_mhs.jam_masuk','kelas_mhs.jam_keluar','ruangan.nama_ruangan', 'semester.namasmt')
            ->where('tahun_ajaran.tahun', $tahun)
            ->where('kelas_mhs.kode_matkul', 'like', "$keyword%")
            ->where('kelas_mhs.idruangan', 'like', "$ruangan%")
            ->where('kelas_mhs.idhari', 'like', "$hari%")
            ->where('tahun_ajaran.idsmt', 'like', "$semester%")
            ->where('kelas_mhs.idkelas', 'like', "$kelas%")->paginate(15)
        ;

        $tampil->appends($request->only(['keyword', 'ruangan', 'hari','semester', 'tahun', 'kelas' ,'limit']));

        return view('pages.pagesLaporan', [
            'ruangan' => $ruangan_,
            'hari' => $hari_,
            'kelas' => $kelas_,
            'prodi' => $prodi_,
            'semester' => $semester_,
            'semester_' => $semester,
            'tampil' => $tampil,
            'tahun' => $tahun,
        ]);
    }

    public function ruangan($ruangan)
    {
        $hari = DB::table('hari')->get();
        return view('pages.pagesHari', [
            'ruangan' => $ruangan,
            'hari' => $hari,
        ]);

    }

    public function hari($ruangan,$hari)
    {
        $kelas = kelas::get();
        return view('pages.pagesKelas', [
            'ruangan' => $ruangan,
            'hari' => $hari,
            'kelas' => $kelas,
        ]);
    }

    public function tampil($ruangan,$hari,$kelas)
    {
        
    }

    public function ubah_matkul($id, $kd_matkul, $ruangan, $hari, $kelas)
    {
        
        return view('pages.pagesUbahJadwal',[
            'id' => $id,
            'kd_matkul' => $kd_matkul,
            'ruangan' => $ruangan,
            'hari' => $hari,
            'kelas' => $kelas,
        ]);
    }


    public function cariPost(Request $request)
    {
        if(empty($request->keyword)){
            return redirect('jadwal');
        }else {
            return redirect('cari/jadwal/'.$request->keyword);
        }
    }

    public function cari($key)
    {
        if(empty($key)) {
            return redirect('jadwal');
        }else {
            $tampil = jadwal::join('matkul', 'matkul.kd_matkul', '=', 'jadwal.kd_matkul')
            ->where('jadwal.kd_matkul', 'like', "$key%")
            ->select('jadwal.*', 'matkul.nama_matkul')
            ->get();

            return view('pages.pagesCari', [
                'tampil' => $tampil,
            ]);


        }
    }


    //master admin

    public function admin(Request $request)
    {
        
        // dd((int) filter_var("andi1092", FILTER_SANITIZE_NUMBER_INT));
        $post = empty($request->keyword)?"":$request->keyword;
        $data = adminM::where('nama', 'like', "$post%")->get();

        return view('pages.pagesAdmin', [
            'admin' => $data,
        ]);
    }


    public function siswa(Request $request)
    {
        $jurusan = empty($request->jurusan)?"":$request->jurusan; 
        $kelas = empty($request->kelas)?"":$request->kelas; 
        $tahun = empty($request->tahunmasuk)?"":$request->tahunmasuk; 
        $keyword = empty($request->keyword)?"":$request->keyword; 

        $kelas_ = kelasM::select('idkelas',"namakelas")->orderBy('idkelas', 'asc')->get();
        $kelas_ = jurusanM::select('idjurusan',"namajurusan")->orderBy('idjurusan', 'asc')->get();
        $tahun_ = siswaM::select('tahunmasuk')->orderBy('tahunmasuk','asc')->groupBy('tahunmasuk')->get();

        $tampil = siswaM::join('kelas','kelas.idkelas','=','siswa.idkelas')
        ->join('jurusan', 'jurusan.idjurusan','=','siswa.idjurusan')
        ->select('siswa.*','kelas.namakelas','jurusan.namajurusan')
        ->where('siswa.nis','like', "$keyword%")
        ->where('jurusan.idjurusan','like', "$jurusan%")
        ->where('kelas.idkelas','like', "$kelas%")
        ->where('siswa.tahunmasuk','like', "$tahun%")
        ->paginate(15);

        $tampil->appends($request->only('keyword', 'tahun', 'jurusan', 'kelas', 'limit'));
        
        return view('pages.pagesMahasiswa',[
            'Dtahun' => $tahun_,
            'tahun' => $tahun,
            'kelas' => $kelas_,
            'prodi' => $prodi_,
            'tampil' => $tampil,
            'keyword' => $keyword,
        ]);

        // $tampil = mahasiswa::

    }

    public function penyelenggara(Request $request){
        
        $semester = semesterM::get();

        $tampil = penyelenggaraM::join('kelas', 'kelas.idkelas','=','penyelenggara_smt.idkelas')
        ->select('penyelenggara_smt.*','kelas.nama_kelas')
        ->get();

        return view('pages.pagesPenyelenggara', [
            'penyelenggara' => $tampil,
            'semester' => $semester,
        ]);

    }

    public function updatepenyelenggara(Request $request, $id,$idkelas)
    {
        $request->validate([
            'penyelenggara' => 'required',
        ]);


        try{
            $idsmt = $request->penyelenggara;

            $update = penyelenggaraM::where('idkelas', $idkelas)
            ->where('id', $id)->update([
                'idsmt' => $idsmt,
            ]);

            if ($update) {
                return redirect()->back()->with('success', "berhasil diupdate")->withInput();
            }

        }catch(\Throwable $th){
            return redirect('/penyelenggara')->with('toast_error', 'Terjadi kesalahan');
        }

    }

    public function updatepenyelenggaratanggal(Request $request, $id,$idkelas)
    {
        $request->validate([
            'tanggal' => 'required',
        ]);


        try{
            $tanggal = $request->tanggal;

            $update = penyelenggaraM::where('idkelas', $idkelas)
            ->where('id', $id)->update([
                'tanggal' => $tanggal,
            ]);

            if ($update) {
                return redirect()->back()->with('success', "berhasil diupdate")->withInput();
            }

        }catch(\Throwable $th){
            return redirect('/penyelenggara')->with('toast_error', 'Terjadi kesalahan');
        }

    }
    





    public function faker()
    {
        // try {
            DB::table('mahasiswa')->truncate();
            $faker = Faker\Factory::create('id_ID');

        $tahun = ["2017","2018", "2019", "2020", "2021", "2022"];
        $jurusan = ["IF", "SI", "KA"];
        $klm = ["L", "P"];
        $kls = ["reguler", "karyawan", "eksekutif"];

        $nim="";
        $nama="";
        foreach ($tahun as $tampil) {
            $belakang = substr($tampil, 2);
            foreach ($jurusan as $jr) {
                if($jr == "IF") {
                    $nim = "12".$belakang;
                }else if($jr=="SI"){
                    $nim = "32".$belakang;
                }else if($jr=="KA"){
                    $nim = "42".$belakang;
                }

                for($i=1; $i <=10; $i++){
                    if($i<10) {
                        $nim2 = $nim."00".$i;
                    }else {
                        $nim2 = $nim."0".$i;
                    }

                    $nama = $faker->name;
                    $prodi = $jr;
                    $kelamin = $klm[rand(0,1)];
                    $kelas = $kls[rand(0,2)];

                    DB::table('mahasiswa')->insert([
                        'nim' => $nim2,
                        'nama' => $nama,
                        'prodi' => $prodi,
                        'kelamin' => $kelamin,
                        'kelas' => $kelas,
                        'tahun' => $tampil,
                    ]);

                }
            }
        };
        // } catch (\Throwable $th) {
            
        // }
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
     * @param  \App\Models\perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function show(perangkat $perangkat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function edit(perangkat $perangkat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, perangkat $perangkat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function destroy(perangkat $perangkat)
    {
        //
    }
}
