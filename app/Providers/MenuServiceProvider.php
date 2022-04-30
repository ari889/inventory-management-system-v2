<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('modules', function(){
            return new Module();
        });
        $this->app->bind('permissions', function(){
            return new Permission();
        });
        $loader = AliasLoader::getInstance();
        $loader->alias('Module',Module::class);
        $loader->alias('Permission',Permission::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if(!App::runningInConsole() && count(Schema::getColumnListing('settings'))){
            if(Auth::check()){
                $role_id = auth()->user()->role_id;

                $menus = Module::doesntHave('parent')
                            ->orderBy('order','asc')
                            ->with('children');
                $permissions = Permission::select('slug');

                if($role_id != 1)
                {
                    $menus->whereHas('module_role', function($q) use ($role_id){
                        $q->where('role_id',$role_id);
                    });
                    $permissions->whereHas('permission_role', function($q) use ($role_id){
                        $q->where('role_id',$role_id);
                    });
                }

                $this->user_menu = $menus->get();
                $this->user_permission = $permissions->get();

                if(!empty($this->user_menu))
                {
                    Session::put('menu',$this->user_menu);
                }

                $permission = [];

                if(!empty($this->user_permission))
                {
                    foreach ($this->user_permission as $value) {
                        array_push($permission,$value->slug);
                    }

                    Session::put('permission',$permission);
                }
            }
        }
    }
}
