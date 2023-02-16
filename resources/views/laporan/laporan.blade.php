<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">


    <title>Laporan</title>
  </head>
  <body>

    <table>
        <tr>
            <td valign="center" width="100px" class="p-1">
                <img src="{{ url('img/logo.png', []) }}" class="w-100" alt="">
            </td>
            <td style="line-height: 20px">
                <h4>SEKOLAH TINGGI TEKNOLOGI INDONESIA TANJUNGPINANG</h3>
                <h4>JL. POMPA AIR NO. 28 KM 2,5 TANJUNGPINANG - KEPULAUAN RIAU 29122</h4>
                <p>TELP. (0771) 317780 Website : http://www.sttindonesia.ac.id Email : info@sttindonesia.ac.id</p>
            </td>
        </tr>
    </table>
    

    <center><h5 class="mt-1">LAPORAN ABSENSI</h5></center>
    
    <table style="width:100%;font-size: 13pt;line-height: 20px">
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td style="width: 160px"><b>Kode Matkul</b></td>
                        <td>:</td>
                        <td>{{ $data->kode_matkul }}</td>
                    </tr>
                    <tr>
                        <td><b>Nama Matkul</b></td>
                        <td>:</td>
                        <td>{{ $data->nmatkul }}</td>
                    </tr>
                    <tr>
                        <td><b>Semester/TA</b></td>
                        <td>:</td>
                        <td>{{ ucwords($data->namasmt) }} / {{ ucwords($data->tahun) }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td style="width: 160px"><b>Nama Dosen</b></td>
                        <td>:</td>
                        <td>{{ $data->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <td><b>NIDN</b></td>
                        <td>:</td>
                        <td>{{ $data->nidn }}</td>
                    </tr>
                    <tr>
                        <td><b>Nama Kelas</b></td>
                        <td>:</td>
                        <td>{{ $data->namakelas }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <table border="1" style="border-collapse: collapse;width:100%;font-size:11pt">
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">NIM</th>
            <th rowspan="2">Nama</th>
            <th colspan="{{count($tanggal)}}">ABSENSI</th>
            <th rowspan="2" width="30px">H</th>
            <th rowspan="2" width="30px">I</th>
            <th rowspan="2" width="30px">S</th>
            <th rowspan="2" width="30px">A</th>
        </tr>
        <tr>
            @foreach ($tanggal as $item)
                <th width="30px">{{$loop->iteration}}</th>
            @endforeach
        </tr>

        @foreach ($peserta as $mhs)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td style="margin: 0 2px">{{$mhs->nim}}</td>
                <td>{{$mhs->nama_mhs}}</td>
                @foreach ($tanggal as $tgl)
                    @php
                        $id_peserta = $mhs->id_peserta;
                        $idkelas_mhs = $mhs->idkelas_mhs;
                        $cek = DB::table('absen')->join('absendetail', 'absendetail.idabsen','=','absen.id_absen')
                        ->where('absen.idkelas_mhs', $mhs->idkelas_mhs)
                        ->where('absen.id_peserta', $mhs->id_peserta)
                        ->select('absendetail.created_at');
                    @endphp
                    @if ($cek->count() === 0)
                        <td style="background-color: rgba(252, 195, 195, 0.384)"></td>
                    @elseif($cek->count() > 0)
                        @php
                            $cek2 = DB::table('absen')->join('absendetail', 'absendetail.idabsen','=','absen.id_absen')
                            ->where('absen.idkelas_mhs', $mhs->idkelas_mhs)
                            ->where('absen.id_peserta', $mhs->id_peserta)
                            ->where('absendetail.created_at', 'like', "$tgl%")
                            ->select('absendetail.idkehadiran');
                        @endphp
                        @if ($cek2->count() === 0)
                            <td style="background-color: rgba(252, 195, 195, 0.384)"></td>
                        @else
                            @if ($cek2->first()->idkehadiran == 0)
                            <td style="background-color: rgba(252, 195, 195, 0.384)"></td>
                            @elseif($cek2->first()->idkehadiran == 1)
                            <td style="background-color: rgb(196, 255, 196)"></td>
                            @elseif($cek2->first()->idkehadiran == 2)
                            <td style="background-color: rgb(255, 249, 169)"></td>
                            @elseif($cek2->first()->idkehadiran == 3)
                            <td style="background-color: rgb(137, 191, 223)"></td>
                            @endif
                        @endif

                    @endif
                @endforeach
                @php
                    $A = count($tanggal);
                    $cekHadir = DB::table('absen')->join('absendetail', 'absendetail.idabsen','=','absen.id_absen')
                        ->where('absen.idkelas_mhs', $mhs->idkelas_mhs)
                        ->where('absen.id_peserta', $mhs->id_peserta)
                        ->where('absendetail.idkehadiran', '1')->count();
                    $H = $cekHadir;
                    $A = $A - $H;
                    $cekizin = DB::table('absen')->join('absendetail', 'absendetail.idabsen','=','absen.id_absen')
                        ->where('absen.idkelas_mhs', $mhs->idkelas_mhs)
                        ->where('absen.id_peserta', $mhs->id_peserta)
                        ->where('absendetail.idkehadiran', '2')->count();
                    $I = $cekizin;
                    $A = $A - $I;
                    $cekSakit = DB::table('absen')->join('absendetail', 'absendetail.idabsen','=','absen.id_absen')
                        ->where('absen.idkelas_mhs', $mhs->idkelas_mhs)
                        ->where('absen.id_peserta', $mhs->id_peserta)
                        ->where('absendetail.idkehadiran', '3')->count();
                    $S = $cekSakit;
                    $A = $A - $S;
                    $cekAlpha = DB::table('absen')->join('absendetail', 'absendetail.idabsen','=','absen.id_absen')
                        ->where('absen.idkelas_mhs', $mhs->idkelas_mhs)
                        ->where('absen.id_peserta', $mhs->id_peserta)
                        ->where('absendetail.idkehadiran', '0')->count();
                    $A = $A;
                @endphp
                <td class="text-center">{{$H}}</td>
                <td class="text-center">{{$I}}</td>
                <td class="text-center">{{$S}}</td>
                <td class="text-center">{{$A}}</td>
            </tr>
            
        @endforeach

    </table>










    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

  </body>
</html>