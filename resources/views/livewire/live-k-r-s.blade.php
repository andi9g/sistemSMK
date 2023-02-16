<div>
    <form>
        <div class="modal-body">
        
            <div class="form-group">
                <label for="">Tahun</label>
                <select name="tahun" wire:model="tahun" wire:change="cari_nim" id="" class="form-control">
                    @for ($i = 2020; $i <= date('Y'); $i++)
                    <option value="{{$i}}" @if ($i == date('Y'))
                        selected
                    @endif>{{$i}}</option>
                    @endfor
                </select>
                
            </div>

            <div class="form-group">
                <label for="">Masukan NIM</label>
                <input type="text" wire:model="cari_nim" wire:keyup="cari_nim" class="form-control rounded-0 text-uppercase" style="background: rgba(9, 255, 0, 0.212)" placeholder="Cari berdasarkan nim">
                <select name="nim" wire:model="nim" class="form-control rounded-0">
                    @foreach ($opt_nim as $item)
                        @php
                            $ambil = explode("-", $item);
                            $nim = empty($ambil[0])?"":$ambil[0];
                            $nama = empty($ambil[1])?"":$ambil[1];
                        @endphp
                        <option value="{{$nim}}">{{$nim}}@if (!empty($nim))
                             -
                        @endif {{$nama}}</option>
                    @endforeach
                </select>
            </div>

            


        

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" wire:click="store" class="btn btn-success">Tambah KRS</button>
    </div>
    </form>
</div>
