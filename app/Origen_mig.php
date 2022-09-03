<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Origen_mig extends Model
{
	protected $guarded = ['id'];    

    public function descrip_modelo()
    {
        return $this->origen;
    }

    protected $table = 'origenes_mig';
}
