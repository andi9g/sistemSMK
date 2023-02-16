@extends('layout.layoutAdmin')

@section('activekuCekCard')
    activeku
@endsection

@section('judul')
    <i class="fa fa-check"></i> Identitas Kartu
@endsection

@section('content')
  
  
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <p class="text-success text-center text-uppercase">Silahkan Scan Kartu Pada RFID Reader</p>
      <form {{ route('cek.card') }} id="myForm" method="post">
        @csrf
        <div class="form-group">
            <textarea name="keyword" id="UID" hidden rows="1" class="form-control text-center justify-content-center align-content-center pl-1" style="outline:none;background: rgba(158, 158, 158, 0.329);border:1px solid rgba(146, 146, 146, 0.596);border-radius:5px;resize:none;text-align:center;width: fit-content" placeholder="" readonly></textarea>
        </div>
      </form>
      <div class="card">
        <div class="card-header text-center text-bold">
          IDENTITAS TAG/CARD
        </div>

        <div class="card-body">
          <table class="table table-striped table-bordered table-hover">
              <tr>
                <th>UID</th>
                <td>{{ empty($data->uid)?"-":$data->uid }}</td>
              </tr>
              <tr>
                <th>NIS</th>
                <td>{{ empty($data->nis)?"-":$data->nis }}</td>
              </tr>
              <tr>
                <th>NAMA</th>
                <td>{{ empty($data->namasiswa)?"-":$data->namasiswa }}</td>
              </tr>
              <tr>
                <th>KET</th>
                <td class="text-bold">{{ strtoupper(empty($data->ket)?"-":"Kartu ".$data->ket) }}</td>
              </tr>
          </table>
        </div>

      </div>
    </div>
  </div>


@endsection


@section('myScript')
@include('layout.layoutJS')
<script>

$(document).ready(function(){
    let i = 350;
    $("#UID").load("{{ url('/masterUID/'.$_SESSION['perangkat'].'.php') }}");
    setInterval(function() {
        $("#UID").load("{{ url('/masterUID/'.$_SESSION['perangkat'].'.php') }}");
        
        var isi = document.getElementById("UID").value;
            if(isi || isi.length > 0){
              var element = document.getElementById("badan");
                  element.classList.remove("loaded");
                document.forms["myForm"].submit();
                document.getElementById("UID").value="";
            }
        
    }, i);
});
</script> 

@endsection