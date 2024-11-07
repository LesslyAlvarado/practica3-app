<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PerfilController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/usuario_temporal', [UsuarioController::class, 'index']);

Route::post('/login', [UsuarioController::class, 'login']);

Route::post('/registrar', [UsuarioController::class, 'autoregistro']);

//

Route::get('/listado_usuario', [UsuarioController::class, 'getAPIAll']);
//http://127.0.0.1:8000/api/listado_usuario

Route::get('/get_usuario/{id}', [UsuarioController::class, 'getAPIGetUsuarioByID']);

Route::delete('/delete_usuario/{id}', [UsuarioController::class, 'deleteAPI']);

Route::post('/add_usuario', [UsuarioController::class, 'postApiAddUsuario']);

Route::put('/update_usuario/{id}', [UsuarioController::class, 'putApiUpdateUsuario']);

Route::post('/search_usuario', [UsuarioController::class, 'getAPIAllFiltro']);


Route::get('/listado_perfil', [PerfilController::class, 'getAPIAll']);

Route::get('/get_perfil/{id}', [PerfilController::class, 'getAPIGetPerfilByID']);

Route::delete('/delete_perfil/{id}', [PerfilController::class, 'deleteAPI']);

Route::post('/add_perfil', [PerfilController::class, 'postApiAddPerfil']);

Route::put('/update_perfil/{id}', [PerfilController::class, 'putApiUpdatePerfil']);

Route::post('/search_perfil', [PerfilController::class, 'getAPIAllFiltro']);
