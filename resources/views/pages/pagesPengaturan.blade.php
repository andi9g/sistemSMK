@extends('layout.layoutAdmin')

@section('activekuPengaturan')
    activeku
@endsection


@section('judul')
    <i class="fa fa-door-open"></i> Pengaturan
@endsection




@section('content')

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-lg bg-secondary text-bold">
        <i class="fa fa-wrench"></i> Setting Website
      </div>
      <form action="{{ route('pengaturan.store') }}" method="post">
        @csrf
        @method('POST')
        <div class="card-body">
          <div class='form-group'>
              <label for='forketerlambatan' class='text-capitalize'>Keterlambatan (menit)</label>
              <input type='number' name='keterlambatan' id='forketerlambatan' class='form-control @error('keterlambatan')
                is-invalid
              @enderror' value="{{empty($pengaturan->keterlambatan)?'':$pengaturan->keterlambatan}}">
              @error('keterlambatan')
                <div class="invalid-feedback">
                  {{$message}}
                </div>
              @enderror
          </div>
  
          <div class='form-group'>
            <label for='forjammasuk' class='text-capitalize'>Jam Masuk</label>
            <input type='time' name='jammasuk' id='forjammasuk' class='form-control @error('jammasuk')
              is-invalid
            @enderror' value="{{empty($pengaturan->jammasuk)?'':$pengaturan->jammasuk}}">
            @error('jammasuk')
              <div class="invalid-feedback">
                {{$message}}
              </div>
            @enderror
          </div>
  
          
        </div>
  
        <div class="card-footer bg-secondary text-right">
          <button type="submit" class="btn btn-default text-bold "> UPDATE PENGATURAN</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

@section('myScript')
@include('layout.layoutJS')
@endsection