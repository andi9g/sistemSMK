<div>
    {{-- <form> --}}
        <div class="modal-body">

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


            <div class="form-group">
                <label for="">Masukan Matkul</label>
                <input type="text" wire:model="cari_matkul" wire:keyup="cari_matkul" class="form-control rounded-0 text-uppercase" style="background: rgba(0, 81, 255, 0.212)" placeholder="Cari berdasarkan Kode Matkul">
                <select name="kd_matkul" wire:model="kd_matkul" class="form-control rounded-0">
                    @foreach ($opt_matkul as $item)
                        @php
                            $ambil = explode("-", $item);
                            $matkul = empty($ambil[0])?"":$ambil[0];
                            $nama = empty($ambil[1])?"":$ambil[1];
                        @endphp
                        <option value="{{$matkul}}">{{$matkul}}@if (!empty($matkul))
                             -
                        @endif {{$nama}}</option>
                    @endforeach
                </select>
            </div>


            @if (session()->has('message'))
            <div class="alert alert-danger text-center">
                {{ session('message') }}
            </div>
            @endif

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button wire:click='store' class="btn btn-primary">Tambah Card</button>
        </div>
    {{-- </form> --}}
</div>
