<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\StaffController;

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\RoleController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('manage.beranda');
});

Route::get('/manage/login', [LoginController::class, 'index'])->name('manage.login');
Route::post('/manage/login', [LoginController::class, 'checkLogin'])->name('manage.checklogin');
Route::group(['middleware' => ['auth', 'acl:web']], function () {
    Route::get('/manage', [BerandaController::class, 'index'])->name('manage.beranda');
    Route::get('/manage/logout', [LoginController::class, 'logout'])->name('manage.logout');

    // STAFF
    Route::get('manage/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('manage/staff/getdata', [StaffController::class, 'getData'])->name('staff.getdata');
    Route::get('manage/staff/tambah', [StaffController::class, 'tambah'])->name('staff.tambah');
    Route::get('manage/staff/detail/{id}', [StaffController::class, 'detail'])->name('staff.detail');
    Route::get('manage/staff/ubah/{id}', [StaffController::class, 'ubah'])->name('staff.ubah');
    Route::delete('manage/staff/hapus/{id?}', [StaffController::class, 'hapus'])->name('staff.hapus');
    Route::post('manage/staff/simpan', [StaffController::class, 'simpan'])->name('staff.simpan');


    //PERMISSION
    Route::get('manage/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('manage/permission/tambah', [PermissionController::class, 'tambah'])->name('permission.tambah');
    Route::get('manage/permission/ubah/{id}', [PermissionController::class, 'ubah'])->name('permission.ubah');
    Route::post('manage/permission/simpan/{id?}', [PermissionController::class, 'simpan'])->name('permission.simpan');
    Route::get('manage/permission/sidebar', [PermissionController::class, 'sidebar'])->name('permission.sidebar');

    //ROLE
    Route::get('manage/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('manage/role/lihat/{id}', [RoleController::class, 'lihat'])->name('role.lihat');
    Route::get('manage/role/tambah', [RoleController::class, 'form'])->name('role.tambah');
    Route::get('manage/role/ubah/{id}', [RoleController::class, 'form'])->name('role.ubah');
    Route::get('manage/role/user/{id}', [RoleController::class, 'formuser'])->name('role.user');
    Route::post('manage/role/tambah', [RoleController::class, 'save'])->name('role.tambah');
    Route::post('manage/role/ubah/{id}', [RoleController::class, 'save'])->name('role.ubah');
    Route::post('manage/role/user/{id}', [RoleController::class, 'saveuser'])->name('role.user');
    Route::post('manage/role/getdata', [RoleController::class, 'getData'])->name('role.getdata');
    Route::delete('manage/role/hapus/{id?}', [RoleController::class, 'delete'])->name('role.hapus');

    //PROFILE
    Route::get('manage/profil', [StaffController::class, 'profil'])->name('profil.index');
    Route::post('manage/profil/simpan', [StaffController::class, 'profilSimpan'])->name('profil.simpan');
    Route::get('manage/newpassword', [StaffController::class, 'profilPassword'])->name('profil.password');
    Route::post('manage/password/simpan', [StaffController::class, 'profilNewPassword'])->name('profil.simpanpassword');

    //POSITIONS
    Route::get('manage/positions', [PositionsController::class, 'index'])->name('positions.index');
    Route::post('manage/positions/getdata', [PositionsController::class, 'getData'])->name('positions.getdata');
    Route::get('manage/positions/tambah', [PositionsController::class, 'tambah'])->name('positions.tambah');
    Route::get('manage/positions/ubah/{id}', [PositionsController::class, 'ubah'])->name('positions.ubah');
    Route::post('manage/positions/simpan/{id?}', [PositionsController::class, 'simpan'])->name('positions.simpan');

    //GOALS
    Route::get('manage/goals', [GoalsController::class, 'index'])->name('goals.index');
    Route::post('manage/goals/getdata', [GoalsController::class, 'getData'])->name('goals.getdata');
    Route::get('manage/goals/tambah', [GoalsController::class, 'tambah'])->name('goals.tambah');
    Route::get('manage/goals/ubah/{id}', [GoalsController::class, 'ubah'])->name('goals.ubah');
    Route::post('manage/goals/simpan/{id?}', [GoalsController::class, 'simpan'])->name('goals.simpan');

  
});
