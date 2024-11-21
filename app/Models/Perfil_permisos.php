<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil_permisos extends Model
{
    //Asignamos el nombre de la tabla
    protected $table = "perfil_permisos";
    
    protected $fillable = [
        "id",
        "perfil_id",
        "permiso_id"
    ];

    //Dsesabilitar los campos de created_at y update_at
    public $timestamps = false;
}
