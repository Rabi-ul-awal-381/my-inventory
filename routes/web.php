<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CrewController;
use Illuminate\Support\Facades\Route;

// Home page - our custom controller
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes (from Breeze)
Route::get('/dashboard', function () {
    return redirect()->route('home'); // Redirect dashboard to our home
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // Crews routes
Route::resource('crews', CrewController::class);
Route::get('/join-crew', [CrewController::class, 'joinForm'])->name('crews.join-form');
Route::post('/join-crew', [CrewController::class, 'join'])->name('crews.join');
Route::post('/crews/{crew}/leave', [CrewController::class, 'leave'])->name('crews.leave');
Route::patch('/crews/{crew}/members/{user}', [CrewController::class, 'updateMemberRole'])->name('crews.update-member-role');
Route::delete('/crews/{crew}/members/{user}', [CrewController::class, 'removeMember'])->name('crews.remove-member');


// Custom roles routes
Route::get('/crews/{crew}/roles', [CrewController::class, 'roles'])->name('crews.roles');
Route::get('/crews/{crew}/roles/create', [CrewController::class, 'createRole'])->name('crews.create-role');
Route::post('/crews/{crew}/roles', [CrewController::class, 'storeRole'])->name('crews.store-role');
Route::get('/crews/{crew}/roles/{role}/edit', [CrewController::class, 'editRole'])->name('crews.edit-role');
Route::patch('/crews/{crew}/roles/{role}', [CrewController::class, 'updateRole'])->name('crews.update-role');
Route::delete('/crews/{crew}/roles/{role}', [CrewController::class, 'destroyRole'])->name('crews.destroy-role');
Route::post('/crews/{crew}/members/{user}/assign-role', [CrewController::class, 'assignRole'])->name('crews.assign-role');

});

// Items routes - only for authenticated users
Route::middleware('auth')->group(function () {
    Route::resource('items', ItemController::class);
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

});

require __DIR__.'/auth.php';