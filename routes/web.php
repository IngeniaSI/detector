<?php

use App\Http\Controllers\crudUsuariosController;
use App\Http\Controllers\iniciarSesionController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Route::get('/iniciar-sesion', [iniciarSesionController::class, 'index'])->name('login');
Route::post('/iniciar-sesion/verificando', [iniciarSesionController::class, 'validarUsuario'])->name('login.comprobando');
Route::post('/cerrando-sesion', [iniciarSesionController::class, 'cerrarSesion'])->name('logout')->middleware('auth');

Route::get('/gestor-usuarios', [crudUsuariosController::class, 'index'])->name('crudUsuario.index')->middleware('auth');
Route::get('/gestor-usuarios/usuarios', [crudUsuariosController::class, 'todosUsuarios'])->name('crudUsuario.todos')->middleware('auth');
Route::get('/gestor-usuarios/obtener-{usuario}', [crudUsuariosController::class, 'obtenerUsuario'])->name('crudUsuario.obtener')->middleware('auth');
Route::post('/gestor-usuarios/crear-usuario', [crudUsuariosController::class, 'crearUsuario'])->name('crudUsuario.crear')->middleware('auth');
Route::post('/gestor-usuarios/editar-usuario-{usuario}', [crudUsuariosController::class, 'editarUsuario'])->name('crudUsuario.editar')->middleware('auth');
Route::post('/gestor-usuarios/borrar-usuario-{usuario}', [crudUsuariosController::class, 'borrarUsuario'])->name('crudUsuario.borrar')->middleware('auth');

