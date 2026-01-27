<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\AttributeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management Routes
    Route::prefix('admin/user')->name('admin.user.')->group(function () {

        Route::get('/', [UserController::class, 'index'])->name('index')->can('user.view');

        Route::get('/create', [UserController::class, 'create'])->name('create')->can('user.create');

        Route::post('/', [UserController::class, 'store'])->name('store')->can('user.create');

        Route::post('/action', [UserController::class, 'action'])->name('action');

        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit')->can('user.edit');

        Route::put('/{user}', [UserController::class, 'update'])->name('update')->can('user.edit');

        Route::delete('/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy')->can('user.delete');
    });

    // Chỉ ai có quyền 'permission.view' mới được nhìn thấy nhóm này
    Route::prefix('admin/permission')->middleware('can:permission.view')->group(function () {

        // Trang quản lý chính (list + create)
        Route::get('/', [PermissionController::class, 'index'])->name('admin.permission.index');

        // Submit form create
        Route::post('/', [PermissionController::class, 'store'])
            ->name('admin.permission.store')
            ->can('permission.create');

        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
            ->name('admin.permission.edit')
            ->can('permission.edit');

        Route::put('/{permission}', [PermissionController::class, 'update'])
            ->name('admin.permission.update')
            ->can('permission.edit');

        Route::delete('/{permission}', [PermissionController::class, 'destroy'])
            ->name('admin.permission.delete')
            ->can('permission.delete');
    });

    // Role Management Routes
    Route::prefix('admin/role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.role.index')->can('role.view');

        Route::get('/create', [RoleController::class, 'create'])->name('admin.role.create')->can('role.create');

        Route::post('/', [RoleController::class, 'store'])->name('admin.role.store')->can('role.create');

        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('admin.role.edit')->can('role.edit');

        Route::put('/{role}', [RoleController::class, 'update'])->name('admin.role.update')->can('role.edit');

        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('admin.role.destroy')->can('role.delete');

        Route::post('/action', [RoleController::class, 'action'])->name('admin.role.action');
    });

    // Page Management Routes
    Route::prefix('admin/page')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('admin.page.index')->can('page.view');

        Route::get('/create', [PageController::class, 'create'])->name('admin.page.create')->can('page.create');

        Route::post('/', [PageController::class, 'store'])->name('admin.page.store')->can('page.create');

        Route::get('/{page}/edit', [PageController::class, 'edit'])->name('admin.page.edit')->can('page.edit');

        Route::put('/{page}', [PageController::class, 'update'])->name('admin.page.update')->can('page.edit');

        Route::delete('/{page}', [PageController::class, 'destroy'])->name('admin.page.destroy')->can('page.delete');

        Route::post('/action', [PageController::class, 'action'])->name('admin.page.action');
    });


    // Product Management Routes
    Route::prefix('admin/product')->name('admin.product.')->group(function () {

        // 1. Các route AJAX/Cố định (Đặt lên đầu)
        // Route::get('/get-attributes/{categoryId}', [ProductController::class, 'getAttributes'])->name('getAttributes');
        Route::post('/upload-temp', [ProductController::class, 'uploadTemp'])->name('upload-temp'); // <--- MỚI
        Route::delete('/delete-temp/{id}', [ProductController::class, 'deleteTemp'])->name('delete-temp');
        Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');

        // 2. Quản lý Attributes (Thuộc tính)
        Route::prefix('attributes')->name('attributes.')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('index');
            Route::post('/', [AttributeController::class, 'store'])->name('store');
            Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('edit');
            Route::put('/{attribute}', [AttributeController::class, 'update'])->name('update');
            Route::delete('/{attribute}', [AttributeController::class, 'destroy'])->name('destroy');

            // Giá trị thuộc tính (Attribute Values)
            Route::get('/{attribute}/values', [AttributeController::class, 'listValues'])->name('values.index');
            Route::post('/{attribute}/values', [AttributeController::class, 'storeValue'])->name('values.store');
            Route::put('/values/{value}', [AttributeController::class, 'updateValue'])->name('values.update');
            Route::patch('/values/{value}/toggle', [AttributeController::class, 'toggleValue'])->name('values.toggle');
            Route::delete('/values/{value}', [AttributeController::class, 'destroyValue'])->name('values.destroy');
        });

        // 3. Quản lý Sản phẩm chính (Dùng tham số động đặt ở cuối)
        Route::post('/quick-update', [ProductController::class, 'quickUpdate'])->name('quick_update');
        Route::get('/', [ProductController::class, 'index'])->name('index')->can('product.view');
        Route::get('/create', [ProductController::class, 'create'])->name('create')->can('product.create');
        Route::post('/', [ProductController::class, 'store'])->name('store')->can('product.create');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit')->can('product.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update')->can('product.edit');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy')->can('product.delete');
        Route::patch('/{id}/restore', [ProductController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('admin/product/cat')
        ->name('admin.product.cat.')
        ->group(function () {

            Route::get('/', [ProductCategoryController::class, 'index'])
                ->name('index')
                ->can('product.cat.view');

            Route::get('/create', [ProductCategoryController::class, 'create'])
                ->name('create')
                ->can('product.cat.create');

            Route::post('/', [ProductCategoryController::class, 'store'])
                ->name('store')
                ->can('product.cat.create');

            Route::get('/{category}/edit', [ProductCategoryController::class, 'edit'])
                ->name('edit')
                ->can('product.cat.edit');

            Route::put('/{category}', [ProductCategoryController::class, 'update'])
                ->name('update')
                ->can('product.cat.edit');

            Route::delete('/{category}', [ProductCategoryController::class, 'destroy'])
                ->name('destroy')
                ->can('product.cat.delete');

            Route::post('/{category}/restore', [ProductCategoryController::class, 'restore'])
                ->name('restore')
                ->can('product.cat.restore');

            Route::delete('/{category}/force-delete', [ProductCategoryController::class, 'force_delete'])
                ->name('forceDelete')
                ->can('product.cat.force_delete');

            // ✅ AJAX: Lấy attribute theo category
            Route::get('/{category}/attributes', [ProductCategoryController::class, 'attributes'])
                ->name('attributes');
            // ->can('product.create'); // hoặc product.view
        });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
