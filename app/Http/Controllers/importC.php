<?php

namespace App\Http\Controllers;

use App\Models\semesterM;
use App\Models\matkulM;
use App\Models\kelasM;
use App\Models\kelasmhsM;
use App\Models\pesertamhsM;
use App\Models\tahunajaranM;
use App\Models\mahasiswa;
use App\Models\ruanganM;
use Illuminate\Http\Request;

class importC extends Controller
{
    public function importJadwal(Request $request)
    {
        $request->validate([
            'ta' =>'required',
            'idprodi' =>'required',
            'idsmt' =>'required',
        ]);

        try{
        $ta = $request->ta;
        $idprodi = $request->idprodi;
        $idsmt = $request->idsmt;
        $kelas = $request->kelas;

        $url = "https://api2.sttindonesia.ac.id/v2/perkuliahan/jadwalkuliah";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '{"ta":"'.$ta.'","idsmt":"'.$idsmt.'","idprodi":"'.$idprodi.'"}';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $data = json_decode(curl_exec($curl), TRUE);
        curl_close($curl);

        $data = json_encode($data["record"]);
        $data = json_decode($data);

        if(count($data)==0) {
            return redirect()->back()->with('warning', 'Tidak ada data yang ditemukan')->withInput();
        }

        // ta       = tahun ajaran
        // idprodi  = id prodi
        // idsmt    = id semester

            foreach ($data as $item) {
    
                if($item->jumlah_peserta_kelas > 0) {
                    //matkul
                    $cekMatkul = matkulM::where('kode_matkul', $item->kode_matkul)->count();
                    if($cekMatkul === 0){
                        $tambah = new matkulM;
                        $tambah->kode_matkul = $item->kode_matkul;
                        $tambah->nmatkul = $item->nmatkul;
                        $tambah->save();
                    }
        
                    //tahun ajaran
                    $cekTahunAjaran = tahunajaranM::where('tahun', date('Y'))
                    // $cekTahunAjaran = tahunajaranM::where('tahun', $ta)
                                        ->where('idsmt', $idsmt)
                                        ->where('id_prodi', $idprodi)
                                        ->where('idkelas_mhs', $item->idkelas_mhs)
                                        ->count();
                    if($cekTahunAjaran === 0) {
                        $tambah = new tahunajaranM;
                        $tambah->idsmt = $idsmt;
                        $tambah->id_prodi = $idprodi;
                        $tambah->idkelas_mhs = $item->idkelas_mhs;
                        $tambah->tahun = date('Y');
                        // $tambah->tahun = $ta;
                        $tambah->save();
                    }

                    $cekTahunAjaran2 = tahunajaranM::where('tahun', date('Y'))
                    // $cekTahunAjaran2 = tahunajaranM::where('tahun', $ta)
                                        ->where('idsmt', $idsmt)
                                        ->where('id_prodi', $idprodi)
                                        ->where('idkelas_mhs', $item->idkelas_mhs)
                                        ->first();
                    $idtahunajaran = $cekTahunAjaran2->idtahunajaran;
                    //ruangan
                    $cekRuangan = ruanganM::where('nama_ruangan', $item->namaruang)->count();
                    if($cekRuangan === 0) {
                        $tambah = new ruanganM;
                        $tambah->nama_ruangan = $item->namaruang;
                        $tambah->save();
                    }
                    $cekRuangan2 = ruanganM::where('nama_ruangan', $item->namaruang)->first();
                    $idruangan = $cekRuangan2->idruangan;
    
                    //kelas mhs
                    $cekkelasmhs = kelasmhsM::where('idkelas_mhs', $item->idkelas_mhs)->count();
                    if($cekkelasmhs === 0) {
                        $tambah = new kelasmhsM;
                        $tambah->idkelas_mhs = $item->idkelas_mhs;
                        $tambah->idkelas = $item->idkelas;
                        $tambah->idhari = $item->hari;
                        $tambah->jam_masuk = $item->jam_masuk;
                        $tambah->jam_keluar = $item->jam_keluar;
                        $tambah->kode_matkul = $item->kode_matkul;
                        $tambah->nama_dosen = $item->nama_dosen;
                        $tambah->nidn = $item->nidn;
                        $tambah->idruangan = $idruangan;
                        $tambah->namakelas = $item->namakelas;
                        $tambah->idtahunajaran = $idtahunajaran;
                        $tambah->save();
                    }
    
    
                    //mahasiswa
                    $idkelas_mhs = $item->idkelas_mhs;
                    $url2 = "https://api2.sttindonesia.ac.id/v2/perkuliahan/jadwalkuliah/".$idkelas_mhs."/peserta";
                    $ambil_json = file_get_contents($url2);
                    $json = json_decode($ambil_json, TRUE);
                    $json = json_encode($json["record"]);
                    $json_peserta = json_decode($json);
                    
                    if(count($json_peserta) > 0) {
                        foreach ($json_peserta as $peserta) {
                            //tambah mahasiswa
                            $cekMahasiswa = mahasiswa::where('nim', $peserta->nim)->count();
                            if($cekMahasiswa == 0) {
                                $tambah = new mahasiswa;
                                $tambah->nim = $peserta->nim;
                                $tambah->nirm = $peserta->nirm;
                                $tambah->nama_mhs = $peserta->nama_mhs;
                                $tambah->jk = $peserta->jk;
                                $tambah->id_prodi = $idprodi;
                                $tambah->idkelas = $item->idkelas;
                                $tambah->tahun_masuk = $peserta->tahun_masuk;
                                $tambah->save();
                            }
    
                            //tambah peseta mhs
                            $pesertamhs = pesertamhsM::where('idkelas_mhs', $idkelas_mhs)
                                            ->where('nim', $peserta->nim)->count();
                            if($pesertamhs === 0) {
                                $tambah = new pesertamhsM;
                                $tambah->idkelas_mhs = $idkelas_mhs;
                                $tambah->idkrsmatkul = $peserta->idkrsmatkul;
                                $tambah->nim = $peserta->nim;
                                $tambah->sah = $peserta->sah;
                                $tambah->save();
                            }
                            
                        }
                    }
    
                }
    
                
            }
            return redirect()->back()->with('success', 'Data berhasil di import')->withInput();
        }catch(\Throwable $th){
            return redirect('/jadwal')->with('toast_error', 'Terjadi kesalahan');
            // dd($th);
        }

        

        // echo json_encode($data["record"]);


    }
}
