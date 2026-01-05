<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/bilietas', [TicketController::class, 'create'])->name('bilietas'); 
Route::post('/bilietas', [TicketController::class, 'store'])->name('bilietas.saugoti');


Route::get('/bilietas', function () {
    return view('bilietas');
})->name('bilietas');

Route::get('/aktyvus', [TicketController::class, 'aktyvus'])->name('aktyvus');
Route::post('/keisti-statusa', [TicketController::class, 'keistiStatusa'])->name('keistiStatusa');

// Redagavimo forma 
Route::get('/{id}/redaguoti', [TicketController::class, 'redaguoti'])->name('redaguoti'); 
// Bilieto atnaujinimas 
Route::put('/{id}/atnaujinti', [TicketController::class, 'atnaujinti'])->name('atnaujinti'); 
// Bilieto ištrynimas 
Route::delete('/{id}/istrinti', [TicketController::class, 'istrinti'])->name('istrinti');

Route::post('/komentaras/{id}', [TicketController::class, 'pridetiKomentara']);

Route::get('/ataskaita/aktyvus', [TicketController::class, 'aktyviuAtaskaita'])
    ->name('aktyvus.ataskaita')
    ->middleware('auth');

Route::post('/ataskaita/siusti', [TicketController::class, 'siustiAtaskaita'])
    ->middleware('auth');


    
require __DIR__.'/auth.php';