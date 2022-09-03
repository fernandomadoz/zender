<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto_cuo extends Model
{
    protected $guarded = ['id'];    

    protected $table = 'contactos_cuo';

    public $timestamps = false;
}
