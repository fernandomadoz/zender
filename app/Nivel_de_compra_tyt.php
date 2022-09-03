<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nivel_de_compra_tyt extends Model
{
	protected $guarded = ['id'];    


    public function descrip_modelo()
    {
        return $this->nivel_de_compra;
    }

    protected $table = 'niveles_de_compra_tyt';
    
    public $timestamps = false;
}
