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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function (Request $request) {
    return Inertia::render('Dashboard', ['links' => $request->user()->links]);
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/{name}', function (Request $request, string $name) {
    $link = $request->user()->links()->where('name', $name)->first();
    if ($link) {
        return redirect()->away($link->value);
    }
    abort(404);
});

Route::middleware('auth:sanctum')->put('/links/{link}', [LinkController::class, 'update'])->name('link.update');

Route::middleware('auth:sanctum')->delete('/links/{link}', [LinkController::class, 'destroy'])->name('link.destroy');
