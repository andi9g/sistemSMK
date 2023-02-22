<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\adminM;
use Hash;
use App\Models\superadmin;

class aksesC extends Controller
{
    public function csrf(Request $request)
    {
        echo csrf_token();
    }

    public function login()
    {
        return view('pages.pagesLogin');
    }

    public function ubahPassword(Request $request)
    {
        try{
            $id = $request->session()->get('id');
            $posisi = $request->session()->get('posisi');
            $password1 = $request->password1;
            $password2 = $request->password2;

            if($password1 === $password2) {
                
                $password = Hash::make($password1);
                if($posisi == 'admin') {
                    $update = adminM::where('id', $id)->update([
                        'password' => $password,
                    ]);
                }else if($posisi == 'superadmin') {
                    $update = superadmin::where('id', $id)->update([
                        'password' => $password,
                    ]);
                }else {
                    return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
                }
            }else {
                return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
            }
            
            if ($update) {
                session_start();
                session_destroy();
                $request->session()->flush();
                return redirect('login')->with('success', 'Silahkan login kembali menggunakan password baru anda');
            }else {
                return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
            }

        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function logout(Request $request)
    {
        session_start();
        session_destroy();
        $request->session()->flush();
        return redirect('/login');
    }

    public function proses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'sebagai' => 'required',
        ]);

        try{
            session_start();
            session_destroy();
            $request->session()->flush();

            $username = $request->username;
            $password = $request->password;
            $sebagai = $request->sebagai;

            if ($sebagai === 'admin') {
                $proses = adminM::where('username', $username);

                if ($proses->count() === 1) {
                    
                    $data = $proses->first();
                    if(Hash::check($password, $data->password)){
                        session_start();
                        $request->session()->put('login', true);
                        $request->session()->put('posisi', "admin");
                        $request->session()->put('nama', $data->nama);
                        $request->session()->put('id', $data->id);
                        
                        $_SESSION['perangkat'] = $data->perangkat;
                        $_SESSION['computerId'] = $data->computerId;
                        
                        return redirect('absen')->with('success', 'welcome');
                    }else {
                        return redirect('login')->with('toast_error', 'username atau password tidak benar');
                    }
                }else{
                    return redirect('login')->with('toast_error', 'username atau password tidak benar');
                }


            }elseif($sebagai === 'superadmin') {
                $proses = superadmin::where('username', $username);

                if($proses->count()===1) {
                    
                    $data = $proses->first();
                    if(Hash::check($password, $data->password)) {
                        $request->session()->put('login', true);
                        $request->session()->put('posisi', "superadmin");
                        $request->session()->put('nama', $data->nama);
                        $request->session()->put('id', $data->id);

                        return redirect('absen')->with('success', 'welcome');
                    }else {
                        return redirect('login')->with('toast_error', 'username atau password tidak benar');
                    }
                }else{
                    return redirect('login')->with('toast_error', 'username atau password tidak benar');
                }
            }else {
                return redirect('login')->with('toast_error', 'username atau password tidak benar');
            }


        }catch(\Throwable $th){
            return redirect('login')->with('toast_error', 'username atau password tidak benar');
        }
    }

}
