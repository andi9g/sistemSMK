@extends('layout.layoutAdmin')

@section('atas')
<script src="https://code.jquery.com/jquery-3.5.0.slim.min.js" integrity="sha256-MlusDLJIP1GRgLrOflUQtshyP0TwT/RHXsI1wWGnQhs=" crossorigin="anonymous"></script>
<link href="{{ url('select2/dist/css/select2.min.css', []) }}" rel="stylesheet" />
<script src="{{ url('select2/dist/js/select2.min.js', []) }}"></script>
@endsection

@section('activekuAbsen')
    activeku
@endsection

@section('judul')
    <i class="fa fa-user"></i> Absensi Siswa
    
@endsection

@section('content')
<div class="row" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">
    <div class="col-md-6">
        <a href="{{ url('/absen', []) }}" class="btn btn-secondary">
            <i class="fa fa-refresh"></i> Refresh
        </a>
    </div>
    <div class="col-md-6 text-right d-inline pb-2">
        <h4 class="text-bold d-inline"> @if ($open->open == true)
            JAM MASUK :
            @else
            JAM KELUAR :

        @endif </h4>

        <form action="{{ route('ubah.jam') }}" method="post" class="d-inline">
            @csrf
            <button type="submit" class="d-inline btn btn-danger btn-sm text-bold">
                <i class="fa fa-exchange"></i>
                MASUK/KELUAR
            </button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#ketabsensiswa">
          <i class="fa fa-plus"></i> Tambah Keterangan Siswa
        </button>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-warning mb-2" data-toggle="modal" data-target="#help">
          <i class="fa fa-question-circle"></i>
        </button>

        
        <!-- Modal -->
        <div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-question-circle"></i></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr>
                                <td class="ket-hijau">&emsp;</td>
                                <td> Hadir</td>
                            </tr>
                            <tr>
                                <td class="ket-merah">&emsp;</td>
                                <td> Alfa</td>
                            </tr>
                            <tr>
                                <td class="ket-kuning">&emsp;</td>
                                <td> Sakit</td>
                            </tr>
                            <tr>
                                <td class="ket-biru">&emsp;</td>
                                <td> Izin</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="ketabsensiswa" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Keterangan Siswa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <form action="{{ route('tambah.keterangan', []) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class='form-group'>
                                <label for='forsiswa' class='text-capitalize'>Identitas nis/nama</label><br>
                                <select name='siswa' id='forsiswa' style="width:100% !important;" class='form-control form-control-sm absen-keterangan'>
                                    <option value=''>Identitas Siswa</option>
                                    @foreach ($siswa as $item)
                                        <option value="{{$item->nis}}">{{$item->nis}}-{{ucwords($item->namasiswa)}} -[ {{$item->namajurusan}} ]</option>
                                    @endforeach
                                <select>
                            </div>
    
                            <div class='form-group'>
                                <label for='forketerangan' class='text-capitalize'>Keterangan</label>
                                <select name='keterangan' required id='forketerangan' class='form-control'>
                                    <option value=''>Pilih</option>
                                    @foreach ($ket as $item)
                                        <option value="{{$item->idket}}">{{strtoupper($item->namaket)}}</option>
                                    @endforeach
                                <select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Proses</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-6 m-0">
                <div class="card bg-secondary">
                    <div class="card-body p-1 text-center">
                        <b class="text-lg p-0">Jumlah Siswa : {{$jumlahSiswa}}</b>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-secondary">
                    <div class="card-body p-1 text-center">
                        <b class="text-lg p-0">Jumlah Kehadiran : {{$jumlahKehadiran}}</b>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<form action="{{ url()->current() }}" class="">
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4">
                <div class='form-group'>
                    <input type='date' name='tanggal' id='fortanggal' onchange="submit()" class='form-control text-bold' style="background: rgba(208, 255, 208, 0.993)" value="{{$tanggal}}">
                </div>
            </div>
            <div class="col-md-4">
                    <select name='jurusan' id='forjurusan' onchange="submit()" class='form-control'>
                        <option value="">Jurusan</option>
                        @foreach ($Djurusan as $item)
                            <option value="{{$item->idjurusan}}" @if ($item->idjurusan == $jurusan)
                                selected
                            @endif>{{$item->namajurusan}}</option>
                        @endforeach
                    <select>
            </div>
            <div class="col-md-4">
                <select name='kelas' id='forkelas' onchange="submit()" class='form-control'>
                    <option value="">Kelas</option>
                    @foreach ($Dkelas as $item)
                        <option value="{{$item->idkelas}}" @if ($item->idkelas == $kelas)
                            selected
                        @endif>{{$item->namakelas}}</option>
                    @endforeach
                <select>
        </div>
        </div>
        
    </div>
    <div class="col-md-6">
            <div class="input-group mb-3">
                <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" aria-describedby="button-addon2" placeholder="Masukan nama atau nis">
                <div class="input-group-append">
                  <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                </div>
            </div>
            
        </div>
    </div>
</form>

    
    
    <div class="card table-responsive">
        
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm table-striped">
                <thead>
                  <tr>
                    <th class="text-center" width="5px">No</th>
                    <th nowrap width="1%">NIS</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Kelas</th>
                    <th nowrap width="1%">Jam Masuk</th>
                    <th nowrap width="1%">Jam Keluar</th>
                    <th>Ink</th>
                    <th>Ket</th>
                    <th>Aksi</th>
                  </tr>
                </thead>

                <tbody>
                    @foreach ($absen as $item)
                        
                    <tr>
                        <td class="text-center">{{$loop->iteration + $absen->firstItem() - 1}}</td>
                        <td nowrap class="text-capitalize text-bold">{{$item->nis}}</td>
                        <td class="text-capitalize text-bold">{{$item->namasiswa}}</td>
                        <td>{{$item->namajurusan}}</td>
                        <td>{{$item->namakelas}}</td>
                        <td class="text-center">{{$item->jammasuk}}</td>
                        <td class="text-center">{{$item->jamkeluar}}</td>
                        <td align="center" @if ($item->ket == 'H')
                            class="ket-hijau" 
                            @elseif($item->ket == 'I')
                            class="ket-kuning" 
                            @elseif($item->ket == 'S')
                            class="ket-biru" 
                            @elseif($item->ket == 'A')
                            class="ket-merah" 
                            @endif
                            width="30px"
                            >
                            @if ($item->ket == 'H')
                            H
                            @elseif($item->ket == 'I')
                            I
                            @elseif($item->ket == 'S')
                            S
                            @elseif($item->ket == 'A')
                            A 
                            @endif
                        </td>
                        <td>
                            @php
                                $jm = empty($pengaturan->jammasuk)?"07:30":$pengaturan->jammasuk;
                                $kt = empty($pengaturan->keterlambatan)?"0":$pengaturan->keterlambatan;
                                $ex = strtotime(date('H:i:s', strtotime('+'.$kt.' min', strtotime($jm))));
                                
                            @endphp
                            @if ($item->ket == 'H')
                                @if (strtotime($item->jammasuk) > $ex)
                                    Terlambat
                                    @else
                                    Hadir
                                @endif
                            @elseif($item->ket == 'I')
                            Izin
                            @elseif($item->ket == 'S')
                            Sakit
                            @elseif($item->ket == 'A')
                            Alfa
                            @endif
                            

                        </td>
                        <td nowrap width="1%">
                            <!-- Button trigger modal -->
                            <button type="button" class="badge badge-info border-0" data-toggle="modal" data-target="#editabsen{{$item->idabsen}}">
                              <i class="fa fa-edit"></i> Edit
                            </button>

                            <!-- Button trigger modal -->
                            <button type="button" class="badge badge-danger border-0" data-toggle="modal" data-target="#hapusabsen{{$item->idabsen}}">
                              <i class="fa fa-trash"></i>Hapus
                            </button>
                            
                            

                        </td>
                    </tr>

                    {{-- Hapus absen --}}
                    <div class="modal fade" id="hapusabsen{{$item->idabsen}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i>Alert!</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <div class="modal-body" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">
                                    <h5>Apakah anda yakin ingin menghapus Absensi {{ucwords($item->namasiswa)}}?</h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <form action="{{ route('hapus.keterangan', [$item->idabsen]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editabsen{{$item->idabsen}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h5 class="modal-title">Edit Data {{$item->nis}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <form action="{{ route('ubah.keterangan', [$item->idabsen]) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class='form-group'>
                                        <label for='forketerangan' class='text-capitalize'>Keterangan</label>
                                        <select name='keterangan' id='forketerangan' class='form-control'>
                                            @foreach ($ket as $k)
                                                <option value="{{$k->ket}}" @if ($k->ket == $item->ket)
                                                    selected
                                                @endif>{{strtoupper($k->namaket)}}</option>
                                            @endforeach
                                        <select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Ubah</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>

                

            </table>
        </div>  
        <div class="card-footer">
            {{$absen->links('vendor.pagination.bootstrap-4')}}
        </div>
    </div>    


@endsection

@section('myScript')
@include('layout.layoutJS2')

<script>
    $(document).ready(function() {
        $('.absen-keterangan').select2();
    });
</script>
    
@endsection