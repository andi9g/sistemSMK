<?php

namespace App\Http\Controllers;

use App\Models\kelasmhsM;
use App\Models\master;
use App\Models\kelasM;
use App\Models\matkulM;
use App\Models\adminM;
use App\Models\mahasiswa;
use App\Models\semesterM;
use App\Models\prodiM;
use App\Models\cardM;
use App\Models\absen;
use App\Models\hariM;
use App\Models\ruanganM;
use App\Models\pesertamhsM;
use App\Models\penyelenggaraM;
use App\Models\tahunajaranM;
use App\Models\absenDetailM;
use Illuminate\Http\Request;
use PDF;

class cetakC extends Controller
{
    public function cetak(Request $request, $idkelas_mhs)
    {
        
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
        $tanggal_awal = strtotime(penyelenggaraM::where('idsmt', $idsmt)->where('idkelas',$idkelas)->first()->tanggal);
        // dd(date('Y-m-d',$tanggal_awal));
        $tanggal_akhir = strtotime(date('Y-m-d', strtotime('+16 week', $tanggal_awal)));
        // dd($tanggal_awal."   ".$tanggal_akhir);
        $tanggal = [];
        $tanggal2 = [];

        for($i = $tanggal_awal;$i<=$tanggal_akhir; $i += 86400) {
            $cekTgl = date('l', $i);
            if($cekTgl == $hari) {
                $tanggal[] = date('Y-m-d', $i);
                $tanggal2[] = $cekTgl;
            }
        }

        // dd($tanggal);
        $peserta = pesertamhsM::join('mahasiswa','mahasiswa.nim','=','kelas_mhs_peserta.nim')
        ->join('kelas_mhs', 'kelas_mhs.idkelas_mhs','=','kelas_mhs_peserta.idkelas_mhs')
        ->select('mahasiswa.nama_mhs','kelas_mhs_peserta.id_peserta', 'kelas_mhs.idkelas_mhs', 'mahasiswa.nim')
        ->where('kelas_mhs_peserta.idkelas_mhs', $idkelas_mhs)
        ->get();



        $pdf = PDF::loadview('laporan.laporan',[
            'data' => $data,
            'tanggal' => $tanggal,
            'peserta' => $peserta,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan.pdf');
    }
}
