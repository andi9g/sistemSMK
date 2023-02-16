@extends('layout.layoutAdmin')

@php
  

@endphp

@section('activekuAlat')
    activeku
@endsection

@section('judul')
    <i class="fa fa-gear"></i> MASTER RFID
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#ruangan">
            Kelola Alat
        </button>

        <a href="{{ url('/alat', []) }}" class="btn btn-secondary">Refresh</a>
        
        <!-- Modal -->
        <div class="modal fade" id="ruangan" tabindex="-1" aria-labelledby="ruanganLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="ruanganLabel">Alat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('tambah.alat') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class='form-group'>
                                <label for='foralat' class='text-capitalize'>Nama Alat</label>
                                <input type='text' name='alat' id='foralat' class='form-control' placeholder='masukan nama alat'>
                            </div>
                        </div>    
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
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

<div class="row">

    @foreach ($alat as $data)
    <div class="col-md-3 m-3 rounded-lg text-center bg-dark">
            <h1 class="text-bold text-capitalize">{{ $data->namaalat }}</h1>
        
            <div class="row">
                <div class="col-sm-6 mx-0 px-0">
                    <button type="button" class="btn btn-success btn-block rounded-0" data-toggle="modal" data-target="#info{{ $data->idalat }}" ><i class="fa fa-eye"></i></button>
                </div>

                <div class="col-sm-6 mx-0 px-0">
                    <form action="{{ route('hapus.alat', [$data->idalat]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus alat {{ $data->namaalat }}?')" class="btn btn-danger btn-block rounded-0"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
            </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="info{{ $data->idalat }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title text-bold" id="exampleModalLabel">Master {{ $data->idalat }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                
                <div class="form-group">
                    <label for="">PERANGKAT</label>
                    <input type="text" readonly class="form-control" value="{{ $data->perangkat }}">
                </div>


                <div class="form-group">
                    <label for="">KEY POST</label>
                    <textarea class="form-control" readonly>{{ $data->key_post }}</textarea>
                </div>

                <div class="form-group">
                    <label for="">COMPUTER ID</label>
                    <textarea class="form-control" readonly>{{ $data->computerId }}</textarea>
                </div>

                @php
                    $myUrl = url('/scan');
                    $myUrl = str_replace("localhost", $_SERVER['REMOTE_ADDR'], $myUrl);
                    $myUrl = str_replace("::1", "Ipaddress or DNS", $myUrl);
                @endphp
                

                <div class="form-group">
                    <label for="">LINKS</label>
                    <textarea class="form-control" readonly>{{ $myUrl."/" }}</textarea>
                </div>

                <center>
                    <p class="text-success text-lowercase">> Silahkan salin info alat tersebut kedalam perangkat NodeMCU < </p>
                </center>


            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <form onclick="return confirm('lanjutkan proses reset IP')" action="{{ route('ubah.ip', [$data->perangkat]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-success">Reset IP</button>
            </form>
            </div>
        </div>
        </div>
    </div>

        
    @endforeach




    

</div>  


@endsection



@section('myScript')

@include('layout.layoutJS')

{{-- <script>
    
    $(document).ready(function(){
        $("#getUID").load("{{ url('alatUID/alatContainer.php') }}");
        setInterval(function() {
            $("#getUID").load("{{ url('alatUID/alatContainer.php') }}");

            var isi = document.getElementById("getUID").value;
            if(!isi || isi.length === 0){
                console.log('kosong');
                // document.forms["myForm"].submit();
            }
            
        }, 500);
    });
</script> --}}
    
@endsection