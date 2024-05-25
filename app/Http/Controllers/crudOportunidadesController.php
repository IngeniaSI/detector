<?php

namespace App\Http\Controllers;

use App\Models\bitacora;
use App\Models\distritoFederal;
use App\Models\distritoLocal;
use App\Models\encuesta;
use App\Models\entidad;
use App\Models\persona;
use App\Models\pregunta;
use App\Models\seccion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class crudOportunidadesController extends Controller
{
    public function index(){
         
        return view();
    }
}
