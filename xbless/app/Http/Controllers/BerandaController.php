<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Slider;
use App\Models\Blog;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
class BerandaController extends Controller
{
	// index:halaman beranda
    public function index(){
    	$tgl = Carbon::now()->format('d F Y');
    	$jmluser   = User::count();
    	return view('backend/beranda/index',compact('tgl','jmluser'));
    }
}
