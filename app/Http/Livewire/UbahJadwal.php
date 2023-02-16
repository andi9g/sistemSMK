<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\matkulM;
use App\Models\jadwal;
use App\Models\kelas;

class UbahJadwal extends Component
{

    public $isi;
    public $cari_matkul;
    public $opt_ruang,$opt_matkul;

    //hasil
    public $id_matkul;
    public $kd_matkul;
    public $matkul, $ruangan, $kelas, $hari, $jam;

    public $buka = false;

    public $opt_jam = [];

    public function mount($kd_matkul)
    {

        if(!isset($this->cari_matkul) && empty($this->cari_matkul)){
            $this->opt_matkul = ["-- none --"];
        }
        $this->cari_matkul = $this->kd_matkul; 
        if(empty($this->cari_matkul)){
            $this->opt_matkul = ["-- none --"];
        }else{
            
            $matkul = matkulM::where('kd_matkul', $this->cari_matkul)->get();
            
            if(count($matkul)==1) {
                $i = 1;
                $this->opt_matkul = ["silahkan pilih"];
                foreach ($matkul as $mk) {
                    $this->opt_matkul[$i] = $mk->kd_matkul."-".$mk->nama_matkul;
                    $i++;
                }
            }else {
                $this->opt_matkul = ["-- none --"];
                $this->matkul = NULL;
            }

        }  
    }


    public function render()
    {
        return view('livewire.ubah-jadwal');
    }

    
    public function cek_mk()
    {
        
        if(empty($this->cari_matkul)){
            $this->opt_matkul = ["-- none --"];
        }else{
            
            $matkul = matkulM::where('kd_matkul', 'like', $this->cari_matkul."%")->get();
            
            if(count($matkul)>0) {
                $i = 1;
                $this->opt_matkul = ["silahkan pilih"];
                foreach ($matkul as $mk) {
                    $this->opt_matkul[$i] = $mk->kd_matkul."-".$mk->nama_matkul;
                    $i++;
                }
            }else {
                $this->opt_matkul = ["-- none --"];
                $this->matkul = NULL;
            }

        }
    }


    public function cekAll()
    {
        if(isset($this->matkul) && isset($this->ruangan) && isset($this->kelas) && isset($this->hari)){
            
            $cek = jadwal::where('ruangan', $this->ruangan)
            ->where('hari', $this->hari)
            ->where('kelas', $this->kelas)
            ->count();

            if ($cek<5) {

                $ambilKelas = kelas::where('kelas', $this->kelas)->first();

                $jam_mulai = $ambilKelas->jam_mulai;
                $jam_selesai = $ambilKelas->jam_selesai;
                

                $str_mulai = strtotime(date('H:i', strtotime($jam_mulai)));
                $str_selesai = strtotime(date('H:i', strtotime($jam_selesai))) - (60*45);
                $menit = 60 * 5;

                $this->opt_jam = ['-- Silahkan Pilih --'];
                
                for($mulai = $str_mulai; $mulai <= $str_selesai; $mulai = $mulai + $menit) {

                    $ambilJ = jadwal::join('matkul','matkul.kd_matkul','=','jadwal.kd_matkul')
                        ->where('jadwal.ruangan', $this->ruangan)
                        ->where('jadwal.hari', $this->hari)
                        ->where('jadwal.kelas', $this->kelas)
                        ->select('jadwal.*','matkul.sks')
                        ->get();

                    $ii=0;
                    $arrayNone = [];
                    foreach ($ambilJ as $jwl) {
                        $sks = $jwl->sks * (60 * 45);
                        
                        $menit2 = 60 * 5;
                        $str_awal = (strtotime(date('H:i', strtotime($jwl->jam))));
                        $str_akhir = (strtotime(date('H:i', strtotime($jwl->jam)))) + $sks;

                        

                        for($mulai2 = $str_awal; $mulai2 <= $str_akhir; $mulai2 = $mulai2 + $menit2) {
                            $arrayNone[$ii] = $mulai2;
                            $ii++;
                        }   
                    }
                    //  dd($ii);
                    if(in_array($mulai, $arrayNone)){
                    }else {
                        $this->opt_jam[] = date('H:i', $mulai);
                    }
                }
                session()->flash('message', 'Tersedia');
                $this->buka = true;
            }else {
                session()->flash('message', 'Ruangan Penuh, MAX 4 matkul terhadap kelas,hari dan ruangan');
                $this->buka = false;
            }
        }
    }


    public function store()
    {

        $this->validate([
            'matkul' => 'required|string',
            'ruangan' => 'required',
            'kelas' => 'required',
            'hari' => 'required',
            'jam' => 'required',
        ],[
            'required' => 'Data tidak boleh kosong!',
        ]);
        
        if(isset($this->matkul) && isset($this->ruangan) && isset($this->kelas) && isset($this->hari) && isset($this->jam)){
        $cek = jadwal::where('ruangan', $this->ruangan)
            ->where('hari', $this->hari)
            ->where('kelas', $this->kelas)
            ->count();

        if ($cek<5) {

            $cekcek = jadwal::where('ruangan', $this->ruangan)
            ->where('hari', $this->hari)
            ->where('kelas', $this->kelas)
            ->where('kd_matkul', $this->matkul)
            ->count();

            if($cekcek === 1) {
                $cekUpdate = jadwal::where('id', $this->id_matkul)->count();
                if($cekUpdate == 1){
                    $update = jadwal::where('id', $this->id_matkul)->update([
                        'kd_matkul' => $this->matkul,
                        'kelas' => $this->kelas,
                        'hari' => $this->hari,
                        'ruangan' => $this->ruangan,
                        'jam' => $this->jam,
                    ]);
                    if($update) {
                        $this->buka = false;
                        return redirect('jadwal')->with('success', 'Berhasil mengubah data');
                    }
                }else {
                    return redirect('jadwal')->with('toast_error', 'Terjadi kesalahan!');
                }

                

            }

        }else {
            session()->flash('message', 'Ruangan Penuh, MAX 4 matkul terhadap kelas,hari dan ruangan');
            $this->buka = false;
        }

        }

    }
    
}
