<?php

namespace App\Http\Controllers;

//accionesPosteriores
use App\Localidad;
use App\Solicitud;
use App\User;
use App\Fecha_de_evento;

use App\Http\Controllers\GenericController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ExtController;

use Auth;

class ParticularController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function accionesAnteriores($modelo, $accion, $id, $request) {

        $acc_ant_errorInfo = array();
        $acc_ant_mensaje = array();
        $acc_ant_mensaje['error'] = false;

        $id = intval($id);


        // INICIO Localidad
        if($modelo == 'Localidad') {
            if ($accion == 'a' or $accion == 'm') {
                $localidad = $request->localidad;
                $provincia_id = $request->provincia_id;
                if ($accion == 'a') {
                    $Localidad = Localidad::whereRaw("localidad like '%$localidad%' AND provincia_id = $provincia_id")->get();
                }
                if ($accion == 'm') {
                    $Localidad = Localidad::whereRaw("localidad like '%$localidad%' AND provincia_id = $provincia_id and id <> ".$id)->get();
                }
                if (count($Localidad) > 0) {
                    $acc_ant_mensaje['error'] = true;
                    $acc_ant_mensaje['detalle'] = 'Esta Ciudad o localidad ya ha sido cargada, no pueden duplicarse las localidades, utilice la ya existente';
                    $acc_ant_mensaje['class'] = 'alert-danger';                    
                }
            }
        }
        // FIN Localidad

        // INICIO Solicitud
        if($modelo == 'Solicitud') {
            if ($accion == 'm') {
                $ejecutivo_nuevo = $request->ejecutivo;
                $Solicitud = Solicitud::find($id);
                if ($Solicitud->ejecutivo <> $ejecutivo_nuevo and $ejecutivo_nuevo <> '') {

                    $Ejecutivo = User::find($ejecutivo_nuevo);
                    $NotificationController = new NotificationController();

                    $user_id = $Ejecutivo->id;
                    $mensaje = __('Se le ha asignado una nueva campaña').': '.$Solicitud->descripcion_sin_estado().' - '.__('ir a la campaña').' -> '.env('PATH_PUBLIC')."Solicitudes/solicitud/ver/".$Solicitud->id;
                    $NotificationController->enviarNotificacion(1, $user_id, $mensaje);  

                    $mensaje = __('Se le ha asignado una nueva campaña').': '.$Solicitud->descripcion_sin_estado().' - '.__('ir a la campaña').' -> <a href="'.env('PATH_PUBLIC').'Solicitudes/solicitud/ver/'.$Solicitud->id.'">'.env('PATH_PUBLIC')."Solicitudes/solicitud/ver/".$Solicitud->id;
                    $NotificationController->enviarNotificacion(2, $user_id, $mensaje);  
                
                }
            }
        }
        // FIN Solicitud

    
    return $acc_ant_mensaje;

    }

    public function accionesPosteriores($modelo, $accion, $id) {
        $id = intval($id);

        // INICIO Fecha_de_evento
        if($modelo == 'Fecha_de_evento') {
            if (($accion == 'a' and $id <> '-1') or $accion == 'm') {

                $Fecha_de_evento = Fecha_de_evento::find($id);

                $ExtController = new ExtController();
                $url_enlace_a_google_maps_inicio_redirect_final = $ExtController->get_redirect_target($Fecha_de_evento->url_enlace_a_google_maps_inicio); 
                $Fecha_de_evento->url_enlace_a_google_maps_inicio_redirect_final = $url_enlace_a_google_maps_inicio_redirect_final;

                if ($Fecha_de_evento->url_enlace_a_google_maps_curso <> '') {
                    $url_enlace_a_google_maps_curso_redirect_final = $ExtController->get_redirect_target($Fecha_de_evento->url_enlace_a_google_maps_curso); 
                    $Fecha_de_evento->url_enlace_a_google_maps_curso_redirect_final = $url_enlace_a_google_maps_curso_redirect_final;                    
                }

                $Fecha_de_evento->save();
            }
        }
        // FIN Fecha_de_evento





    }

}
