<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Thay vì dùng vòng lặp foreach như thầy dạy (cách cũ)
        // Bạn dùng Gate::before để check động bằng hàm của Spatie
        Gate::before(function ($user, $ability) {
            // hasPermissionTo là hàm của Spatie, nó sẽ tự check trong DB
            return $user->hasPermissionTo($ability) ? true : null;
        });

        // $permissions = Permission::all();
        // foreach ($permissions as $permission) {
        //     Gate::define($permission->name, function (User $user) use ($permission) {
        //         return $user->hasPermissionTo($permission->name);
        //     });
        // }
    }
}
