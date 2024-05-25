<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class bitacora extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function crearRegistro($accion, $url, $ip, $tipo, $idUser){
        try{
            DB::beginTransaction();
            $bitacora = new bitacora();
            $bitacora->accion = $accion;
            $bitacora->url = $url;
            $bitacora->ip = $ip;
            $bitacora->tipo = $tipo;
            $bitacora->user_id = $idUser;
            $bitacora->save();
            DB::commit();
        }
        catch (Exception $e){
            DB::rollBack();
        }
    }
}
