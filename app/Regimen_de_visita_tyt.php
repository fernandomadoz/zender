<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regimen_de_visita_tyt extends Model
{
	protected $guarded = ['id'];    


    public function descrip_modelo()
    {
        return $this->regimen_de_visita;
    }

    protected $table = 'regimenes_de_visita_tyt';

    public $timestamps = false;
}
