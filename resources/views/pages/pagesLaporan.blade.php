@extends('layout.layoutAdmin')

@section('activekuLaporan')
    activeku
@endsection


@section('judul')
    <i class="fa fa-door-open"></i> Jadwal Matkul
@endsection




@section('content')
<div class="row">
    <div class="col-md-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahJadwal">
            <i class="fa fa-door-open"></i>Tambah Jadwal
        </button>


        <div class="modal fade" id="tambahJadwal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Import Jadwal (API)</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('import.jadwal') }}" method="post">
                  @csrf
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="">Pilih Kelas</label>
                        <select name="kelas" id="" class="form-control" required>
                              <option value="all">Semua</option>
                          @foreach ($kelas as $kls)
                              <option value="{{$kls->idkelas}}">{{strtoupper($kls->nama_kelas)}}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="">Tahun Ajaran</label>
                        <select name="ta" id="" class="form-control" required>
                          @for ($i = 2018; $i <= date('Y')+1; $i++)
                          <option value="{{$i}}" @if ($i == date('Y'))
                              selected
                          @endif>{{$i}}</option>
                              
                          @endfor
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="">Prodi MK</label>
                        <select name="idprodi" id="" class="form-control" required>
                            @foreach ($prodi as $item)
                                <option value="{{$item->id_prodi}}">{{$item->nama_prodi}}</option>
                            @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="">Semester</label>
                        <select name="idsmt" id="" class="form-control" required>
                            @foreach ($semester as $item)
                                <option value="{{$item->idsmt}}">{{strtoupper($item->namasmt)}}</option>
                            @endforeach
                        </select>
                      </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" onclick="return tambah_jadwal()">Import Jadwal</button>
                  </div>

                </form>
              </div>
            </div>
          </div>
        
        
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-8">
          <form action="{{ url()->current() }}">
              <div class="row">
                <div class="col-md-2">
                  <select name="ruangan" id="" class="form-control"  style="font-size: 13px" onchange="submit()">
                    <option value="">Ruangan</option>
                    @foreach ($ruangan as $item)
                        <option value="{{ $item->idruangan }}" @if (empty($_GET['ruangan'])?"":$_GET['ruangan'] == $item->idruangan)
                            selected
                        @endif>{{ $item->nama_ruangan }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="hari" id="" class="form-control" style="font-size: 13px" onchange="submit()">
                    <option value="">hari</option>
                    @foreach ($hari as $item)
                        <option value="{{ $item->idhari }}" @if (empty($_GET['hari'])?"":$_GET['hari'] == $item->idhari)
                            selected
                        @endif>{{ strtoupper($item->nama_hari) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="kelas" id="" class="form-control" style="font-size: 13px" onchange="submit()">
                    <option value="">kelas</option>
                    @foreach ($kelas as $item)
                        <option value="{{ $item->idkelas }}" @if (empty($_GET['kelas'])?"":$_GET['kelas'] == $item->idkelas)
                            selected
                        @endif>{{ $item->nama_kelas }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <select name="tahun" id="" class="form-control text-bold" style="font-size: 13px" onchange="submit()">
                    <option value="">Tahun</option>
                      @for ($i = 2020; $i <= date('Y') +1; $i++)
                      <option value="{{ $i }}" @if ($tahun == $i)
                          selected
                      @endif>{{ $i }}</option>
                      
                      @endfor
                  </select>
                </div>

                <div class="col-md-3">
                  <select name="semester" id="" class="form-control text-bold" style="font-size: 13px" onchange="submit()">
                    <option value="">Semester</option>
                    @foreach ($semester as $item)
                    <option value="{{ $item->idsmt }}" @if ($item->idsmt == $semester_)
                        selected
                    @endif>{{ $item->namasmt }}</option>
                        
                    @endforeach
                  </select>
                </div>

              </div>
          </form>
        </div>
        <div class="col-md-4">
          <form action="{{ url()->current() }}" class="form-inline justify-content-end">
              <div class="input-group mb-3">
                  <input type="text" hidden value="{{$tahun}}" name="tahun">
                  <input type="text" class="form-control text-uppercase" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" placeholder="Masukan kode matkul" aria-describedby="button-addon2">
                  <div class="input-group-append">
                    <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                  </div>
              </div>
              
          </form>
        </div>
      </div>

    </div>
</div>

{{-- table    --}}



<div class="card">
  <div class="">
    <table class="table table-bordered table-hover table-sm table-striped">
      <thead>
        <tr>
          <th class="text-center">No</th>
          <th>KD KELAS</th>
          <th>KD MATKUL</th>
          <th>NAMA MATKUL</th>
          <th>KELAS</th>
          <th>HARI</th>
          <th>RUANGAN</th>
          <th>Smt</th>
          <th title="Jumlah Peserta">JP</th>
          <th>Aksi</th>
        </tr>
      </thead>
  
      <tbody>
        @foreach ($tampil as $item)
            <tr>
              <td class="text-center">{{ $loop->iteration + $tampil->firstItem() - 1 }}</td>
              <td>{{$item->idkelas_mhs}}</td>
              <td>{{$item->kode_matkul}}</td>
              <td>{{$item->nmatkul}}</td>
              <td>{{$item->nama_kelas}}</td>
              <td>{{$item->nama_hari}}</td>
              <td>{{$item->nama_ruangan}}</td>
              <td>{{ucwords($item->namasmt)}}</td>
              <td>
                @php
                    $jp = DB::table('kelas_mhs_peserta')->where('idkelas_mhs', $item->idkelas_mhs)->count();
                @endphp
                {{ $jp }}
              </td>
              <td>
                <button type="button" class="badge badge-primary border-0" data-toggle="modal" data-target="#detailkelas{{$item->idkelas_mhs}}">
                  Detail Peserta
                </button>
              </td>
              <td>
                  <a href="{{ url('laporan/cetak', [$item->idkelas_mhs]) }}" target="_blank" class="badge badge-success py-1 border-0"><i class="fa fa-print"></i>Cetak</a>
              </td>

            </tr>

           

            
        @endforeach
        
      </tbody>
    </table>
  </div>
  @foreach ($tampil as $item)
  <div class="modal fade" id="detailkelas{{$item->idkelas_mhs}}" tabindex="-1" aria-labelledby="detailkelas{{$item->idkelas_mhs}}Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailkelas{{$item->idkelas_mhs}}Label">PESERTA KULIAH ({{$item->idkelas_mhs}})</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover table-sm table-striped table-bordered">
                  <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                  </tr>
                  @php
                      $mahasiswa = DB::table('kelas_mhs_peserta')
                      ->join('mahasiswa','mahasiswa.nim','=','kelas_mhs_peserta.nim')
                      ->where('kelas_mhs_peserta.idkelas_mhs', $item->idkelas_mhs)
                      ->select("mahasiswa.*", 'kelas_mhs_peserta.idkelas_mhs')
                      ->get();
                  @endphp
                  @foreach ($mahasiswa as $mhs)
                      <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$mhs->nim}}</td>
                          <td>{{$mhs->nama_mhs}}</td>
                          <td>
                            @if ($mhs->jk == 'P')
                                Perempuan
                            @elseif($mhs->jk == "L")
                                Laki-laki
                            @endif
                          </td>
                      </tr>
                  @endforeach
              </table>

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  <div class="card-footer">
    {{ $tampil->links('vendor.pagination.bootstrap-4') }}
  </div>
</div>


@endsection

@section('myScript')
@include('layout.layoutJS')
@endsection