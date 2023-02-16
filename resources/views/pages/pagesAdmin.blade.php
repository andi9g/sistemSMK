@extends('layout.layoutAdmin')

@section('activekuAdmin')
    activeku
@endsection

@section('judul')
    <i class="fa fa-user"></i> Master Admin
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahMasterAdmin">
            <i class="fa fa-user"></i>Tambah Master Admin
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="tambahMasterAdmin" tabindex="-1" aria-labelledby="tambahMasterAdmin" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="tambahMasterAdmin">Tambah Master Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('tambah.admin') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" name="username" id="" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" name="nama" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="password" id="" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                    
                </form>
            </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <form action="{{ url()->current() }}" class="form-inline justify-content-end">
            <div class="input-group mb-3">
                <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" aria-describedby="button-addon2">
                <div class="input-group-append">
                  <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                </div>
            </div>
            
        </form>
    </div>
</div>

    <div class="card table-responsive">
        <div class="card-body ">
            <table class="table table-bordered table-hover table-sm table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Ket</th>
                    <th>Aksi</th>
                  </tr>
                </thead>

                <tbody>
                    @foreach ($admin as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->username }}</td>
                        <td class="text-center">
                            @php
                                if(Hash::check("admin".date('Y'), $item->password)){
                                    echo 'Default';
                                }else {
                                    echo '-';
                                }
                            @endphp
                        </td>
                        <td>{{ $item->perangkat }}</td>
                        <td>
                            <button type="button" class="badge badge-info border-0 " data-toggle="modal" data-target="#info{{ $item->id }}" >
                                <i class="fa fa-eye"></i> Detail
                            </button>

                            <form action="{{ route('reset.admin', [$item->id]) }}" method="post"    class="d-inline">
                                @csrf
                                <button type="submit" class="badge badge-success border-0" onclick="return confirm('yakin ingin dihapus?')">
                                    <i class="fa fa-key"></i> Reset 
                                </button>
                            </form>

                            <form action="{{ route('delete.admin', [$item->id]) }}" method="post"    class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="badge badge-danger border-0" onclick="return confirm('yakin ingin dihapus?')">
                                    <i class="fa fa-trash"></i> Hapus 
                                </button>
                            </form>

                            <button type="button" class="badge badge-primary border-0 " data-toggle="modal" data-target="#ubah{{ $item->id }}" >
                                <i class="fa fa-edit"></i> Ubah
                            </button>

                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="ubah{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title text-bold" id="exampleModalLabel">Ubah {{ $item->username }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <form action="{{ route('update.admin', [$item->id]) }}" method="post">
                                @csrf
                                @method('PUT')
                            
                            <div class="modal-body">
                                
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input type="text" readonly class="form-control" value="{{ $item->username }}">
                                </div>

                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ $item->nama }}">
                                </div>
                                


                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                Ubah Data
                            </button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                        
                    <!-- Modal -->
                    <div class="modal fade" id="info{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title text-bold" id="exampleModalLabel">Master {{ $item->perangkat }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="form-group">
                                    <label for="">PERANGKAT</label>
                                    <input type="text" readonly class="form-control" value="{{ $item->perangkat }}">
                                </div>


                                <div class="form-group">
                                    <label for="">KEY POST</label>
                                    <textarea class="form-control" readonly>{{ $item->key_post }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">COMPUTER ID</label>
                                    <textarea class="form-control" readonly>{{ $item->computerId }}</textarea>
                                </div>

                                @php
                                    $myUrl = url('/adminScan');
                                    $myUrl = str_replace("localhost", $_SERVER['REMOTE_ADDR'], $myUrl);
                                    $myUrl = str_replace("::1", "192.168.1.24", $myUrl);
                                @endphp
                                <div class="form-group">
                                    <label for="">LINKS</label>
                                    <textarea class="form-control" readonly>{{ $myUrl."/" }}</textarea>
                                </div>

                                <center>
                                    <p class="text-success text-lowercase">> Silahkan salin info master tersebut kedalam perangkat NodeMCU < </p>
                                </center>


                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>

            </table>
        </div>  
    </div>    


@endsection

@section('myScript')
    @include('layout.layoutJS')
@endsection