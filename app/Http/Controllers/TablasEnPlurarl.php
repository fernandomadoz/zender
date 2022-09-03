<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TablasEnPlurarl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function tablasEnPlural() {

        $tb_plural_distintas = [
            "Rol_de_usuario" => "roles_de_usuario",
            "Encabezado_de_envio" => "encabezados_de_envios",
            "Lista_de_envio" => "listas_de_envios",
            "Instancia_de_envio" => "instancias_de_envios",
            "Envio_a_contacto" => "envios_a_contactos",
            "Origen_mig" => "origenes_mig",
            "Opcion" => "opciones",
            "Contacto_gru" => "contactos_gru",
            "Contacto_mig" => "contactos_mig",
            "Contacto_tyt" => "contactos_tyt",
            "Contacto_cuo" => "contactos_cuo",
            "Tipo_de_negocio_tyt" => "tipos_de_negocio_tyt",
            "Nivel_de_compra_tyt" => "niveles_de_compra_tyt",
            "Regimen_de_visita_tyt" => "regimenes_de_visita_tyt",
        ];
        
        return $tb_plural_distintas;

    }


}
