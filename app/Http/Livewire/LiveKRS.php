<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\cardM;
use App\Models\krsM;

class LiveKRS extends Component
{

    public $nim, $tahun;

    public $cari_nim;
    public $opt_nim;
    public function mount()
    {
        $this->tahun = date('Y');
        if(empty($this->nim)){
            $this->opt_nim = ["-Silahkan Cari"];
        }
    }

    public function render()
    {
        return view('livewire.live-k-r-s');
    }

    public function cari_nim()
    {
        $cariNim = cardM::join('mahasiswa', 'mahasiswa.nim', '=', 'card.nim')
                ->select('card.nim', 'mahasiswa.nama')
                ->where('card.nim', 'like', $this->cari_nim."%")
                ->get();
        if (count($cariNim)) {
            if(!empty($this->cari_nim)){
                $this->opt_nim = ["-Silahkan Pilih"];
                foreach ($cariNim as $nim) {
                    $cek = krsM::where('nim', $nim->nim)->where('tahun', $this->tahun)->count();
                    if($cek == 0){
                        $this->opt_nim[] = $nim->nim."-".$nim->nama;
                    }
    
                }
            }
        }else {
            $this->opt_nim = ["-Silahkan Cari"];
        }
    }


    public function store()
    {
        $this->validate([
            'tahun' => 'required',
            'nim' => 'required',
        ]);

        try{
            $cek = krsM::where('nim', $this->nim)->where('tahun', $this->tahun)->count();
            if($cek == 0) {
                $tambah = new krsM;
                $tambah->tahun = $this->tahun;
                $tambah->nim = $this->nim;
                $tambah->save();
                if($tambah) {
                    return redirect('mahasiswa/krs')->with('success', 'KRS '.$this->nim.' berhasil ditambahkan');
                }
            }else {
                session()->flash('error', 'KRS telah terdaftar');
            }
        }catch(\Throwable $th){
            return redirect('mahasiswa/krs')->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
