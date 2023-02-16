<div>
    <form>
    <div class="modal-body">
        <div class='form-group'>
            <label for='fornnidninput' class='text-capitalize'>Masukan NIDN</label>
            <input type='number' ni wire:model="ketiknidn" wire:keyup="nidnketik" id='fornnidninput' class='form-control disabled mb-0 pb-0' placeholder='ketik nidn' style="border-bottom-left-radius: 0;border-bottom-right-radius: 0;background: rgb(198, 255, 198)">
            <select name='nidn' wire:model.debounce.10ms="nidn" wire:change="pilihnidn" id='fornidn' class='form-control border-top-0 mt-0 pt-0' style="border-top-left-radius: 0;border-top-right-radius: 0">
                @php
                    $no = 1;
                @endphp
                @foreach ($dataNidn as $data)
                    @if ($no === 1)
                        <option value="" selected>Silahkan Pilih Dosen</option>   
                    @endif
                    {{$}}
                    <option value="{{$data['nidn']}}" @if ($data['nidn'] ==  $nidn)
                        selected="true"
                    @endif>{{$data['nidn']." - ".$data['nama_dosen']}}</option>
                @endforeach
            <select>
        </div>
        {{ $nidn }}
        <div class='form-group'>
            <label for='forkodematkul' class='text-capitalize'>Matkul</label>
            <select name='kode_matkul' wire:model='kode_matkul' wire:change='kd_matkul' id='forkodematkul' class='form-control' >
                <option value="">Silahkan Pilih Matkul</option>
                {{-- @foreach ($datakode_matkul as $data2)
                    <option value='{{$data2->kode_matkul}}' @if ($data2->kode_matkul == $kode_matkul)
                        selected
                    @endif>{{"[".$data2->kode_matkul."] - ".$data2->nmatkul}}</option>
                @endforeach --}}
            <select>
        </div>
        

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" wire:click="store" class="btn btn-success">Tambah Jadwal</button>
    </div>
    </form>
</div>
