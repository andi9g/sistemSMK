
@extends('layout.layoutAdmin')


@section('activekuSiswa')
    activeku
@endsection

@section('judul')
    <i class="fa fa-users"></i> Card Siswa
@endsection

@section('content')
<a href="{{ url('/siswa', []) }}" class=""><< BACK</a>
<div class="row">
    <div class="col-md-4">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahsiswa">
          <i class="fa fa-user-plus"></i> Tambah Siswa
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="tambahsiswa" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahCard">
            <i class="fa fa-id-card"></i> Tambah Card Siswa
        </button>

        <!-- Modal -->
        <div class="modal fade" id="tambahCard" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Card</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('tambah.card') }}" method="post">
                    @csrf
                    @method('patch')
                <div class="modal-body">
                    <small><i class="text-success">silahkan scan CARD RFID</i></small>
                    <div class="form-group">
                        <label for="">UID Scan</label>
                        <textarea name="uid" id="UID" rows="1" class="form-control text-center justify-content-center align-content-center pl-1" style="outline:none;background: rgba(158, 158, 158, 0.329);border:1px solid rgba(146, 146, 146, 0.596);border-radius:5px;resize:none;text-align:center;width: fit-content" placeholder="" readonly></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Masukan NIS</label>
                        <input type="number" name="nis" id="id_nis" class="form-control text-center">
                    </div>

                    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Card</button>
                </div>
            </form>
            </div>
            </div>
        </div>

        
    </div>
    <div class="col-md-8">
        <form action="{{ url()->current() }}" class="form-inline justify-content-end">
        <div class="row">
            <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4 m-0 p-0">
                            <select name="tahunmasuk" id="" class="form-control text-bold" onchange="submit()">
                                <option value="">Tahun</option>
                                @foreach ($Dtahun as $item)
                                    <option value="{{$item->tahunmasuk}}" @if (empty($_GET['tahun'])?"":$_GET['tahun']==$item->tahunmasuk)
                                        selected
                                    @endif>{{$item->tahunmasuk}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 m-0 p-0">
                            <select name="jurusan" id="" class="form-control" onchange="submit()">
                                <option value="">Jurusan</option>
                                @foreach ($jurusan as $item)
                                    <option value="{{$item->idjurusan}}" @if (empty($_GET['jurusan'])?"":$_GET['jurusan']==$item->idjurusan)
                                        selected
                                    @endif>
                                    {{$item->namajurusan}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 ">
                            <select name="kelas" id="" class="form-control" onchange="submit()">
                                <option value="">Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{$item->idkelas}}" @if (empty($_GET['kelas'])?"":$_GET['kelas']==$item->idkelas)
                                        selected
                                    @endif>{{$item->namakelas}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    
                    
            </div>

            <div class="col-md-6">
                
                    <div class="input-group mb-3">
                        <input type="text" name="tahun" value="{{$tahun}}" hidden>
                        <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" aria-describedby="button-addon2">
                        <div class="input-group-append">
                          <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                        </div>
                    </div>
                    
                
            </div>
        </div>
        </form>
        

        
    </div>
</div>

<div class="card">
    <div class="card-header">
        <font class="text-bold">DATA SISWA</font> @error('uid')
            - <font class="text-danger text-bold">( Proses Penambahan Card Dihentikan!!! )</font>
        @enderror
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-sm table-hover">
            <thead class="bg-secondary">
                <tr>  
                    <th>Nis</th>    
                    <th>Nama Lengkap</th>    
                    <th>Kelamin</th>    
                    <th>Kelas</th>    
                    <th>Tahun</th>  
                    <th>Action</th>
                    <th>KET</th>
                </tr>    
            </thead>
            
            <tbody>
                @foreach ($siswa as $item)
                <tr>
                    <td class="text-center text-bold">{{ $item->nis }}</td>
                    <td>{{ $item->namasiswa }}</td>
                    <td>{{ ($item->jk=="L")?"Laki-Laki":"Perempuan" }}</td>
                    <td>{{ strtoupper($item->namakelas) }}</td>
                    <td class="text-center">{{ strtoupper($item->tahunmasuk) }}</td>
                    <td>
                        <button type="button" onclick="kirim_nis_{{$item->nis}}(this)" value="{{$item->nis}}" data-toggle="modal" class="btn btn-xs  btn-success btn-block" data-target="#tambahCard"><i class="fa fa-id-card"></i> Card</button>
                    </td>
                    <td class="text-center">
                        @php
                            $cek = DB::table('card')->where('nis', $item->nis)->count();
                        @endphp
                        @if ($cek === 1)
                            <p class="badge badge-success my-0">Terdaftar</p>
                            <form action="{{ route('reset.card', [$item->nis]) }}" method="post" class="d-inline my-0">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin ingin direset?')" class="badge badge-secondary border-0">
                                    reset
                                </button>
                            </form>
                        @else
                            <p class="badge badge-danger my-0">Belum Terdaftar</p>
                            
                        @endif
                    </td>
                </tr>
                    

                <script>
                    function kirim_nis_{{$item->nis}}(nis) {
                        document.getElementById('id_nis').value=nis.value;
                    }
                </script>
                @endforeach
            </tbody>
        </table>    
    </div> 
    
    <div class="card-footer">
        {{ $siswa->links('vendor.pagination.bootstrap-4') }}
    </div>
</div> 


@endsection



@section('myScript')
@include('layout.layoutJS')


<script>

$(document).ready(function(){
    let i = 350;
    $("#UID").load("{{ url('/masterUID/'.$_SESSION['perangkat'].'.php') }}");
    $("#UID2").load("{{ url('/masterUID/'.$_SESSION['perangkat'].'.php') }}");
    setInterval(function() {
        $("#UID").load("{{ url('/masterUID/'.$_SESSION['perangkat'].'.php') }}");        
        $("#UID2").load("{{ url('/masterUID/'.$_SESSION['perangkat'].'.php') }}");        
    }, i);
});
</script> 

@endsection