<div>
    <form>
        <div class="modal-body">
        
        <div class="form-group">
            <label for="">MATA KULIAH *</label>
            <input type="text" wire:model="cari_matkul" wire:change="cekAll" placeholder="cari berdasarkan kode matkul" wire:keyup="cek_mk" id="" class="form-control rounded-0 text-uppercase" style="background: rgba(181, 255, 174, 0.842)">
            <select wire:model="matkul" wire:change="cekAll"  class="form-control rounded-0 @error('matkul')
                is-invalid
            @enderror">
                @foreach ($opt_matkul as $mk)
                    @php
                        $ex = explode("-", $mk);
                        
                    @endphp
                    
                    <option value="{{ ($ex[0]=="silahkan pilih")?'':$ex[0] }}" {{($ex[0]=="silahkan pilih")?'select':''}}>{{ $mk }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="">RUANGAN *</label>
            <select wire:model="ruangan" wire:change="cekAll" id="" class="form-control @error('ruangan')
                is-invalid
            @enderror">
                <option value="">-- Pilih Ruangan --</option>
                @php
                    $opt_ruang = DB::table('master')->select('no_ruangan')->get();
                @endphp
                @foreach ($opt_ruang as $item)
                    <option value="{{$item->no_ruangan}}">{{$item->no_ruangan}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="">KELAS *</label>
            <select wire:model="kelas" wire:change="cekAll" id="" class="form-control @error('kelas')
                is-invalid
            @enderror">
                <option value="">-- Pilih Kelas --</option>
                @php
                    $opt_kelas = DB::table('kelas')->select('kelas')->get();
                @endphp
                @foreach ($opt_kelas as $item)
                    <option value="{{$item->kelas}}">{{ strtoupper($item->kelas) }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="">HARI *</label>
            <select wire:model="hari" wire:change="cekAll" id="" class="form-control @error('hari')
                is-invalid
            @enderror">
                <option value="">-- Pilih Hari --</option>
                @php
                    $opt_kelas = DB::table('hari')->select('en','idn')->get();
                @endphp
                @foreach ($opt_kelas as $item)
                    <option value="{{$item->en}}">{{ strtoupper($item->idn) }}</option>
                @endforeach
            </select>
        </div>


        @if ($buka===true)
        @if (session()->has('message'))
            <div class="alert alert-success text-center">
                {{ session('message') }}
            </div>
        @endif
        
        @elseif($buka === false)
        @if (session()->has('message'))
            <div class="alert alert-danger text-center">
                {{ session('message') }}
            </div>
        @endif

        @endif


        @if ($buka==true)

        <div class="form-group">
            <label for="">JAM *</label>
            <select wire:model="jam" id="" class="form-control @error('jam')
                is-invalid
            @enderror">
                @foreach ($opt_jam as $item)
                    <option value="{{$item}}">{{ strtoupper($item) }}</option>
                @endforeach
            </select>
        </div>
            
        @endif
        

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" wire:click="store" class="btn btn-success">Ubah Jadwal</button>
    </div>
    </form>
</div>
