<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\postController;
use App\Http\Controllers\pokemonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas para el login
Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');
Route::post('validar-registro', [loginController::class, 'register'])->name('validar-registro');
Route::post('inicia-sesion', [loginController::class, 'login'])->name('inicia-sesion');
Route::get('logout', [loginController::class, 'logout'])->name('logout');

// Se puede acceder sin estar autenticado
Route::get('/publicaciones', [postController::class, 'index'])->name('post.index');

// Tienes que estar autenticado para acceder
Route::view('/cuenta', 'account')->middleware('auth');
Route::view('/crear-publicacion', 'post.create_edit')->middleware('auth');
Route::get('/publicaciones/crear', [postController::class, 'create'])->name('post.create')->middleware('auth');
Route::post('/publicaciones/crear', [postController::class, 'store'])->name('post.store')->middleware('auth');
Route::get('/publicaciones/editar/{post}', [postController::class, 'edit'])->name('post.edit')->middleware('auth');
Route::put('/publicaciones/actualizar/{post}', [postController::class, 'update'])->name('post.update')->middleware('auth');
Route::delete('/publicaciones/{post}', [postController::class, 'delete'])->name('post.delete')->middleware('auth');


// Pokemon
Route::get('/pokemon', [pokemonController::class, 'index'])->name('pokemon.index')->middleware('checkRole');
Route::get('/pokemons', [pokemonController::class, 'apiCaller'])->name('pokemon.load')->middleware('checkRole');
Route::get('/pokemon/list/page/{page}', [pokemonController::class, 'list'])->name('pokemon.list')->middleware('checkRole');
Route::get('/pokemon/{id}', [pokemonController::class, 'show'])->name('pokemon.show')->middleware('checkRole');
Route::get('/pokemon/list/type/{type}/{page}', [pokemonController::class, 'listType'])->name('pokemon.type')->middleware('checkRole');
