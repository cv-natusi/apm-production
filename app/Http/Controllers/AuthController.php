<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Users;
use Validator;
use Auth;
use Redirect;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if(!empty($this->data['user'])){
            return redirect()->route('home');
        }
        $this->data['next_url']     = empty($request->next_url) ? '' : $request->next_url;
        return view('Admin.master.login')->with('data', $this->data);
    }

    public function DoLogin(Request $request)
    {
        if (!empty($request->email)) {
            if (!empty($request->password)) {
                $user = Users::where('email', $request->email)->count();
                if ($user!= 0) {
                        $credentials = array(
                            'email'         => $request->email,
                            'password'      => $request->password
                        );
                        
                        $login = Auth::attempt($credentials);
                        if($login) {
                            $return = ['status'=>'success', 'message'=>'Login Berhasil'];
                        } else {
                            $return = ['status'=>'error', 'message'=>'Email atau Password Salah, silahkan Coba Kembali !!'];
                        }
                }else{
                    $return = ['status'=>'error', 'message'=>'Akun Tidak Dapat Ditemukan, Silahkan Cek Kembali Email Anda'];
                }
            }else{
                $return = ['status'=>'failed', 'message'=>'Password Harus Diisi'];
            }
        }else{
            $return = ['status'=>'failed', 'message'=>'Email Harus Diisi'];
        }
        return response()->json($return);
    }
}
