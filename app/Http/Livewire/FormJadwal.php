<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\matkulM;
// use App\Models\jadwal;
use App\Models\kelasM;
use App\Models\kelasmhsM;
use App\Models\master;

class FormJadwal extends Component
{

    public $nidn, $ketiknidn;

    public $datanidn;
    public $datamatkul;
    public $tahun;

    public function mount()
    {
        // dd($this->nidn);
    }

    public function pilih()
    {
        $tahun = date('Y');

        if(empty($this->nidn)){
            $this->datamatkul = kelasmhsM::join('tahun_ajaran', 'tahun_ajaran.idkelas_mhs','=','kelas_mhs.idkelas_mhs')
            ->join('matkul', 'matkul.kode_matkul', '=', 'kelas_mhs.kode_matkul')
            ->join('penyelenggara_smt', 'penyelenggara_smt.idsmt', '=', 'tahun_ajaran.idsmt')
            ->where('tahun_ajaran.tahun', $tahun)
            ->where('kelas_mhs.nidn', $this->nidn)
            ->groupBy('kelas_mhs.kode_matkul')
            ->groupBy('matkul.nmatkul')
            ->select('kelas_mhs.kode_matkul', 'matkul.nmatkul')
            ->get();
        }else {
            $datamatkul=[];
        }
    }

    public function render()
    {  
        // $nidn = empty($this->nidn)?"":$this->nidn;
        
        
        

        return view('livewire.form-jadwal',[
            'datamatkul' => $this->datamatkul,
            'datanidn' => $this->datanidn,
        ]);
    }

    

}
