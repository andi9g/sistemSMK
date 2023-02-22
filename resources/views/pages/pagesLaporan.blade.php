@extends('layout.layoutAdmin')

@section('activekuLaporan')
    activeku
@endsection


@section('judul')
    <i class="fa fa-door-open"></i> Laporan Absensi
@endsection




@section('content')

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-lg bg-secondary text-bold">
        <i class="fa fa-print"></i> CETAK ABSENSI
      </div>
      <form action="{{ route('cetak.laporan') }}" method="get" target="_blank">
        @csrf
        {{-- @method('get') --}}
        <div class="card-body">
          <div class='form-group'>
              <label for='fortanggal1' class='text-capitalize'>Tanggal Awal</label>
              <input type='date' name='tanggal1' id='fortanggal1' class='form-control @error('tanggal1')
                is-invalid
              @enderror' >
              @error('tanggal1')
                <div class="invalid-feedback">
                  {{$message}}
                </div>
              @enderror
          </div>
  
          <div class='form-group'>
            <label for='fortanggal2' class='text-capitalize'>Tanggal Akhir</label>
            <input type='date' name='tanggal2' id='fortanggal2' class='form-control @error('tanggal2')
              is-invalid
            @enderror' >
            @error('tanggal2')
              <div class="invalid-feedback">
                {{$message}}
              </div>
            @enderror
          </div>
  
          <div class='form-group'>
              <label for='forjurusan' class='text-capitalize'>Jurusan</label>
              <select name='jurusan' id='forjurusan' class='form-control'>
                  <option value='all'>Semua Jurusan</option>
                  @foreach ($jurusan as $item)
                      <option value="{{$item->idjurusan}}">{{$item->namajurusan}}</option>
                  @endforeach
              <select>
          </div>
  
          <div class='form-group'>
            <label for='forkelas' class='text-capitalize'>Kelas</label>
              <select name='kelas' id='forkelas' class='form-control'>
                  <option value='all'>Semua Kelas</option>
                  @foreach ($kelas as $item)
                      <option value="{{$item->idkelas}}">{{$item->namakelas}}</option>
                  @endforeach
              <select>
          </div>
        </div>
  
        <div class="card-footer bg-secondary text-right">
          <button type="submit" class="btn btn-default text-bold "> Cetak Laporan</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

@section('myScript')
@include('layout.layoutJS')
@endsection