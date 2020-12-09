<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class StaffController extends Controller
{
      protected $original_column = array(
        1 => "profil",
        2 => "name",
        3 => "jk",
        4 => "email",
        5 => "no_hp",
        6 => "active",
      );

      public function status()
      {
         $value = array('1'=>'Aktif' ,'0'=>'Tidak Aktif','2'=>'Blokir');
        return $value;
      }
      public function statusFilter()
      {
         $value = array('99'=>'Semua','1'=>'Aktif' ,'0'=>'Tidak Aktif','2'=>'Blokir');
        return $value;
      }

      public function index()
      {
         $statusfilter = $this->statusFilter();
          if(session('statusfilter')==""){
            $selectedstatusfilter = "99";
          }else if(session('statusfilter')=="99"){
             $selectedstatusfilter = "99";
          }else{
            $selectedstatusfilter = session('statusfilter');
          }
          return view('backend/staff/index',compact('selectedstatusfilter','statusfilter'));
      }
   
      public function getData(Request $request)
      {
          $limit = $request->length;
          $start = $request->start;
          $page  = $start +1;
          $search = $request->search['value'];

          $request->session()->put('statusfilter', $request->statusfilter);

          $admins = User::select('id','name','email','jk','profil','no_hp','active','last_login_at','created_at');
          if($request->statusfilter !='99'){
              $admins->where('active',$request->statusfilter);
          }

          if(array_key_exists($request->order[0]['column'], $this->original_column)){
             $admins->orderByRaw($this->original_column[$request->order[0]['column']].' '.$request->order[0]['dir']);
          }
           else{
            $admins->orderBy('id','DESC');
          }
           if($search) {
            $admins->where(function ($query) use ($search) {
                    $query->orWhere('name','LIKE',"%{$search}%");
                    $query->orWhere('email','LIKE',"%{$search}%");
            });
          }
          $totalData = $admins->get()->count();
      
          $totalFiltered = $admins->get()->count();
      
          $admins->limit($limit);
          $admins->offset($start);
          $data = $admins->get();
          foreach ($data as $key=> $admin)
          {
            $enc_id = $this->safe_encode(Crypt::encryptString($admin->id));
            $action = "";
           
            $action.="";
         
            if($request->user()->can('staff.detail')){
              $action.='<a href="'.route('staff.detail',$enc_id).'" class="btn btn-success btn-xs icon-btn md-btn-flat product-tooltip" title="Detail" data-original-title="Show"><i class="ion ion-md-eye"></i></a>&nbsp';
            }
            if($request->user()->can('staff.ubah')){
              $action.='<a href="'.route('staff.ubah',$enc_id).'" class="btn btn-warning btn-xs icon-btn md-btn-flat product-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>&nbsp;';
            }
            if($request->user()->can('staff.hapus')){
              $action.='<a href="#" onclick="deleteStaff(this,\''.$enc_id.'\')" class="btn btn-danger btn-xs icon-btn md-btn-flat product-tooltip" title="Hapus"><i class="ion ion-md-close"></i></a>&nbsp;';
            }
            if ($admin->active=='1') {
               $status = '<span class="badge badge-outline-success">Aktif</span>';
            }else if($admin->active=='0'){
               $status = '<span class="badge badge-outline-danger">Tidak Aktif</span>';
            }else if($admin->active=='2'){
               $status = '<span class="badge badge-outline-default">Blokir</span>';
            }

            if ($admin->profil==null) {
              $profile=$this->defaultProfilePhotoUrl($admin->name);
            }else{
              $profile=url($admin->profil);
            }

            $admin->no             = $key+$page;
            $admin->photo         = '<div class="media align-items-center"><img class="ui-w-40 d-block" src="'.$profile.'" alt=""></div>';
            $admin->id             = $admin->id;
            $admin->name           = $admin->name;
            $admin->email          = $admin->email;
            $admin->last_login_at  = $admin->last_login_at;
            $admin->status         = $status;
            $admin->action         = $action;
          }
          if ($request->user()->can('staff.index')) {
            $json_data = array(
                      "draw"            => intval($request->input('draw')),  
                      "recordsTotal"    => intval($totalData),  
                      "recordsFiltered" => intval($totalFiltered), 
                      "data"            => $data
                      );
          }else{
             $json_data = array(
                      "draw"            => intval($request->input('draw')),  
                      "recordsTotal"    => 0,  
                      "recordsFiltered" => 0, 
                      "data"            => []  
                      );
          }    
          return json_encode($json_data); 
      }
     
    
      private function cekExist($column,$var,$id)
      {
       $cek = User::where('id','!=',$id)->where($column,'=',$var)->first();
       return (!empty($cek) ? false : true);
      }
      
      function safe_encode($string) {
	
        $data = str_replace(array('/'),array('_'),$string);
        return $data;
      }
 
	    function safe_decode($string,$mode=null) {
		
		   $data = str_replace(array('_'),array('/'),$string);
        return $data;
      }
      
      public function tambah()
      {
        $status= $this->status();
        $selectedstatus   = '1';
        return view('backend/staff/form',compact('status','selectedstatus'));
      }
     
      public function ubah($enc_id)
      {
        $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
       
         
        if ($dec_id) {
          $status= $this->status();
          $staff= User::find($dec_id);
          $selectedstatus   =  $staff->active;
          if ($staff->profil==null) {
                  	 
          	$profile=$this->defaultProfilePhotoUrl($staff->name);
          }else{
          	$profile=url($staff->profil);
          }

          return view('backend/staff/form',compact('status','enc_id','selectedstatus','staff','profile'));
        } else {
        	return view('errors/noaccess');
        }
      }

      protected function defaultProfilePhotoUrl($name)
   	  {
        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=ffffff&background=ed4626&rounded=true&length=3';
      }
       public function detail(Request $request,$enc_id)
      {
        

        // $agent = new Agent();
        // $device = $agent->device();
        // $platform = $agent->platform();
        // $browser = $agent->browser();
        // $desktop = $agent->isDesktop();
        // $agent->isMobile();
        // $agent->isTablet();
        // // dd($request->ip());
        // $browser = $agent->browser();
        // $versionx = $agent->version($browser);
        // // dd($versionx);

        // $platform = $agent->platform();
        // $version = $agent->version($platform);

        $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
       
         
        if ($dec_id) {
          $status= $this->status();
          $staff= User::find($dec_id);
          $selectedstatus   =  $staff->active;
          if ($staff->profil==null) {
                     
            $profile=$this->defaultProfilePhotoUrl($staff->name);
            
          }else{
            $profile=url($staff->profil);
          }
          Carbon::setLocale('id');
          if ($staff->active=='1') {
             $status = '<span class="badge badge-outline-success">Aktif</span>';
          }else if($staff->active=='0'){
             $status = '<span class="badge badge-outline-danger">Tidak Aktif</span>';
          }else if($staff->active=='2'){
             $status = '<span class="badge badge-outline-default">Blokir</span>';
          }
          $tgl_last_login = $staff->last_login_at==null? '-':Carbon::parse($staff->last_login_at)->format('d/m/Y H:i');
          $tgl_registrasi = $staff->created_at==null? '-':Carbon::parse($staff->created_at)->format('d/m/Y H:i');
        
          return view('backend/staff/detail',compact('status','enc_id','selectedstatus','staff','profile','tgl_last_login','tgl_registrasi','status'));
        } else {
        	return view('errors/noaccess');
        }
      }
    
      public function simpan(Request $req)
      {     
          $enc_id     = $req->enc_id;
          $dataprofil = $req->image;
          $dir        = 'media/profile/';
          if ($enc_id != null) {
            $dec_id = $this->safe_decode(Crypt::decryptString($enc_id));
          }else{
            $dec_id = null;
          }
          $cek_mail = $this->cekExist('email',$req->email,$dec_id);

         if(!$cek_mail)
          {
              $json_data = array(
                "success"         => FALSE,
                "message"         => 'Mohon maaf. Email yang Anda masukan sudah terdaftar pada sistem.'
              );
          }
          else {
          if($enc_id){
             $staff = User::find($dec_id);
             
             if ($dataprofil != null) {
              list($type, $dataprofil) = explode(';', $dataprofil);
              list(, $dataprofil)      = explode(',', $dataprofil);
              if(!file_exists($dir)){
                  mkdir($dir, 0777, true);
                   chmod($dir, 0777);
              }
              $dataprofil = base64_decode($dataprofil);
              $image_name= 'profile_'.time().'.png';
              $path = $dir. $image_name;
              $paths  = url($dir . $image_name);
              file_put_contents($path, $dataprofil);
              chmod($path, 0664);
              if ($staff->profil != null) {
                $db_path = $staff->profil;
                if (file_exists($db_path)) {
                  unlink($db_path);
                }
              }
              $staff->profil      = $path;
            }      
            $staff->name       = $req->name;
            $staff->email      = $req->email;
            if ($req->password !='') {
                    $staff->password   = bcrypt($req->password);
            }
            $staff->no_hp       = $req->no_hp;
            $staff->jk          = $req->jk;
            $staff->active      = $req->status;
            $staff->save();
            if ($staff) {
              $json_data = array(
                    "success"         => TRUE,
                    "message"         => 'Data berhasil diperbarui.'
                 );
            }else{
               $json_data = array(
                    "success"         => FALSE,
                    "message"         => 'Data gagal diperbarui.'
                 );
            }
            
          }else{

            if ($dataprofil != null) {
               
                list($type, $dataprofil) = explode(';', $dataprofil);
                list(, $dataprofil)      = explode(',', $dataprofil);
              

                if(!file_exists($dir)){
                    mkdir($dir, 0777, true);
                     chmod($dir, 0777);
                }
                 
                $dataprofil = base64_decode($dataprofil);
                $image_name= 'profile_'.time().'.png';
                $path = $dir. $image_name;
                $paths  = url($dir . $image_name);
                file_put_contents($path, $dataprofil);
                chmod($path, 0664);
          }else{
            $path = null;
          }

            $staff = new User;
            $staff->name        = $req->name;
            $staff->email       = $req->email;
            $staff->password    = bcrypt($req->password);
            $staff->no_hp       = $req->no_hp;
            $staff->jk          = $req->jk;
            $staff->profil      = $path;
            $staff->active      = $req->status;
            $staff->save();
            if($staff) {
              $json_data = array(
                    "success"         => TRUE,
                    "message"         => 'Data berhasil ditambahkan.'
              );
            }else{
              $json_data = array(
                    "success"         => FALSE,
                    "message"         => 'Data gagal ditambahkan.'
              );
            }
            
          }
        }
           return json_encode($json_data); 
      }

      public function hapus(Request $req,$enc_id)
      {
        $dec_id   = $this->safe_decode(Crypt::decryptString($enc_id));
        $staff    = User::find($dec_id);
        $cekexist = RoleUser::where('user_id',$dec_id)->first();
        if($staff) {
            if ($cekexist) {
                return response()->json(['status'=>"failed",'message'=>'Gagal menghapus data dikarenakan ID STAFF tersebut masih digunakan oleh role/akses. Silahkan hapus dahulu di Keamanan->Manajemen Akses->Daftar User jika ingin menghapus data ini kembali.']);
            }else{
                $staff->delete();
                return response()->json(['status'=>"success",'message'=>'Data Berhasil dihapus.']);
            }
        }else {
            return response()->json(['status'=>"failed",'message'=>'Gagal menghapus data. Silahkan ulangi kembali.']);
        }
      }

      public function profil()
      {
        $profil = User::find(Auth()->user()->id);
        if ($profil) {
          if ($profil->profil==null) {
                     
            $profile=$this->defaultProfilePhotoUrl($profil->name);
          }else{
            $profile=url($profil->profil);
          }
          return view('backend/profil/index',compact('profil','profile'));
        }else{
          Abort('404');
        }
      }
     
      public function profilSimpan(Request $req)
      {     
     
          $dataprofil = $req->image;
          $dir        = 'media/profile/';
          $id         = Auth()->user()->id;
          $cek_mail = $this->cekExist('email',$req->email,$id);

         if(!$cek_mail)
          {
              $json_data = array(
                "success"         => FALSE,
                "message"         => 'Mohon maaf. Email yang Anda masukan sudah terdaftar pada sistem.'
              );
          }
          else {
          if($id){
             $profil = User::find($id);
             
             if ($dataprofil != null) {
              list($type, $dataprofil) = explode(';', $dataprofil);
              list(, $dataprofil)      = explode(',', $dataprofil);
              if(!file_exists($dir)){
                  mkdir($dir, 0777, true);
                   chmod($dir, 0777);
              }
              $dataprofil = base64_decode($dataprofil);
              $image_name= 'profile_'.time().'.png';
              $path = $dir. $image_name;
              $paths  = url($dir . $image_name);
              file_put_contents($path, $dataprofil);
              chmod($path, 0664);
              if ($profil->profil != null) {
                $db_path = $profil->profil;
                if (file_exists($db_path)) {
                  unlink($db_path);
                }
              }
              $profil->profil      = $path;
                if ($profil->profil==null) {
                  session(['profile' => $this->defaultProfilePhotoUrl($akun->name)]);
                }else{
                  session(['profile' => url($profil->profil)]);
                } 
            }      
            $profil->name       = $req->name;
            $profil->email      = $req->email;
            $profil->no_hp       = $req->no_hp;
            $profil->jk          = $req->jk;
            $profil->save();
            if ($profil) {
              $json_data = array(
                    "success"         => TRUE,
                    "message"         => 'Data berhasil diperbarui.'
                 );
            }else{
               $json_data = array(
                    "success"         => FALSE,
                    "message"         => 'Data gagal diperbarui.'
                 );
            }
            
          }
        }
           return json_encode($json_data); 
      }
      
      public function profilPassword()
      {
        return view('backend/profil/newpassword');
      }

      public function profilNewPassword(Request $req)
      {     
          
          $profil = User::find(auth()->user()->id);
          //cek password lama
          if(Hash::check($req->password_old,$profil->password))  {
              $profil->password   = bcrypt($req->password_new);
              $profil->save();
              if ($profil) {
                $json_data = array(
                      "success"         => TRUE,
                      "message"         => 'Password berhasil diperbarui.'
                   );
              }else{
                 $json_data = array(
                      "success"         => FALSE,
                      "message"         => 'Password gagal diperbarui.'
                   );
              }
          }else {
              $json_data = array(
                    "success"         => FALSE,
                    "message"         => 'Password Lama yang Anda masukan salah.'
              );
          }
          return json_encode($json_data); 
      }
}
