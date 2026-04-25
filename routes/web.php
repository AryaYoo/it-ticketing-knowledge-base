<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Auth::routes();
Route::post('/fast-login', [App\Http\Controllers\Auth\LoginController::class, 'fastLogin'])->name('fast-login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('tickets/history', [App\Http\Controllers\TicketController::class, 'history'])->name('tickets.history');
    Route::get('tickets/history/pdf', [App\Http\Controllers\TicketController::class, 'historyPdf'])->name('tickets.history.pdf');
    Route::get('tickets/search', [App\Http\Controllers\TicketController::class, 'search'])->name('tickets.search'); // Search before resource
    Route::resource('tickets', App\Http\Controllers\TicketController::class);
    Route::post('tickets/{ticket}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

    // Forum Routes
    Route::resource('forum', App\Http\Controllers\ForumController::class)->only(['index', 'store']);
    Route::post('forum/{post}/react', [App\Http\Controllers\ForumController::class, 'react'])->name('forum.react');

    // New Actions
    Route::get('tickets/{ticket}/resolve', [App\Http\Controllers\TicketController::class, 'resolveForm'])->name('tickets.resolveForm');
    Route::post('tickets/{ticket}/resolve', [App\Http\Controllers\TicketController::class, 'resolve'])->name('tickets.resolve');
    Route::post('tickets/{ticket}/in-progress', [App\Http\Controllers\TicketController::class, 'inProgress'])->name('tickets.inProgress');
    Route::post('tickets/{ticket}/escalate', [App\Http\Controllers\TicketController::class, 'escalate'])->name('tickets.escalate');

    Route::get('check-activity', [App\Http\Controllers\ActivityController::class, 'check'])->name('activity.check');

    // Inventaris & Maintenance Routes (Admin & Staff Only)
    Route::group([
        'middleware' => function ($request, $next) {
            if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        }
    ], function () {
        Route::get('inventaris/export/pdf', [App\Http\Controllers\InventarisController::class, 'exportPdf'])->name('inventaris.export.pdf');
        Route::get('inventaris/export/excel', [App\Http\Controllers\InventarisController::class, 'exportExcel'])->name('inventaris.export.excel');
        Route::resource('inventaris', App\Http\Controllers\InventarisController::class)->parameters([
            'inventaris' => 'asset'
        ]);
        Route::get('maintenances/create/{asset?}', [App\Http\Controllers\MaintenanceController::class, 'create'])->name('maintenances.create');
        Route::get('maintenances/export/pdf', [App\Http\Controllers\MaintenanceController::class, 'exportPdf'])->name('maintenances.export.pdf');
        Route::get('maintenances/export/excel', [App\Http\Controllers\MaintenanceController::class, 'exportExcel'])->name('maintenances.export.excel');
        Route::post('maintenances/{maintenance}/complete', [App\Http\Controllers\MaintenanceController::class, 'complete'])->name('maintenances.complete');
        Route::resource('maintenances', App\Http\Controllers\MaintenanceController::class)->except(['create']);
    });

    // Admin Routes
    Route::group([
        'prefix' => 'admin',
        'middleware' => function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403);
            }
            return $next($request);
        }
    ], function () {
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
        Route::resource('ip-mappings', App\Http\Controllers\Admin\IpMappingController::class);
        Route::get('activity-logs', [App\Http\Controllers\ActivityController::class, 'index'])->name('activity_logs.index');
        
        // App Settings
        Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('settings', [App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');
    });
    Route::get('lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');
});
