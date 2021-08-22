<?php

use App\Http\Controllers\LinkController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return redirect('dashboard');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/dashboard', function (Request $request) {

        $permissions = [];
        foreach ($request->user()->allTeams() as $team) {
            $permissions[$team->id] = $request->user()->teamPermissions($team);
        }
        return Inertia::render('Dashboard', [
            'current_links' => $request->user()->currentTeam->links,
            'teams_permissions' =>$permissions
        ]);
    })->name('dashboard');

    Route::get('/admin/panel', function (Request $request) {
        return Inertia::render('Administration/Show');
    })->name('admin.panel');

    Route::get('/{name}', [LinkController::class, 'go']);

    Route::get('/{slug}/{name}', [LinkController::class, 'goTeam']);

    Route::put('/links/{link}', [LinkController::class, 'update'])->name('link.update');

    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('link.destroy');

    Route::post('/links', [LinkController::class, 'store'])->name('link.store');
});

Route::get('/{slug}/{name}', [LinkController::class, 'goTeam']);
