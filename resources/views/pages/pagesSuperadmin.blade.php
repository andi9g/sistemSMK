@extends('layout.layoutAdmin')

@section('activekuHome')
    activeku
@endsection

@section('judul')
    <i class="fa fa-book"></i> Data Buku
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahSuperadmin">
            Tambah Superadmin
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="tambahSuperadmin" tabindex="-1" aria-labelledby="tambahSuperadminLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="tambahSuperadminLabel">Superadmin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('tambah.superadmin') }}" method="post">
                    @csrf
                    @method("POST")
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="masukan username">
                    </div>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="nama" class="form-control" placeholder="masukan username">
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="masukan username">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Proses</button>
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

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-striped table-sm">
                <thead class="bg-secondary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($superadmin as $item)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-capitalize">{{$item->nama}}</td>
                        <td class="text-bold text-center">{{$item->username}}</td>
                        <td class="text-center">
                            @if (Hash::check("superadmin".date('Y'), $item->password))
                                Default
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('reset.superadmin', [$item->id]) }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit" class="badge badge-warning border-0">
                                    <i class="fa fa-key"></i> Reset
                                </button>

                            </form>

                            <button type="button" class="badge badge-primary border-0" data-toggle="modal" data-target="#update{{$item->id}}">
                                <i class="fa fa-edit"></i> Edit
                            </button>

                            <form action="{{ route('hapus.superadmin', [$item->id]) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="badge badge-danger border-0">
                                    <i class="fa fa-key"></i> Reset
                                </button>

                            </form>

                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="update{{$item->id}}" tabindex="-1" aria-labelledby="update{{$item->id}}Label" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="update{{$item->id}}Label">Superadmin</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <form action="{{ route('update.superadmin', [$item->id]) }}" method="post">
                                @csrf
                                @method("PATCH")
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input type="text" value="{{$item->username}}" disabled class="form-control" placeholder="masukan username">
                                </div>
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="nama" value="{{$item->nama}}" class="form-control" placeholder="masukan username">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Proses</button>
                            </div>
                            </form>
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