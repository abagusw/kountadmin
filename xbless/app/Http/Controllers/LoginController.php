<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;
class LoginController extends Controller
{
	// index:halaman login
    public function index(){
    	return view('backend/login/index');
    }

    // checkLogin::fungsi cek login
    public function checkLogin(Request $request){
    	$email    = $request->input("email");
        $password = $request->input("password");

        $akun = User::where("email",$email)->first();
        if ($akun) {
           if ($akun->active=='1') {
              if(Hash::check($password,$akun->password))  {
                  $akun->last_login_at = now();
                  $akun->save();
                  Auth::login($akun);
                  // Auth::loginUsingId($akun->id);
                  if ($akun->profil==null) {
                  	session(['profile' => $this->defaultProfilePhotoUrl($akun->name)]);
                  }else{
                  	session(['profile' => url($akun->profil)]);
                  }
                  return redirect()->route('manage.beranda');
              } else {
                  $desc = 'Login gagal. Cek kembali email dan password Anda.';
                  return redirect()->route('manage.login')->with('message', ['status'=>'danger','desc'=>$desc]);
              }
           }if ($akun->active=='2'){
              $desc = 'Login gagal. Akun anda telah terblokir. Silahkan hubungi Admin.';
              return redirect()->route('manage.login')->with('message', ['status'=>'danger','desc'=>$desc]);
           }else{
             $desc = 'Login gagal. Akun anda belum terverifikasi. Silahkan melakukan verifikasi terlebih dahulu.';
             return redirect()->route('manage.login')->with('message', ['status'=>'danger','desc'=>$desc]);
           }
        }else{
            $desc = 'Login gagal. Akun tidak ditemukan di sistem kami.';
            return redirect()->route('manage.login')->with('message', ['status'=>'danger','desc'=>$desc]);
        }      
    }
    // logout::fungsi logout
    public function logout(Request $request){
    	Auth::logout();
    	return redirect()->route('manage.login');
    }
    protected function defaultProfilePhotoUrl($name)
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=ffffff&background=ed4626&rounded=true&length=3';
    }
}
