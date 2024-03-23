<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class compartirUsuarioLogeado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = auth()->user();
            if(isset($user)){
                $usuario = User::where('email', $user->email)->first();
                $rol = $usuario->getRoleNames()->first();

                View::share(
                    [
                        'user' => $usuario,
                        'role' => $rol,
                    ]);
            }
        }
        catch(Exception $e){
            Log::info($e->getMessage());
        }
        return $next($request);
    }
}
