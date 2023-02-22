<?php

namespace App\Http\Controllers;

use App\Models\kelasM;
use App\Models\siswaM;
use App\Models\jurusanM;
use App\Models\absenM;
use App\Models\pengaturanM;
use Illuminate\Http\Request;
use DateTime, DateInterval, DatePeriod;
use Session;
use PDF;

class cetakC extends Controller
{

    public function index(Request $request)
    {
        // $token = $request->session()->token();
        // dd(csrf_token());
        $jurusan = jurusanM::get();
        $kelas = kelasM::get();

        return view('pages.pagesLaporan', [
            'jurusan' => $jurusan,
            'kelas' => $kelas,
        ]);
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal1'=>'required|date',
            'tanggal2'=>'required|date'
        ], [
            'required' => 'Tanggal tidak boleh kosong!'
        ]);

        $tanggal1 = $request->tanggal1;
        $tanggal2 = $request->tanggal2;
        $jurusan = ($request->jurusan=="all")?"":$request->jurusan;
        $kelas = ($request->kelas=="all")?"":$request->kelas;

        $t1 = new DateTime($tanggal1 );
        $t2 = new DateTime($tanggal2);
        $end = $t2->modify( '+1 day' );
        

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($t1, $interval ,$end);
        
        
        foreach($daterange as $date){
            $hari = \Carbon\Carbon::parse($date->format('Y-m-d'))->isoFormat('dddd');

            if($hari === 'Minggu' || $hari === 'Sabtu'){
            }else {
                $tanggal[] = $date->format('Y-m-d');
                $tanggaltampil[] = $date->format('d');

            }
        }
        $jumlah = count($tanggal);

        $jur = jurusanM::where('idjurusan', 'like', "$jurusan%")->get();
        $d1 = 0;
        $d2 = 0;
        $d3 = 0;
        $d4 = 0;
        $data = [];
        $dket = [];
        $dsiswa = [];
        $dkelas = [];
        foreach ($jur as $j) {

            $kel = kelasM::where('idkelas', 'like', "$kelas%")->orderBy('namakelas', 'ASC')->get();

            $dkelas = [];
            foreach ($kel as $k) {
                
                $siswa = siswaM::where('idjurusan', $j->idjurusan)
                ->where('idkelas', $k->idkelas)
                ->orderBy('namasiswa', 'ASC')
                ->get();

                $dsiswa = [];
                foreach ($siswa as $s) {
                    $dket = [];
                    foreach ($tanggal as $t) {
                        $absen = absenM::where('nis', $s->nis)
                        ->where('tanggal', $t);
                        $keterlambatan = false;
                        if($absen->count()>0) {
                            $ket = $absen->first()->ket;
                            if ($ket=='H') {
                                $pengaturan = pengaturanM::first();
                                $jm = empty($pengaturan->jammasuk)?"07:30":$pengaturan->jammasuk;
                                $kt = empty($pengaturan->keterlambatan)?"0":$pengaturan->keterlambatan;
                                $ex = strtotime(date('H:i:s', strtotime('+'.$kt.' min', strtotime($jm))));
                                if(strtotime($absen->first()->jammasuk) > $ex) {
                                    $keterlambatan = true;
                                }
                            }
                        }else {
                            $ket = "A";
                        }
                        $dket[] = [
                            'tanggalabsen' => $t,
                            'ket' => $ket,
                            'keterlambatan' => $keterlambatan,
                        ];
                    }

                    $dsiswa[$d3] = [
                        'namasiswa' => $s->namasiswa,
                        'jk' => $s->jk,
                        'absen' => $dket,
                    ];
                    $d3++;
                }
                $dkelas[$d2] = [
                    'namakelas' => $k->namakelas,
                    'siswa' => $dsiswa,
                ];

                $d2++;
            }

            $data[$d1] = collect([
                'jurusan' => $j->namajurusan,
                'kelas' => $dkelas,
            ]);
            $d1++;
        }

        // dd($tanggaltampil);


        $pdf = PDF::loadview('laporan.laporan',[
            'data' => $data,
            'tanggal' => $tanggal,

            'tanggal1' => $tanggal1,
            'tanggal2' => $tanggal2,
            'tanggaltampil' => $tanggaltampil,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan.pdf');
    }
}
