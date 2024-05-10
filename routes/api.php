<?php

use App\Http\Controllers\loginController;
use App\Http\Controllers\pokemonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// POSTS
Route::get('/postsAPI', [postController::class, 'apiIndex']);

Route::get('/postsAPI/{id}', [postController::class, 'apiIndexId']);

Route::post('/postsAPI', [postController::class, 'apiCreate']);

Route::put('/postsAPI/{id}', [postController::class, 'apiUpdate']);

Route::delete('/postsAPI/{id}', [postController::class, 'apiDelete']);

// POKEMONS
Route::get('/getPokemons', [pokemonController::class, 'getPokemons']);

Route::get('/getPokemon/{id}', [pokemonController::class, 'getPokemon']);

Route::post('/getPokemonName', [pokemonController::class, 'getPokemonName']);

Route::post('/getPokemonEvoInvo', [pokemonController::class, 'apiPokemonEvoInvo']);

// USER
Route::get('/getUserByToken/{token}', [loginController::class, 'apiRememberToken']);
