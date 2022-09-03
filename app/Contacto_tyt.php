<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto_tyt extends Model
{
	protected $guarded = ['id'];    

    public function nivel_de_compra_tyt()
    {
        return $this->belongsTo('App\Nivel_de_compra_tyt');
    } 

    public function regimen_de_visita_tyt()
    {
        return $this->belongsTo('App\Regimen_de_visita_tyt');
    } 

    public function tipo_de_negocio_tyt()
    {
        return $this->belongsTo('App\Tipo_de_negocio_tyt');
    } 

    protected $table = 'contactos_tyt';

    public $timestamps = false;
}
