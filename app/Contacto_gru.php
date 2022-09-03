<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto_gru extends Model
{
	protected $guarded = ['id'];    

    protected $table = 'contactos_gru';

    public $timestamps = false;
}
