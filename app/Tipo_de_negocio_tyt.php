<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo_de_negocio_tyt extends Model
{
	protected $guarded = ['id'];    


    public function descrip_modelo()
    {
        return $this->tipo_de_negocio;
    }

    protected $table = 'tipos_de_negocio_tyt';

    public $timestamps = false;
}
