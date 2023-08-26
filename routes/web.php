<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\FirstTimeLoginController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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
Route::permanentRedirect('/', 'login')->name('homepage');
//Route::prefix('projects')->name('projects.')->group(function () {
//    Route::get('/', \App\Http\Livewire\ViewProjects::class)->name('index');
//    Route::get('/create', \App\Http\Livewire\CreateProject::class)->name('create');
//    Route::get('/{project}', \App\Http\Livewire\ShowProject::class)->name('show');
//    Route::get('/{project}/edit', \App\Http\Livewire\EditProject::class)->name('edit');
//});
Route::prefix('login')->name('login.')->group(function () {
    Route::get('first_time', [FirstTimeLoginController::class, 'create'])->name('first.time.create');
    Route::post('first_time', [FirstTimeLoginController::class, 'store'])->name('first.time.store');
});

Route::middleware(['auth',  'new_user'])->name('reports.')->group(function () {
    Route::get('/{user}/reports', \App\Http\Livewire\ViewDailyReports::class)
        ->middleware('can:view-reports')
        ->name('index');
    Route::get('/reports/create', \App\Http\Livewire\CreateDailyReport::class)
        ->middleware('can:view-reports')
        ->name('create');
    Route::get('/reports/{report}/edit', \App\Http\Livewire\EditDailyReport::class)
        ->name('edit');
});

Route::middleware(['auth', 'admin.project_manager'])->group(function () {
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', \App\Http\Livewire\ViewProjects::class)->name('index');
        Route::get('/create', \App\Http\Livewire\CreateProject::class)->name('create');
        Route::get('/{project:slug}/edit', \App\Http\Livewire\EditProject::class)->name('edit');
        Route::get('/{project:slug}/tasks/', \App\Http\Livewire\ViewProjectTasks::class)
            ->name('tasks.show');
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('users/')->name('users.')->group(function () {
        Route::get('', \App\Http\Livewire\ViewAllUsers::class)->name('index');
        Route::get('create', \App\Http\Livewire\CreateNewUser::class)->name('create');
        Route::get('{user}/edit', \App\Http\Livewire\EditUser::class)->name('edit');
    });
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'new_user'])->name('dashboard');



Route::get('/test-pdf', function () {
    try {
        // Replace this with your actual data retrieval logic
        $reports = [
            // Sample data
            ['name' => 'Report 1', 'value' => 10],
            ['name' => 'Report 2', 'value' => 20],
        ];

        // Load the view content
        $pdfContent = view('daily_report_pdf', compact('reports'))->render();

        // Generate PDF
        $pdf = PDF::loadHtml($pdfContent);

        // Set paper size and orientation if needed
        $pdf->setPaper('A4', 'portrait');

        // Download the PDF
        return $pdf->download('test-pdf.pdf');
    } catch (\Exception $e) {
        return response()->json(['error' => 'PDF export failed'], 500);
    }
});


require __DIR__.'/auth.php';
