@extends('layout.layoutAdmin')

@section('activekuSiswa')
    activeku
@endsection

@section('judul')
    <i class="fa fa-users"></i> Data Siswa
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-7">
          <form action="{{ url()->current() }}">
              <div class="row">
                <div class="col-md-4">
                  <select name="jurusan" id="" class="form-control" onchange="submit()">
                    <option value="">jurusan</option>
                    @foreach ($jurusan as $item)
                        <option value="{{ $item->idjurusan }}" @if (empty($_GET['jurusan'])?"":$_GET['jurusan'] == $item->idjurusan)
                            selected
                        @endif>{{ $item->namajurusan }}</option>
                    @endforeach
                  </select>
                </div>
                
                <div class="col-md-4">
                  <select name="kelas" id="" class="form-control" onchange="submit()">
                    <option value="">kelas</option>
                    @foreach ($kelas as $item)
                        <option value="{{ $item->idkelas }}" @if (empty($_GET['kelas'])?"":$_GET['kelas'] == $item->idkelas)
                            selected
                        @endif>{{ $item->namakelas }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <select name="tahunmasuk" id="" class="form-control text-bold" onchange="submit()">
                    <option value="">Tahun</option>
                      @foreach ($Dtahun as $item)
                      <option value="{{ $item->tahunmasuk }}" @if ($item->tahunmasuk == $tahun)
                          selected
                      @endif>{{ $item->tahunmasuk }}</option>
                          
                      @endforeach
                  </select>
                </div>

              </div>
          </form>
        </div>
        <div class="col-md-5">
          
        </div>
      </div>

    </div>
    <div class="col-md-4">
        <form action="{{ url()->current() }}" class="form-inline justify-content-end">
            <div class="input-group mb-3">
                <input type="text" hidden value="{{$tahun}}" name="tahun">
                <input type="text" class="form-control text-uppercase" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" placeholder="Masukan nim" aria-describedby="button-addon2">
                <div class="input-group-append">
                  <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                </div>
            </div>
            
        </form>
    </div>

    <div class="col-md-12">
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary btn-xs btn-lg mb-2" data-toggle="modal" data-target="#tambahSiswa">
        <i class="fa fa-user-plus"></i> Tambah Siswa
      </button>
      
      <!-- Modal -->
      <div class="modal fade" id="tambahSiswa" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('siswa.store') }}" method="post">
              @csrf
            <div class="modal-body">
              <div class='form-group'>
                  <label for='fornis' class='text-capitalize'>NIS</label>
                  <input type='number' name='nis' id='fornis' class='form-control' placeholder='masukan nis'>
              </div>

              <div class='form-group'>
                  <label for='fornamasiswa' class='text-capitalize'>Nama Siswa</label>
                  <input type='text' name='namasiswa' id='fornamasiswa' class='form-control' placeholder='masukan nama siswa'>
              </div>

              <div class='form-group'>
                  <label for='fornama' class='text-capitalize'>Kelamin</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="jk" id="exampleRadios1" value="L" checked>
                    <label class="form-check-label" for="exampleRadios1">
                      Laki-Laki
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="jk" id="exampleRadios2" value="P">
                    <label class="form-check-label" for="exampleRadios2">
                      Perempuan
                    </label>
                  </div>
                  
              </div>

              <div class='form-group'>
                  <label for='forjurusan' class='text-capitalize'>Jurusan</label>
                  <select name='jurusan' id='forjurusan' class='form-control'>
                    @foreach ($jurusan as $item)
                        <option value="{{ $item->idjurusan }}" @if (empty($_GET['jurusan'])?"":$_GET['jurusan'] == $item->idjurusan)
                            selected
                        @endif>{{ $item->namajurusan }}</option>
                    @endforeach
                  <select>
              </div>

              <div class='form-group'>
                  <label for='forkelas' class='text-capitalize'>Kelas</label>
                  <select name='kelas' id='forkelas' class='form-control'>
                    @foreach ($kelas as $item)
                        <option value="{{ $item->idkelas }}" @if (empty($_GET['jurusan'])?"":$_GET['jurusan'] == $item->idkelas)
                            selected
                        @endif>{{ $item->namakelas }}</option>
                    @endforeach
                  <select>
              </div>

              <div class='form-group'>
                  <label for='fortahun' class='text-capitalize'>Tahun</label>
                  <input type='number' name='tahun' id='fortahun' class='form-control' placeholder='masukan tahun'>
              </div>

              

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form> 
          </div>
        </div>
      </div>
    </div>
</div>

{{-- table    --}}



<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-sm table-striped">
      <thead>
        <tr>
          <th class="text-center">No</th>
          <th>NIS</th>
          <th>Nama Siswa</th>
          <th>Jenis Kelamin</th>
          <th>Tahun Masuk</th>
          <th>Kelas</th>
          <th>Jurusan</th>
          <th>Jurusan</th>
        </tr>
      </thead>
  
      <tbody>
        @foreach ($tampil as $item)
            <tr>
              <td class="text-center" width="10px">{{ $loop->iteration + $tampil->firstItem() - 1 }}</td>
              <td>{{$item->nis}}</td>
              <td>{{$item->namasiswa}}</td>
              <td>{{$item->jk}}</td>
              <td>{{$item->tahunmasuk}}</td>
              <td>{{$item->namakelas}}</td>
              <td>{{$item->namajurusan}}</td>
              <td>
                <!-- Button trigger modal -->
                <button type="button" class="badge badge-primary border-0 d-inline" data-toggle="modal" data-target="#editdata{{$item->nis}}">
                  <i class="fa fa-edit"></i> Edit
                </button>
                
                <!-- Button trigger modal -->
                <button type="button" class="badge badge-danger border-0 d-inline" data-toggle="modal" data-target="#hapusdata{{$item->nis}}">
                  <i class="fa fa-trash"></i> Hapus
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="hapusdata{{$item->nis}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-danger">
                        <h5 class="modal-title">Hapus</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                        Yakin ingin menghapusnya?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route('siswa.destroy', [$item->nis]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Hapus</button>
                      </form>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            
            
            <div class="modal fade" id="editdata{{$item->nis}}" tabindex="-1" role="dialog" aria-labelledby="editdata" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <form action="{{ route('siswa.update', [$item->nis]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                      <div class='form-group'>
                        <label for='fornamasiswa' class='text-capitalize'>Nama Siswa</label>
                        <input type='text' name='namasiswa' id='fornamasiswa' class='form-control' value="{{$item->namasiswa}}">
                      </div>

                      <div class='form-group'>
                        <label for='fornama' class='text-capitalize'>Kelamin</label>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="jk" id="exampleRadios1" value="L" checked>
                          <label class="form-check-label" for="exampleRadios1" @if ($item->jk=="L")
                              selected
                          @endif>
                            Laki-Laki
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="jk" id="exampleRadios2" value="P">
                          <label class="form-check-label" for="exampleRadios2" @if ($item->jk=="P")
                              selected
                          @endif>
                            Perempuan
                          </label>
                        </div>
                        
                    </div>
      
                    <div class='form-group'>
                        <label for='forjurusan' class='text-capitalize'>Jurusan</label>
                        <select name='jurusan' id='forjurusan' class='form-control'>
                          @foreach ($jurusan as $i)
                              <option value="{{ $i->idjurusan }}" @if ($i->idjurusan == $item->idjurusan)
                                  selected
                              @endif>{{ $item->namajurusan }}</option>
                          @endforeach
                        <select>
                    </div>
      
                    <div class='form-group'>
                        <label for='forkelas' class='text-capitalize'>Kelas</label>
                        <select name='kelas' id='forkelas' class='form-control'>
                          @foreach ($kelas as $i)
                              <option value="{{ $i->idkelas }}" @if ($i->idkelas == $item->idkelas)
                                  selected
                              @endif>{{ $i->namakelas }}</option>
                          @endforeach
                        <select>
                    </div>
      
                    <div class='form-group'>
                        <label for='fortahun' class='text-capitalize'>Tahun</label>
                        <input type='number' name='tahun' id='fortahun' class='form-control' value="{{$item->tahunmasuk}}">
                    </div>



                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Edit</button>
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
    {{ $tampil->links('vendor.pagination.bootstrap-4') }}
  </div>
</div>


@endsection

@section('myScript')
@include('layout.layoutJS')
@endsection