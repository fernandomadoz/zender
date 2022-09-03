<?php

namespace App\Http\Controllers;
use App\Encabezado_de_envio;
use App\Envio_a_contacto;
use App\Instancia_de_envio;
use App\Contacto_gru;
use App\Contacto_mig;
use App\Contacto_tyt;
use App\Contacto_cuo;
use App\Origen_mig;
use App\Lista_de_envio;
use App\Tipo_de_negocio_tyt;
use App\Regimen_de_visita_tyt;
use App\Nivel_de_compra_tyt;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ListasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function camposBusqueda()
    {

        /*
        $nombre = $campo[0];
        $label = $campo[1];
        $valores = $campo[2];
        $tipo = $campo[3];
        $busqueda = $campo[4];
        */

        $campos = [];
        if (Auth::user()->empresa->prefijo == 'mig') {

            $Origenes = $this->get_origenes_tyt();
            
            $campos = [
                ['nombre', 'Nombre', '', 't', 's'],
                ['apellido', 'Apellido', '', 't', 's'],
                ['email', 'Email', '', 't', ''],
                ['telefono', 'Telefono', '', 't', ''],
                ['fecha_de_nacimiento', 'Fecha de nacimiento', '', 'f', ''],
                ['origen_mig_id', 'Origen', $Origenes, 'fk', 's']
            ];

        }

        if (Auth::user()->empresa->prefijo == 'gru') {
            $campos = [
                ['nombre', 'Nombre', '', 't', 's'],
                ['apellido', 'Apellido', '', 't', 's'],
                ['email_correo', 'Email', '', 't', ''],
                ['email_correo_2', 'Email 2', '', 't', ''],
                ['telefono', 'Telefono', '', 't', ''],
                ['observaciones', 'Observaciones', '', 't', '']
            ];
            
        }


        if (Auth::user()->empresa->prefijo == 'tyt') {


            $Tipos_de_negocio = $this->get_tipos_de_negocio_tyt();
            $Regimenes_de_visita = $this->get_regimenes_de_visita_tyt();
            $Niveles_de_compra = $this->get_niveles_de_compra();

            
            $campos = [
                ['nombre_de_fantasia', 'Nombre de Fantasia', '', 't', 's'],
                ['codigo', 'Codigo', '', 't', ''],
                ['cuenta', 'Cuenta', '', 't', ''],
                ['nombre_del_contacto', 'Nombre del contacto', '', 't', ''],
                ['localidad', 'Localidad', '', 't', 's'],
                ['vendedor', 'Vendedor', '', 't', 's'],
                ['nombre_del_responsable_de_compra', 'nombre resp compra', '', 't', ''],
                ['tipo_de_negocio_tyt_id', 'Tipo de Negocio', $Tipos_de_negocio, 'fk', 's'],
                ['regimen_de_visita_tyt_id', 'Regimen de visita', $Regimenes_de_visita, 'fk', 's'],
                ['nivel_de_compra_tyt_id', 'Nivel de Compra', $Niveles_de_compra, 'fk', 's']
            ];

        }

        if (Auth::user()->empresa->prefijo == 'cuo') {
            $campos = [
                ['numero', 'Número', '', 't', 's'],
                ['nombre', 'Nombre', '', 't', 's'],
                ['direccion', 'Dirección', '', 't', 's'],
                ['domicilio_particular', 'Domicilio particular', '', 't', 's'],
                ['telefono_fijo', 'Tel fijo', '', 't', 's'],
                ['celular', 'Celular', '', 't', 's'],
                ['moneda_documentos', 'Documentos', '', 't', ''],
                ['dni', 'DNI', '', 't', ''],
                ['tipo_de_negocio', 'Tipo de negocio', '', 't', 's'],

            ];
            
        }


        return $campos;

    }

    public function url_whatsapp($Modelo_de_mensaje, $parametros, $campos)
    {
        
        $telefono = $parametros['telefono'];
        $codigo_tel = $parametros['codigo_tel'];

        // pedido_de_confirmacion_curso
        $patrones = array();
        $sustituciones = array();
        
        $i = 0;
        
        foreach ($campos as $campo) {
            $i++;
            $nombre = $campo[0];
            $patrones[$i] = '/contacto_'.$nombre.'/';
            $sustituciones[$i] = $parametros[$nombre];
        }


        $Modelo_de_mensaje = preg_replace($patrones, $sustituciones, $Modelo_de_mensaje);
        $Modelo_de_mensaje = $Modelo_de_mensaje;
        $urlencode_Modelo_de_mensaje = $this->CodificarURL($Modelo_de_mensaje);
        $Modelo_de_mensaje = 'https://api.whatsapp.com/send?phone='.$this->celular_wa($telefono, $codigo_tel).'&text='.$urlencode_Modelo_de_mensaje;


        $url_whatsapp = $Modelo_de_mensaje;

        return $url_whatsapp;
    }


    public function CodificarURL($string) {

        $entities = array('%20');
        $replacements = array('+');
        return str_replace($replacements, $entities, urlencode($string));
    }


    public function celular_wa($telefono, $codigo_tel)
    {
        $celular_wa = trim($telefono);
        
        if (substr($celular_wa, 0, 1) <> '+') {
            if (substr($celular_wa, 0, strlen($codigo_tel)) <> $codigo_tel) {
                $celular_wa = $codigo_tel.$celular_wa;
            }
        }
        
        $celular_wa = str_replace('+', '', $celular_wa);
        $celular_wa = str_replace(' ', '', $celular_wa);
        $celular_wa = str_replace('-', '', $celular_wa);
        $celular_wa = str_replace('(', '', $celular_wa);
        $celular_wa = str_replace(')', '', $celular_wa);
        $celular_wa = str_replace(',', '', $celular_wa);
        $celular_wa = str_replace('.', '', $celular_wa);
        
        return $celular_wa;
    }


    public function registrarEnvio($codigo_de_envio_id, $instancia_de_envio_id, $medio_de_envio_id)
    {  

        $Envio = new Envio_a_contacto;
        $Envio->instancia_de_envio_id = $instancia_de_envio_id;
        $Envio->codigo_de_envio_id = $codigo_de_envio_id;
        $Envio->medio_de_envio_id = $medio_de_envio_id;
        $Envio->save();

        //return $Inscripcion;

    }


    public function setearSino($codigo, $instancia_de_envio_id)
    {   
        $sino = $_POST['sino'];
        
        $nombre_de_campo = 'sino_envio_'.$codigo;
        
        if ($codigo == 11) {
            $nombre_de_campo = 'sino_deshabilitar';
        }

        $Instancia_de_envio = Instancia_de_envio::find($instancia_de_envio_id);
        $contacto_id = $Instancia_de_envio->contacto_id;
        $Instancia_de_envio->$nombre_de_campo = $sino;
        $Instancia_de_envio->save();

        return $Instancia_de_envio;

    }


    public function crearCampania()
    {  

        $campos = $this->camposBusqueda();

        return View('listas/filtrar-contactos')        
        ->with('campos', $campos);

    }


    public function generarListas(request $response)
    {  

        $campos = $this->camposBusqueda();

        $whereRaw = '1=1';

        foreach ($campos as $campo) {

            $nombre = $campo[0];
            $label = $campo[1];
            $valores = $campo[2];
            $tipo = $campo[3];
            $busqueda = $campo[4];

            if (isset($_POST[$nombre])) {
                $var_post = $_POST[$nombre];
            
                if ($var_post <> '' and $busqueda == 's') {
                    if ($tipo == 'fk') {                
                        $whereRaw .= " and $nombre = $var_post";
                    }
                    else {
                        $whereRaw .= " and $nombre like '%$var_post%'";
                    }
                }
            }
        }

        if (Auth::user()->empresa->prefijo == 'mig') {
            $Contactos = Contacto_mig::whereRaw("($whereRaw)")->get();
        }
        
        if (Auth::user()->empresa->prefijo == 'gru') {
            $Contactos = Contacto_gru::whereRaw("($whereRaw)")->get();
        }
        
        if (Auth::user()->empresa->prefijo == 'tyt') {
            $Contactos = Contacto_tyt::whereRaw("($whereRaw)")->get();
        }
        
        if (Auth::user()->empresa->prefijo == 'cuo') {
            $Contactos = Contacto_cuo::whereRaw("($whereRaw)")->get();
        }

        $cant_contactos = count($Contactos);
//dd($Contactos);
        
        return View('listas/filtrar-contactos')        
        ->with('cant_contactos', $cant_contactos)       
        ->with('Contactos', $Contactos)        
        ->with('campos', $campos);                         

    }


    public function generarInstancias(request $response)
    {  

        $cant_listas = $_POST['cant_listas'];
        $cant_contactos = $_POST['cant_contactos'];
        $cantidad_sel = 0;
        $nombre = $_POST['nombre'];
        $codigo_tel = $_POST['codigo_tel'];
        $titulo_mensaje_1 = $_POST['titulo_mensaje_1'];
        $mensaje_1 = $_POST['mensaje_1'];
        $titulo_mensaje_2 = $_POST['titulo_mensaje_2'];
        $mensaje_2 = $_POST['mensaje_2'];
        $titulo_mensaje_3 = $_POST['titulo_mensaje_3'];
        $mensaje_3 = $_POST['mensaje_3'];
        $titulo_mensaje_4 = $_POST['titulo_mensaje_4'];
        $mensaje_4 = $_POST['mensaje_4'];
        $titulo_mensaje_5 = $_POST['titulo_mensaje_5'];
        $mensaje_5 = $_POST['mensaje_5'];

        
        $Encabezado_de_envio = new Encabezado_de_envio;
        $Encabezado_de_envio->nombre = $nombre;
        $Encabezado_de_envio->codigo_tel = $codigo_tel;
        $Encabezado_de_envio->titulo_mensaje_1 = $titulo_mensaje_1;
        $Encabezado_de_envio->mensaje_1 = $mensaje_1;
        $Encabezado_de_envio->titulo_mensaje_2 = $titulo_mensaje_2;
        $Encabezado_de_envio->mensaje_2 = $mensaje_2;
        $Encabezado_de_envio->titulo_mensaje_3 = $titulo_mensaje_3;
        $Encabezado_de_envio->mensaje_3 = $mensaje_3;
        $Encabezado_de_envio->titulo_mensaje_4 = $titulo_mensaje_4;
        $Encabezado_de_envio->mensaje_4 = $mensaje_4;
        $Encabezado_de_envio->titulo_mensaje_5 = $titulo_mensaje_5;
        $Encabezado_de_envio->mensaje_5 = $mensaje_5;
        $Encabezado_de_envio->save();

        $encabezado_de_envio_id = $Encabezado_de_envio->id;

        for ($i=1; $i <= $cant_listas; $i++) { 

            $nombre_de_la_lista = 'Lista '.$i;
            $hash = md5(rand());

            $Lista_de_envio = new Lista_de_envio;
            $Lista_de_envio->encabezado_de_envio_id = $encabezado_de_envio_id;
            $Lista_de_envio->nombre_de_la_lista = $nombre_de_la_lista;
            $Lista_de_envio->hash = $hash;
            $Lista_de_envio->save();
        }

        $Listas_de_envios = Lista_de_envio::where('encabezado_de_envio_id', $encabezado_de_envio_id)->get();



        for ($i=1; $i <= $cant_contactos; $i++) { 
            if (isset($_POST['contacto_'.$i])) {
                $cantidad_sel++;
            }
        }
        

        $cant_por_lista = intval($cantidad_sel/$cant_listas);
        $cont_cant_por_lista = 1;
        $indice_lista_de_envio = 0;
        
        //dd($Listas_de_envios);

        for ($i=1; $i <= $cant_contactos; $i++) { 
            if (isset($_POST['contacto_'.$i])) {

                $instancia_de_envio_id = $Listas_de_envios[$indice_lista_de_envio]->id;
                $contacto_id = $_POST['contacto_'.$i];

                $Instancia_de_envio = new Instancia_de_envio;
                $Instancia_de_envio->lista_de_envio_id = $instancia_de_envio_id;
                $Instancia_de_envio->contacto_id = $contacto_id;
                $Instancia_de_envio->save();

                if ($cont_cant_por_lista < $cant_por_lista or $indice_lista_de_envio == count($Listas_de_envios)-1) {
                    $cont_cant_por_lista++;
                }
                else {
                    $indice_lista_de_envio++;
                    $cont_cant_por_lista = 1;
                }
            }
        }
        
        return View('listas/encabezado-de-envio')        
        ->with('Encabezado_de_envio', $Encabezado_de_envio)       
        ->with('Listas_de_envios', $Listas_de_envios);          
                       

    }


    public function verListas($encabezado_de_envio_id)
    {  

        $Encabezado_de_envio = Encabezado_de_envio::find($encabezado_de_envio_id);
        $Listas_de_envios = Lista_de_envio::where('encabezado_de_envio_id', $encabezado_de_envio_id)->get();
        
        return View('listas/encabezado-de-envio')        
        ->with('Encabezado_de_envio', $Encabezado_de_envio)       
        ->with('Listas_de_envios', $Listas_de_envios);          
                       

    }



    public function get_origenes_tyt()
    {
        $Origenes_mig = Origen_mig::orderBy('origen')->get();

        $array = array();
        $array[null] = '';
        foreach ($Origenes_mig as $Origen_mig) {
            $array[$Origen_mig->id] = $Origen_mig->origen;
        }

        return $array;
    }


    public function get_tipos_de_negocio_tyt()
    {
        $Tipos_de_negocio_tyt = Tipo_de_negocio_tyt::orderBy('tipo_de_negocio')->get();

        $array = array();
        $array[null] = '';
        foreach ($Tipos_de_negocio_tyt as $Tipo_de_negocio_tyt) {
            $array[$Tipo_de_negocio_tyt->id] = $Tipo_de_negocio_tyt->tipo_de_negocio;
        }

        return $array;
    }


    public function get_regimenes_de_visita_tyt()
    {
        $Regimenes_de_visita_tyt = Regimen_de_visita_tyt::orderBy('regimen_de_visita')->get();

        $array = array();
        $array[null] = '';
        foreach ($Regimenes_de_visita_tyt as $Regimen_de_visita_tyt) {
            $array[$Regimen_de_visita_tyt->id] = $Regimen_de_visita_tyt->regimen_de_visita;
        }

        return $array;
    }


    public function get_niveles_de_compra()
    {
        $Niveles_de_compra_tyt = Nivel_de_compra_tyt::orderBy('nivel_de_compra')->get();

        $array = array();
        $array[null] = '';
        foreach ($Niveles_de_compra_tyt as $Nivel_de_compra_tyt) {
            $array[$Nivel_de_compra_tyt->id] = $Nivel_de_compra_tyt->nivel_de_compra;
        }

        return $array;
    }


    public function listEnvios($lista_de_envio_id, $hash)
    {  
       
        $Lista_de_envio = DB::table('listas_de_envios as l')
        ->select(DB::Raw('e.nombre, e.titulo_mensaje_1, e.titulo_mensaje_2, e.titulo_mensaje_3, e.titulo_mensaje_4, e.titulo_mensaje_5, e.mensaje_1, e.mensaje_2, e.mensaje_3, e.mensaje_4, e.mensaje_5, l.nombre_de_la_lista, l.hash'))
        ->leftjoin('encabezados_de_envios as e', 'e.id', '=', 'l.encabezado_de_envio_id')
        ->where('l.id', $lista_de_envio_id)
        ->first();



        $ListasController = new ListasController();
        
        
        if ($Lista_de_envio->hash == $hash) {   



            $campos = [];
            if (Auth::user()->empresa->prefijo == 'mig') {
                
                $campos = [
                    ['nombre', 'Nombre', 't'],
                    ['apellido', 'Apellido', 't'],
                    ['email', 'Email', 't'],
                    ['telefono', 'Telefono', 't'],
                    ['origen', 'Origen', 't']
                ];


                $Contactos = DB::table('instancias_de_envios as i')
                ->select(DB::Raw('i.id, i.contacto_id, i.sino_envio_1, i.sino_envio_2, i.sino_envio_3, i.sino_envio_4, i.sino_envio_5, i.sino_deshabilitar, e.codigo_tel, c.nombre, c.apellido, c.email, c.telefono, c.observaciones, o.origen'))
                ->join('contactos_mig as c', 'c.id', '=', 'i.contacto_id')
                ->join('listas_de_envios as l', 'l.id', '=', 'i.lista_de_envio_id')
                ->join('encabezados_de_envios as e', 'e.id', '=', 'l.encabezado_de_envio_id')
                ->join('origenes_mig as o', 'o.id', '=', 'c.origen_mig_id')
                ->where('i.lista_de_envio_id', $lista_de_envio_id)
                ->whereRaw('(c.sino_deshabilitar IS NULL or c.sino_deshabilitar = "NO")')
                ->get();

            }

            if (Auth::user()->empresa->prefijo == 'gru') {

                $campos = [
                    ['nombre', 'Nombre', 't'],
                    ['apellido', 'Apellido', 't'],
                    ['email_correo', 'Email', 't'],
                    ['telefono', 'Telefono', 't']
                ];


                $Contactos = DB::table('instancias_de_envios as i')
                ->select(DB::Raw('i.id, i.contacto_id, i.sino_envio_1, i.sino_envio_2, i.sino_envio_3, i.sino_envio_4, i.sino_envio_5, i.sino_deshabilitar, e.codigo_tel, c.nombre, c.apellido, c.email_correo, c.telefono, c.observaciones'))
                ->join('contactos_gru as c', 'c.id', '=', 'i.contacto_id')
                ->join('listas_de_envios as l', 'l.id', '=', 'i.lista_de_envio_id')
                ->join('encabezados_de_envios as e', 'e.id', '=', 'l.encabezado_de_envio_id')
                ->where('i.lista_de_envio_id', $lista_de_envio_id)
                ->whereRaw('(c.sino_deshabilitar IS NULL or c.sino_deshabilitar = "NO")')
                ->get();
            }


            if (Auth::user()->empresa->prefijo == 'tyt') {

                
                $campos = [
                    ['nombre_de_fantasia', 'Nombre de Fantasia', 't'],
                    ['codigo', 'Codigo', 't'],
                    ['cuenta', 'Cuenta', 't'],
                    ['localidad', 'Localidad', 't'],
                    ['vendedor', 'Vendedor', 't'],
                    ['nombre_del_responsable_de_compra', 'nombre resp compra', 't'],
                    ['tipo_de_negocio', 'Tipo de Negocio', 't'],
                    ['regimen_de_visita', 'Regimen de visita', 't'],
                    ['nivel_de_compra', 'Nivel de Compra', 't']
                ];


                $Contactos = DB::table('instancias_de_envios as i')
                ->select(DB::Raw('i.id, i.contacto_id, i.sino_envio_1, i.sino_envio_2, i.sino_envio_3, i.sino_envio_4, i.sino_envio_5, i.sino_deshabilitar, e.codigo_tel, c.celular_del_responsable_de_compra as telefono , c.nombre_de_fantasia, c.codigo, c.cuenta, c.localidad, c.vendedor, c.nombre_del_responsable_de_compra, c.observaciones, nc.nivel_de_compra, tn.tipo_de_negocio, rv.regimen_de_visita'))
                ->join('contactos_tyt as c', 'c.id', '=', 'i.contacto_id')
                ->join('listas_de_envios as l', 'l.id', '=', 'i.lista_de_envio_id')
                ->join('encabezados_de_envios as e', 'e.id', '=', 'l.encabezado_de_envio_id')
                ->join('niveles_de_compra_tyt as nc', 'nc.id', '=', 'c.nivel_de_compra_tyt_id')
                ->join('tipos_de_negocio_tyt as tn', 'tn.id', '=', 'c.tipo_de_negocio_tyt_id')
                ->join('regimenes_de_visita_tyt as rv', 'rv.id', '=', 'c.regimen_de_visita_tyt_id')
                ->where('i.lista_de_envio_id', $lista_de_envio_id)
                ->whereRaw('(c.sino_deshabilitar IS NULL or c.sino_deshabilitar = "NO")')
                ->get();



            }



            if (Auth::user()->empresa->prefijo == 'cuo') {

                $campos = [
                    ['nombre', 'Nombre', 't'],
                    ['direccion', 'Direccion', 't'],
                    ['moneda_documentos', 'Documentos', 't']
                ];


                $Contactos = DB::table('instancias_de_envios as i')
                ->select(DB::Raw('i.id, i.contacto_id, i.sino_envio_1, i.sino_envio_2, i.sino_envio_3, i.sino_envio_4, i.sino_envio_5, i.sino_deshabilitar, e.codigo_tel, c.nombre, c.direccion, c.moneda_documentos, c.celular telefono, c.observaciones '))
                ->join('contactos_cuo as c', 'c.id', '=', 'i.contacto_id')
                ->join('listas_de_envios as l', 'l.id', '=', 'i.lista_de_envio_id')
                ->join('encabezados_de_envios as e', 'e.id', '=', 'l.encabezado_de_envio_id')
                ->where('i.lista_de_envio_id', $lista_de_envio_id)
                ->whereRaw('(c.sino_deshabilitar IS NULL or c.sino_deshabilitar = "NO")')
                ->get();
            }

            
            //dd($Contactos_Historicos[1]->url_whatsapp('kkkk'));
            return View('listas/lista-de-envio')
            ->with('Lista_de_envio', $Lista_de_envio)
            ->with('Contactos', $Contactos)
            ->with('campos', $campos)
            ->with('ListasController', $ListasController);            
        }
        else {
            echo 'ERROR';
        }  

    }

}
