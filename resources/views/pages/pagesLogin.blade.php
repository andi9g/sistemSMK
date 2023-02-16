@extends('layout.layout')

@section('judul')
    Login
@endsection

@section('activeLogin')
    disabled
@endsection


@section('content')
<div class="card mx-5 shadow-sm border-success" style="border:2px solid">
    <div class="card-body bg-light">
        <form action="{{ route('login.proses') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 p-4" style="">
                    <div class="form-group text-left">
                        <label class="sr-only" for="inlineFormInputGroup">Username</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text bg-primary text-white">U</div>
                            </div>
                            <input type="text" name="username" class="form-control" id="inlineFormInputGroup" placeholder="Username">
                        </div>
                    </div>
        
                    <div class="form-group text-left">
                        <label class="sr-only" for="inlineFormInputGroup2">Password</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text bg-primary text-white">P</div>
                            </div>
                            <input type="password" name="password" class="form-control" id="inlineFormInputGroup2" placeholder="Password">
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <select name="sebagai" id="" class="form-control" required>
                                <option>-- Pilih --</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>
        
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-success btn-block">
                                Masuk
                            </button>
                        </div>
                    </div>
        
                </div>
            </div>
            
        
        </form>

    </div>
</div>

@endsection



@section('myScript')
    
<script>

    $(document).ready(function(){
        $("#getUID").load("{{ url('UIDContainer.php') }}");
        setInterval(function() {
            $("#getUID").load("{{ url('UIDContainer.php') }}");

            var isi = document.getElementById("getUID").value;
            if(!isi || isi.length === 0){
                console.log('kosong');
                // document.forms["myForm"].submit();
            }
            
        }, 500);
    });
    function coba(hasil){
        var coba = hasil.value;
        console.log(coba);
    }
</script>

@endsection

@section('myScript')
@include('layout.layoutJS')
@endsection