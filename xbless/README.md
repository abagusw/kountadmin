# README #

This README would normally document whatever steps are necessary to get your application up and running.

### INSTALL ? ###

* Import dahulu database [folder db] 
* Arahkan CMD ke directory xbless ketikan composer update
* Kalau nggak bisa ..buka xbless/app/providers edit pada file AuthServiceProvider.php 
  buat komentar dahulu/hide pada code dibawah ini:
    $permissions = \App\Models\Permission::all();
    foreach($permissions as $permission) {
        Gate::define($permission->slug, function($user) use ($permission) {
            $return = false;
            foreach ($permission->role as $role) {
                $return = $user->hasRole($role->name);
                if($return) break; 
            }
            return $return;
        });
    },
    Lalu jalankan kembali step composer update
* Copy .env.example dengan perintah cp .env.example .env kemudian 
  php artisan key:generate

### HAPPY CODING ###

