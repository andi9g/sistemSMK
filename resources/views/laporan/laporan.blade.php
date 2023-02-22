<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Laporan</title>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        .text-family {
            font-size: 10pt;
            text-align: center;
        }
        p {
            margin: 0;
            padding: 0;
        }
    </style>
  </head>
  <body>

    <table style="border-bottom: 2px solid;line-height: 0px;padding-bottom: 5px" width="100%">
        <tr>
            <td valign="center" width="60px">
                <img src="{{ url('resource_admin/gambar/smk.png', []) }}" width="55px" alt="">
            </td>
            <td style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;padding-top:10px">
                <h1>LAPORAN ABSENSI SISWA</h1> 
                <h2>SMKN 1 GUNUNG KIJANG</h2>
                <p>Jl. Poros Lome- Pulau Pucung, Malang Rapat, Kec. Gn. Kijang, Kabupaten Bintan, Kepulauan Riau 29151</p>
            </td>
        </tr>
    </table>
    <div style="padding: 8px 0px">
    @php
    
        if ($tanggal1 != $tanggal2) {
            echo "(".\Carbon\Carbon::parse($tanggal1)->isoFormat('DD/MM/Y').")"; 
            echo '  <b>s/d</b>  '; 
            echo "(".\Carbon\Carbon::parse($tanggal2)->isoFormat('DD/MM/Y').")"; 
        }else {
            echo "(".\Carbon\Carbon::parse($tanggal1)->isoFormat('DD/MM/Y').")";
        }
    @endphp
    </div>

    <table border="1" width="100%" style="border-collapse: collapse;font-size:11pt">
        <tr>
            <th width="3%" rowspan="2">NO</th>
            <th nowrap rowspan="2">NAMA SISWA</th>
            <th width="3%" rowspan="2">JK</th>
            <th colspan="{{count($tanggaltampil)}}">INDIKATOR ABSENSI</th>
            <th width="3%" rowspan="2">H</th>
            <th width="3%" rowspan="2">I</th>
            <th width="3%" rowspan="2">A</th>
            <th width="3%" rowspan="2">S</th>
            <th width="3%" rowspan="2">T</th>
        </tr>
        <tr>
            @foreach ($tanggaltampil as $item)
                <th>{{$item}}</th>
            @endforeach
        </tr>

        @php
            $i1 = 0;
            $i2 = 0;
            $i3 = 0;
            $i4 = 0;
        @endphp

        @foreach ($data as $d)
        <tr style="background: rgb(205, 255, 126)">
            <td colspan="{{8 + count($tanggaltampil)}}" style="font-size: 15pt">
                <b>JURUSAN {{strtoupper($d["jurusan"])}}</b>
            </td>
        </tr>

        @foreach ($d['kelas'] as $k)
            <tr style="background: rgb(235, 235, 235)" style="font-size: 13pt">
                <td colspan="{{8 + count($tanggaltampil)}}">
                    <b>KELAS {{strtoupper($k["namakelas"])}}</b>
                </td>
            </tr>

            @php
            $i3 = 1;
            @endphp
            @foreach ($k['siswa'] as $s)
                <tr>
                    <td align="center">{{$i3++}}</td>
                    <td>{{ucwords($s["namasiswa"])}}</td>
                    <td align="center">{{$s["jk"]}}</td>

                    @php
                        $hadir = 0;
                        $izin = 0;
                        $sakit = 0;
                        $alfa = 0;
                        $lambat = 0;
                    @endphp
                    @foreach ($s['absen'] as $a)
                        <td @if ($a['ket']=="A")
                        style="background: rgb(255, 172, 172)"
                        @elseif($a['ket']=="S")
                        style="background: rgb(169, 209, 255)"
                        @elseif($a['ket']=="I")
                        style="background: rgb(255, 249, 166)"
                        @elseif($a['ket']=="H")
                        style="background: rgb(164, 255, 160)"
                        @endif class="text-family">

                        @php
                        if($a['ket']=="H"){
                            if ($a['keterlambatan']==true){
                                echo "T";
                            }
                        }
                        @endphp
                        
                    </td>


                    @php
                        if ($a['ket']=="A"){
                            $alfa = $alfa + 1;
                        }                            
                        elseif($a['ket']=="S"){
                            $sakit = $sakit + 1;
                        }
                        elseif($a['ket']=="I"){
                            $izin = $izin + 1;
                        }
                        elseif($a['ket']=="H"){
                            $hadir = $hadir + 1;
                            if ($a['keterlambatan']==true){
                                $lambat = $lambat + 1;
                            }
                        }
                        
                    @endphp

                    @endforeach
                    <td align="center">&nbsp;{{$hadir}}&nbsp;</td>
                    <td align="center">&nbsp;{{$izin}}&nbsp;</td>
                    <td align="center">&nbsp;{{$alfa}}&nbsp;</td>
                    <td align="center">&nbsp;{{$sakit}}&nbsp;</td>
                    <td align="center">&nbsp;{{$lambat}}&nbsp;</td>

                </tr>
            @endforeach
        @endforeach

        



        @php
            $i1++;
        @endphp
        @endforeach

    </table>

    <br>



    <table width="100%" style="border-collapse: collapse;font-size:11pt">
        <tr>
            <td valign="top">
                <table>
                    <tr>
                        <td style="background: rgb(255, 172, 172)">&emsp;</td>
                        <td>:</td>
                        <td>Indikator Alfa (A)</td>
                    </tr>
                    <tr>
                        <td style="background: rgb(164, 255, 160)">&emsp;</td>
                        <td>:</td>
                        <td>Indikator Hadir (H)</td>
                    </tr>
                    <tr>
                        <td style="background: rgb(255, 249, 166)">&emsp;</td>
                        <td>:</td>
                        <td>Indikator Izin (I)</td>
                    </tr>
                    <tr>
                        <td style="background: rgb(169, 209, 255)">&emsp;</td>
                        <td>:</td>
                        <td>Indikator Sakit (S)</td>
                    </tr>
                    <tr>
                        <td>( T )</td>
                        <td>:</td>
                        <td>Terlambat</td>
                    </tr>
                    
                </table>

            </td>
            <td valign="top" width="40%" align="center">
                <p>Kepala Sekolah</p>
                <p>SMKN 1 Gunung Kijang</p>
                <br><br><br><br>
                <p>Mustafa Kamal, S.Pd</p>

            </td>
        </tr>
    </table>



  </body>
</html>