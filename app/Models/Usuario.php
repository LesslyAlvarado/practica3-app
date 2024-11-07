<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Model
{
    protected $table = "usuarios";
    
    protected $fillable = [
        "id",
        "nombre",
        "password",
        "perfil_id"
    ];
    //Dsesabilitar los campos de created_at y update_at
    public $timestamp = false;

    //Creamos un método que herede de la ralación 1 a 1
    public function perfil(): HasOne
    {
        //Vincular los campos relacionados 
        return $this->hasOne(Perfil::class, "id", 'perfil_id');
    }
}
