<?php

use App\Http\Controllers\bitacoraController;
use App\Http\Controllers\crudPersonasController;
use App\Http\Controllers\crudUsuariosController;
use App\Http\Controllers\estadisticaController;
use App\Http\Controllers\formularioSimpatizanteController;
use App\Http\Controllers\iniciarSesionController;
use App\Http\Controllers\mapaController;
use App\Http\Controllers\tablaSimpatizantesController;
use App\Http\Controllers\crudEncuestasController;
use App\Http\Controllers\crudObjetivoController;
use App\Http\Controllers\crudOportunidadesController;
use App\Http\Controllers\crudPromotoresController;
use App\Http\Controllers\crudResultadosController;
use App\Http\Controllers\repuestasEncuestasController;

use App\Models\bitacora;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
Route::get('/iniciar-sesion',
[iniciarSesionController::class, 'index'])->name('login');
Route::post('/iniciar-sesion/verificando',
[iniciarSesionController::class, 'validarUsuario'])->name('login.comprobando');
Route::get("/encuestas/contestar-encuesta-{encuesta}",
[crudEncuestasController::class, 'visualizarEncuesta'])->name('encuestas.visualizarEncuesta');
Route::get("/encuestas/cargar-encuesta-{encuesta}",
[crudEncuestasController::class, 'cargarEncuesta'])->name('encuestas.cargarEncuesta');
Route::post("/encuestas/contestar-encuesta-{encuesta}",
[crudEncuestasController::class, 'contestarEncuesta'])->name('encuestas.contestarEncuesta');
Route::get("/gracias",
[crudEncuestasController::class, 'graciasResponder'])->name('encuestas.graciasResponder');


Route::prefix('/')->middleware('auth')->group(function (){
    Route::post('cerrando-sesion', [iniciarSesionController::class, 'cerrarSesion'])->name('logout');

    Route::prefix('gestor-usuarios')->controller(crudUsuariosController::class)->group(function () {
        Route::get('/', 'index')
        ->name('crudUsuario.index')->middleware(['can:crudUsuarios.index']);
        Route::get('/usuarios', 'todosUsuarios')
        ->name('crudUsuario.todos')->middleware(['can:crudUsuarios.index']);
        Route::get('/obtener-{usuario}', 'obtenerUsuario')
        ->name('crudUsuario.obtener')->middleware(['can:crudUsuarios.edit']);
        Route::post('/crear-usuario', 'crearUsuario')
        ->name('crudUsuario.crear')->middleware(['can:crudUsuarios.create']);
        Route::post('/editar-usuario-{usuario}', 'editarUsuario')
        ->name('crudUsuario.editar')->middleware(['can:crudUsuarios.edit']);
        Route::post('/borrar-usuario-{usuario}', 'borrarUsuario')
        ->name('crudUsuario.borrar')->middleware(['can:crudUsuarios.delete']);
    });
    Route::prefix('simpatizantes')->group(function() {
        Route::controller(tablaSimpatizantesController::class)->group(function() {
            Route::get('/', 'index')
            ->name('crudSimpatizantes.index')->middleware(['can:crudSimpatizantes.index']);
            Route::get('/descargar', 'descargar')
            ->name('crudSimpatizantes.descargar')->middleware(['can:crudSimpatizantes.exportar']);
            Route::get('/inicializar', 'inicializar')
            ->name('crudSimpatizantes.inicializar')->middleware(['can:crudSimpatizantes.index']);
            Route::get('/numeros-supervisado', 'numeroSupervisados')
            ->name('crudSimpatizantes.numeroSupervisados')->middleware(['can:crudSimpatizantes.index']);
            Route::get('/ver-{persona}', 'ver')
            ->name('crudSimpatizantes.ver')->middleware(['can:crudSimpatizantes.consultar']);//DESUSO
            Route::post('/supervisar-{persona}', 'verificar')
            ->name('crudSimpatizantes.verificar')->middleware(['can:crudSimpatizantes.verificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
            Route::post('/borrar-{persona}', 'borrar')
            ->name('crudSimpatizantes.borrar')->middleware(['can:crudSimpatizantes.borrar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
        });
        Route::controller(formularioSimpatizanteController::class)->group(function() {
            Route::get('/filtrarColonias-{colonia}', 'filtrarColonias')
            ->name('crudSimpatizantes.filtrarColonias')->middleware(['can:agregarSimpatizante.index']);
            Route::get('/filtrarSecciones-{seccion}', 'filtrarSecciones')
            ->name('crudSimpatizantes.filtrarSecciones')->middleware(['can:agregarSimpatizante.index']);
            Route::get('/agregar', 'index')
            ->name('agregarSimpatizante.index')->middleware(['can:agregarSimpatizante.index']);
            Route::get('/agregar/inicializar', 'inicializar')
            ->name('agregarSimpatizante.inicializar')->middleware(['can:agregarSimpatizante.index']);
            Route::post('/agregar/agregando', 'agregandoSimpatizante')
            ->name('agregarSimpatizante.agregandoSimpatizante')->middleware(['can:agregarSimpatizante.index']);
        });
        Route::controller(crudPersonasController::class)->group(function() {
            Route::get('/modificar-{persona}', 'index')
            ->name('crudPersonas.index')->middleware(['can:crudSimpatizantes.modificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
            Route::get('/modificar/cargarPersona-{persona}', 'cargarPersona')
            ->name('crudPersonas.cargarPersona')->middleware(['can:crudSimpatizantes.modificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
            Route::post('/modificar/modificarPersona-{persona}', 'modificarPersona')
            ->name('crudPersonas.modificarPersona')->middleware(['can:crudSimpatizantes.modificar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
            Route::get('/consultar-{persona}', 'consultar')
            ->name('crudPersonas.consultar')->middleware(['can:crudSimpatizantes.consultar', 'nivelAcceso']); //ESTOS NECESITA LA VALIDACION
        });
    });
    Route::prefix('estadistica')->controller(estadisticaController::class)->group(function(){
        Route::get('/', 'index')
        ->name('estadistica.index')->middleware(['can:estadistica.index']);
        Route::get('/inicializar', 'inicializar')
        ->name('estadistica.inicializar')->middleware(['can:estadistica.index']);
        Route::get('/filtrar', 'filtrar')
        ->name('estadistica.filtrar')->middleware(['can:estadistica.index']);
        Route::post('/cargarMeta', 'cargarMeta')
        ->name('estadistica.cargarMeta')->middleware(['can:estadistica.cambiarMeta']);

    });
    Route::prefix('encuestas')->group(function(){
        Route::controller(crudEncuestasController::class)->group(function(){
            Route::get("/", 'index')->name('encuestas.index');
            Route::get("/inicializar", 'cargarEncuestas')->name('encuestas.cargar');
            Route::get("/cargar-secciones", 'cargarSecciones')->name('encuestas.cargarSecciones');
            Route::post("/agregar", 'agregar')->name('encuestas.agregar');
            Route::get("/ver-{encuesta}", 'ver')->name('encuestas.ver');
            Route::post("/configurar-{encuesta}", 'configurar')->name('encuestas.configurar');
            Route::post("/modificar-{encuesta}", 'editar')->name('encuestas.modificar');
            Route::post("/iniciar-periodo-{encuesta}", 'iniciarEncuesta')->name('encuestas.iniciarEncuesta');
            Route::post("/finalizar-periodo-{encuesta}", 'detenerEncuesta')->name('encuestas.finalizarEncuesta');
            Route::post("/borrar-{encuesta}", 'borrar')->name('encuestas.borrar');
            Route::post("/duplicar-{encuesta}", 'clonar')->name('encuestas.clonar');
        });
        Route::prefix('resultados')->controller(crudResultadosController::class)->group(function(){
            Route::get("/", 'index')->name('respuestas.index');
            Route::get("/inicializar", 'inicializar')->name('respuestas.inicializar');
            Route::get("/cargar-resultado-{respuesta}", 'cargarResultado')->name('respuestas.cargarResultado');
            Route::get("/tabla-respuestas", 'paginacion')->name('respuestas.paginacion');
            Route::get('/exportar-{encuesta}', "exportarResultados")->name('respuestas.exportarResultados');
            Route::post('/vincular-{respuesta}-{persona}', "vincularPersona")->name('respuestas.vincularPersona');
        });
    });

    Route::prefix('objetivos')->controller(crudObjetivoController::class)->group(function(){
        Route::get('/', 'index')
        ->name('objetivos.index');
        Route::get('/inicializar', 'inicializar')
        ->name('objetivos.inicializar');
        Route::get('/cargar-tabla', 'cargarTabla')
        ->name('objetivos.cargarTabla');
        Route::post('/cambiar-estatus-{objetivo}', 'cambiarEstatus')
        ->name('objetivos.cambiarEstatus');
        Route::post('/agregar-objetivo', 'agregar')
        ->name('objetivos.agregar');
        Route::get('/cargar-objetivo-{objetivo}', 'cargar')
        ->name('objetivos.cargarObjetivo');
        Route::post('/modificar-objetivo-{objetivo}', 'editar')
        ->name('objetivos.modificar');
        Route::post('/borrar-{objetivo}', 'borrar')
        ->name('objetivos.borrar');
    });



    Route::get('/mapa', [mapaController::class, 'index'])->middleware(['can:mapa.index']);
    Route::get('/bitacora', [bitacoraController::class, 'index'])->name('bitacora.index')->middleware(['can:bitacora.index']);

    Route::get("/crudOportunidades", [crudOportunidadesController::class, 'index'])->name("oportunidades.index");
    Route::get("/crudOportunidades/inicializar", [crudOportunidadesController::class, 'inicializar'])->name("oportunidades.inicializar");
    Route::get("/crudOportunidades/exportarParaPromotor", [crudOportunidadesController::class, 'exportarParaPromotor'])->name("oportunidades.exportarParaPromotor");
    Route::get("/crudOportunidades/cargarTabla", [crudOportunidadesController::class, 'cargarOportunidades'])->name("oportunidades.cargarOportunidades");
    Route::get("/crudOportunidades/cargar-seguimientos-{oportunidad}", [crudOportunidadesController::class, 'obtenerSeguimiento'])->name("oportunidades.obtenerSeguimiento");
    Route::post("/crudOportunidades/crearOportunidad", [crudOportunidadesController::class, 'agregar'])->name("oportunidades.agregar");
    Route::post("/crudOportunidades/cambiarOportunidad", [crudOportunidadesController::class, 'cambiarEstado'])->name("oportunidades.cambiarEstatus");
    Route::post("/crudOportunidades/agregar-actividad-{oportunidad}", [crudOportunidadesController::class, 'agregarActividad'])->name("oportunidades.agregarActividad");

    Route::get("/promotores/cargarTabla", [crudPromotoresController::class, 'cargarPromotores'])->name("promotores.cargarPromotores");




     //  Route::get("/crudPromotores", function(){
    //     return View::make("crudPromotores");
    //  });


});

