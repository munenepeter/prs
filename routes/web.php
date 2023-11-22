<?php


use App\Http\Livewire\EditUser;
use App\Http\Livewire\EditProject;
use App\Http\Livewire\ViewAllUsers;
use App\Http\Livewire\ViewProjects;
use App\Http\Livewire\CreateNewUser;
use App\Http\Livewire\CreateProject;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\EditDailyReport;
use App\Http\Livewire\ViewDailyReports;
use App\Http\Livewire\ViewProjectTasks;
use App\Http\Livewire\CreateDailyReport;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\FirstTimeLoginController;


Route::permanentRedirect('/', 'login')->name('homepage');

Route::prefix('login')->name('login.')->group(function () {
    Route::get('first_time', [FirstTimeLoginController::class, 'create'])->name('first.time.create');
    Route::post('first_time', [FirstTimeLoginController::class, 'store'])->name('first.time.store');
});

Route::middleware(['auth',  'new_user'])->name('reports.')->group(function () {
    Route::get('/{user}/reports', ViewDailyReports::class)
        ->middleware('can:view-reports')->name('index');
    Route::get('/reports/create', CreateDailyReport::class)
        ->middleware('can:view-reports')->name('create');
    Route::get('/reports/{report}/edit', EditDailyReport::class)->name('edit');
});

Route::middleware(['auth', 'admin.project_manager'])->group(function () {
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', ViewProjects::class)->name('index');
        Route::get('/create', CreateProject::class)->name('create');
        Route::get('/{project:slug}/edit', EditProject::class)->name('edit');
        Route::get('/{project:slug}/tasks/', ViewProjectTasks::class)->name('tasks.show');
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('users/')->name('users.')->group(function () {
        Route::get('', ViewAllUsers::class)->name('index');
        Route::get('create', CreateNewUser::class)->name('create');
        Route::get('{user}/edit', EditUser::class)->name('edit');
    });
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'new_user'])->name('dashboard');

require __DIR__.'/auth.php';