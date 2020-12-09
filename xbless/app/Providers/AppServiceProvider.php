<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\About;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Carbon::setLocale('id');
         $perusahaan = About::first();
         if($perusahaan){
            View::share('perusahaan', $perusahaan);
         }else{
             dd('Informasi tentang perusahaan belum dibuat.');
         }
    }
}
