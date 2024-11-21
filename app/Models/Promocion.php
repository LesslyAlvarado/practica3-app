<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    //Asignamos el nombre de la tabla
    protected $table = "promociones";
    
    protected $fillable = [
        "nombre_promocion",
        "procentaje_descuento",
        "fecha_inicio",
        "fecha_fin"
    ];

    //Dsesabilitar los campos de created_at y update_at
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }
}
