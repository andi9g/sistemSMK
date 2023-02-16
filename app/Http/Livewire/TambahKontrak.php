<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\kontrakM;
use App\Models\cardM;
use App\Models\mahasiswa;
use App\Models\jadwal;
use App\Models\matkulM;

class TambahKontrak extends Component
{



    public $cari_nim, $cari_matkul;


    public $nim,$nama, $nama_matkul, $kd_matkul;

    public $opt_nim, $opt_matkul;

    public function mount()
    {
        if(empty($this->nim)){
            $this->opt_nim = ["-Silahkan Cari"];
            $this->opt_matkul = ["-Silahkan Cari"];
        }
    }

    public function render()
    {
        return view('livewire.tambah-kontrak');
    }


    public function cari_nim()
    {
        $cariNim = cardM::join('mahasiswa', 'mahasiswa.nim', '=', 'card.nim')
                ->select('card.nim', 'mahasiswa.nama')
                ->where('card.nim', 'like', $this->cari_nim."%")
                ->get();
        if (count($cariNim)) {
            $this->opt_nim = ["-Silahkan Pilih"];
            foreach ($cariNim as $nim) {
                $this->opt_nim[] = $nim->nim."-".$nim->nama;
            }
        }else {
            $this->opt_nim = ["-Silahkan Cari"];
        }
    }

    public function cari_matkul()
    {
        $carimatkul = jadwal::join('matkul', 'matkul.kd_matkul', '=', 'jadwal.kd_matkul')
                ->select('jadwal.kd_matkul', 'matkul.nama_matkul')
                ->where('jadwal.kd_matkul', 'like', $this->cari_matkul."%")
                ->get();
        if (count($carimatkul)) {
            $this->opt_matkul = ["-Silahkan Pilih"];
            foreach ($carimatkul as $matkul) {
                $this->opt_matkul[] = $matkul->kd_matkul."-".$matkul->nama_matkul;
            }
        }else {
            $this->opt_matkul = ["-Silahkan Cari"];
        }
    }

    public function store()
    {
        $this->validate([
            'kd_matkul' => 'required',
            'nim' => 'required',
        ]);

        try {
            $cek = kontrakM::where('nim', $this->nim)->where('kd_matkul', $this->kd_matkul)->count();
            if($cek ===1) {
                session()->flash('message', 'matakuliah telah dikontrak');
            }else {
                $tambah = new kontrakM;
                $tambah->nim = $this->nim;
                $tambah->kd_matkul = $this->kd_matkul;
                $tambah->save();

                if ($tambah) {
                    return redirect('kontrak')->with('toast_success', 'data berhasil ditambahkan');
                }
            }
        } catch (\Throwable $th) {
        }
    }

}
