<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto_mig extends Model
{
	protected $guarded = ['id'];    

    public function origen_mig()
    {
        return $this->belongsTo('App\Origen_mig');
    } 
    protected $table = 'contactos_mig';

    public $timestamps = false;
}
