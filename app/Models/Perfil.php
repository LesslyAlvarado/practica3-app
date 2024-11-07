<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    //Asignamos el nombre de la tabla
    protected $table = "perfiles";
    
    protected $fillable = [
        "nombre_perfil"
    ];

    //Dsesabilitar los campos de created_at y update_at
    public $timestamps = false;
}
