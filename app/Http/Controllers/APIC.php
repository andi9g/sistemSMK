<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cardM;
use App\Models\krsM;
use App\Models\kelas;
use App\Models\kelasjadwalM;
use App\Models\krsMatkulM;

class APIC extends Controller
{
    public function APIMatkul($tahun)
    {

        $url = "https://api2.sttindonesia.ac.id/v2/perkuliahan/jadwalkuliah";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Basic MTIxODAxMjphbmRpNDIwNDE1",
        "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '{"ta":"2021","idsmt":"2","idprodi":"12"}';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        echo $resp;

        // $kmatkul_ = ["IF1201","IF1302", "IF1403","IF1204","IF1501"];
        // $reguler_jam_mulai = ["08:00","10:00", "12:00","13:30","14:30"];
        // $reguler_jam_selesai = ["09:50","11:50", "13:20","14:20","15:30"];
        // $karyawan_jam_mulai = ["16:00","17:30", "19:00","20:30","22:00"];
        // $karyawan_jam_selesai = ["17:20","18:50", "20:20","21:50","23:00"];
        // $eksekutif_jam_mulai = ["08:00","10:00", "12:00","13:30","14:30"];
        // $eksekutif_jam_selesai = ["09:50","11:50", "13:20","14:20","15:30"];
        // $json_encode = [];
        // $hari = date('l');
        // $tahun = $tahun;    
        // $kelas_ = kelas::select('kelas')->get();
        
        // foreach ($kelas_ as $kls) {
        //     $kelas = $kls->kelas;
        //     if($kelas == "reguler") {
        //         for ($i=0; $i < 5; $i++) { 
        //             $json_obj = '{
        //                 "kelas":"'.$kelas.'",
        //                 "tahun":"'.$tahun.'",
        //                 "kmatkul":"'.$kmatkul_[$i].'",
        //                 "hari":"'.$hari.'",
        //                 "jam_mulai":"'.$reguler_jam_mulai[$i].'",
        //                 "jam_selesai":"'.$reguler_jam_selesai[$i].'",
        //                 "no_ruangan":"101"
        //             }';
        //             $json_encode[] = json_decode($json_obj);
        //         }
        //     }else if($kelas == "karyawan") {
        //         for ($i=0; $i < 5; $i++) { 
        //             $json_obj = '{
        //                 "kelas":"'.$kelas.'",
        //                 "tahun":"'.$tahun.'",
        //                 "kmatkul":"'.$kmatkul_[$i].'",
        //                 "hari":"'.$hari.'",
        //                 "jam_mulai":"'.$karyawan_jam_mulai[$i].'",
        //                 "jam_selesai":"'.$karyawan_jam_selesai[$i].'",
        //                 "no_ruangan":"101"
        //             }';
        //             $json_encode[] = json_decode($json_obj);
        //         }
        //     }else if($kelas == "eksekutif") {
        //         for ($i=0; $i < 5; $i++) { 
        //             $json_obj = '{
        //                 "kelas":"'.$kelas.'",
        //                 "tahun":"'.$tahun.'",
        //                 "kmatkul":"'.$kmatkul_[$i].'",
        //                 "hari":"'.$hari.'",
        //                 "jam_mulai":"'.$eksekutif_jam_mulai[$i].'",
        //                 "jam_selesai":"'.$eksekutif_jam_selesai[$i].'",
        //                 "no_ruangan":"101"
        //             }';
        //             $json_encode[] = json_decode($json_obj);
        //         }
        //     }
        // }

        // $json_encode = array_unique($json_encode, SORT_REGULAR);
        // echo json_encode($json_encode);


    }

    public function krsmatkul($id)
    {
        $kmatkul_ = ["MK","IF1201","IF1302", "IF1403","IF1204","IF1501"];
        $tahun = date('Y');
        $json_encode = [];

        for ($i=0; $i < 20; $i++) { 
            $id_krs = rand(1, 5);
            $id_penyelenggara = rand(1, 5);

            $kode = (String) ($kmatkul_[$id_penyelenggara]);
            $json_obj = '{"idpenyelenggara":'."$id_penyelenggara".', "id_krs":'."$id_krs".', "kmatkul":"'.$kode.'", "tahun":'."$tahun".'}';
            $json_encode[] = json_decode($json_obj);
            $json_encode = array_unique($json_encode, SORT_REGULAR);
        }

        return $json_encode;
    }

    public function krs_matkul(Request $request, $id)
    {

        echo json_encode($this->krsmatkul($id));
    }

}
