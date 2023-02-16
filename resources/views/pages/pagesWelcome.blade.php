@extends('layout.layoutAdmin')

@section('activekuHome')
    activeku
@endsection

@section('judul')
    <i class="fa fa-home"></i> Home
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{$card}}</h3>

          <p>CARD</p>
        </div>
        <div class="icon">
          <i class="fa fa-id-card"></i>
        </div>
        <a href="{{ url('mahasiswa/card', []) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{$ruangan}}</h3>

          <p>RUANGAN</p>
        </div>
        <div class="icon">
            <i class="fas fa-desktop"></i>
        </div>
        <a href="{{ url('master', []) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{$mahasiswa}}</h3>

          <p>MAHASISWA</p>
        </div>
        <div class="icon">
            <i class="fas fa-users"></i>
        </div>
        <a href="{{ url('mahasiswa', []) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{$matkul}}</h3>
  
            <p>MATAKULIAH</p>
          </div>
          <div class="icon">
              <i class="fas fa-book-open"></i>
          </div>
          <a href="{{ url('matkul', []) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

</div>


@endsection

@section('myScript')
@include('layout.layoutJS')
@endsection