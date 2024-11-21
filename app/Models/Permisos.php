<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    //Asignamos el nombre de la tabla
    protected $table = "permisos";
    
    protected $fillable = [
        "id",
        "nombre",
        "clave"
    ];

    //Dsesabilitar los campos de created_at y update_at
    public $timestamps = false;
}
