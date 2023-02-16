@extends('layout.layoutAdmin')

@section('activekuJadwal')
    activeku
@endsection

@section('judul')
    <i class="fa fa-door-open"></i> Jadwal Matkul
@endsection


@push('start')
    @livewireStyles()
@endpush

@push('end')
    @livewireScripts()
@endpush

@section('content')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahJadwal">
            <i class="fa fa-door-open"></i>Tambah Jadwal
        </button>


        <div class="modal fade" id="tambahJadwal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Tambah Jadwal</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                   @livewire('form-jadwal')
              </div>
            </div>
          </div>
        
        
    </div>
    <div class="col-md-6">
      <form action="{{ route('cari.jadwal') }}" class="form-inline justify-content-end" method="post">
        @csrf
          <div class="input-group mb-3">
              <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" aria-describedby="button-addon2">
              <div class="input-group-append">
                <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
              </div>
          </div>
          
      </form>
    </div>
</div>

{{-- table    --}}

<div class="row">
  <div class="col">
    <a href="{{ url('jadwal') }}" class="badge badge-danger badge-btn"> << Kembali </a>
  </div>
</div>

<div class="card">
  <table class="table table-bordered table-hover table-sm table-striped">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>KD MATKUL</th>
        <th>NAMA MATKUL</th>
        <th>KELAS</th>
        <th>HARI</th>
        <th>RUANGAN</th>
        <th>JAM</th>
        <th>ACTION</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($tampil as $data)
        <tr>
          <td class="text-center">{{ $loop->iteration }}</td>
          <td>{{ $data->kd_matkul }}</td>
          <td>{{ $data->nama_matkul }}</td>
          <td>{{ $data->kelas }}</td>
          <td>{{ $data->hari }}</td>
          <td>{{ $data->ruangan }}</td>
          <td>{{ $data->jam }}</td>

          <td>

            <a href="{{ route('ubah.jadwal', [$data->id, $data->kd_matkul, $data->ruangan, $data->hari, $data->kelas]) }}">
              <span class="badge badge-success"><i class="fa fa-edit"></i> Edit</span>
            </a>

            <form action="{{ route('hapus.jadwal', [$data->id]) }}" method="post" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="badge badge-danger" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-trash"></i> Hapus</button>
            </form>
          </td>
        </tr>
          
      @endforeach
    </tbody>
  </table>
</div>


@endsection

@section('myScript')
@include('layout.layoutJS')
@endsection