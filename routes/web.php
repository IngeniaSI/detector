<?php

use App\Http\Controllers\bitacoraController;
use App\Http\Controllers\crudUsuariosController;
use App\Http\Controllers\formularioSimpatizanteController;
use App\Http\Controllers\iniciarSesionController;
use App\Http\Controllers\tablaSimpatizantesController;
use App\Models\bitacora;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


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

Route::get('/gestor-usuarios', [crudUsuariosController::class, 'index'])->name('crudUsuario.index')->middleware(['auth', 'can:crudUsuarios.index']);
Route::get('/gestor-usuarios/usuarios', [crudUsuariosController::class, 'todosUsuarios'])->name('crudUsuario.todos')->middleware('auth');
Route::get('/gestor-usuarios/obtener-{usuario}', [crudUsuariosController::class, 'obtenerUsuario'])->name('crudUsuario.obtener')->middleware('auth');
Route::post('/gestor-usuarios/crear-usuario', [crudUsuariosController::class, 'crearUsuario'])->name('crudUsuario.crear')->middleware('auth');
Route::post('/gestor-usuarios/editar-usuario-{usuario}', [crudUsuariosController::class, 'editarUsuario'])->name('crudUsuario.editar')->middleware('auth');
Route::post('/gestor-usuarios/borrar-usuario-{usuario}', [crudUsuariosController::class, 'borrarUsuario'])->name('crudUsuario.borrar')->middleware('auth');

Route::get('/simpatizantes', [tablaSimpatizantesController::class, 'index'])->name('crudSimpatizantes.index')->middleware(['auth', 'can:crudSimpatizantes.index']);
Route::get('/simpatizantes/inicializar', [tablaSimpatizantesController::class, 'inicializar'])->name('crudSimpatizantes.inicializar')->middleware('auth');
Route::post('/simpatizantes/supervisar-{persona}', [tablaSimpatizantesController::class, 'verificar'])->name('crudSimpatizantes.verificar')->middleware(['auth', 'can:crudSimpatizantes.verificar']);
Route::post('/simpatizantes/borrar-{persona}', [tablaSimpatizantesController::class, 'borrar'])->name('crudSimpatizantes.borrar')->middleware(['auth', 'can:crudSimpatizantes.borrar']);

Route::get('/simpatizantes/agregar', [formularioSimpatizanteController::class, 'index'])->name('agregarSimpatizante.index')->middleware(['auth', 'can:agregarSimpatizante.index']);
Route::get('/simpatizantes/agregar/inicializar', [formularioSimpatizanteController::class, 'inicializar'])->name('agregarSimpatizante.inicializar')->middleware('auth');
Route::post('/simpatizantes/agregar/agregando', [formularioSimpatizanteController::class, 'agregandoSimpatizante'])->name('agregarSimpatizante.agregandoSimpatizante')->middleware('auth');


//Vista
//Route::get('/estadistica', [tablaSimpatizantesController::class, 'index'])->name('estadistica.index')->middleware('auth');
//Route::get('/mapa', [tablaSimpatizantesController::class, 'index'])->name('mapa.index')->middleware('auth');
Route::get('/estadistica', function () {
    return view('estadistica');
})->name('estadistica.index')->middleware(['auth', 'can:estadistica.index']);
Route::get('/mapa', function () {
    return view('mapa');
})->middleware(['auth', 'can:mapa.index']);

Route::get('/bitacora', [bitacoraController::class, 'index'])->name('bitacora.index')->middleware(['auth', 'can:bitacora.index']);

