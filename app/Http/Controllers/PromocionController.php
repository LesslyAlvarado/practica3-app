<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Promocion;
use Carbon\Carbon;

class PromocionController extends Controller
{
    
    public function index()
    {
        //trae la fecha y hora actual
        $fecha_inicio = Carbon::now()->subHours(6);
        //trae la fecha y hora actual + 2 minutos
        $fecha_fin = Carbon::now()->addHours(-6)->addMinutes(2);
        //trae la fecha y hora actual + 2 segundos
        // $afterM = Carbon::now()->addMonths(2); //igual gunciona como resta, poner -2
        $fecha_especifica = Carbon::create('2024-12-31 12:00:00');

        //Registro....
        $periodo = new Promocion();
        $periodo->nombre_promocion = "Black Friday";
        $periodo->porcentaje_descuento = 50;
        $periodo->fecha_inicio = $fecha_inicio;
        $periodo->fecha_fin = $fecha_fin;
        $periodo->save();

        return [
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'fecha_especifica' => $fecha_especifica,

        ];
    }
    
    //EJEMPLOS APIISSSSSSSS
    public function getAPIAll()
    {
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $promociones = Promocion::all();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["promociones" => $promociones];
    }

    public function getAPIGetPerfilByID($id = null)
    {
        //Ejecutamos el metodo find para que busque el id
        $promocion = Promocion::find($id);
        return $promocion;
    }

    public function deleteAPI($id)
    {
        //Se consulta el usuario con base al parámetro de id
        //Se usa el método find
        //SELECT * FROM usuarios WHERE id=1
        $promocion = Promocion::find($id);
        //Se borra la imagen
        if (!empty($promocion->imagen)) {
            Storage::delete(public_path('imagenes_promocion') . '/' . $promocion->imagen);
        }
        //Ejecutamos el método para eliminar
        //DELETE FROM usuarios WHERE id=1
        $promocion->delete();
    }

    public function postApiAddPerfil(Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $promocion = new Promocion();

        //Generamos un nombre aleatorio y concatenamos la estensión de la imagen
        if ($request->hasFile('imagen')) {
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta única
            $request->imagen->move(public_path('imagenes_promocion'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }

        //Se asignan los parámetros de la petición del objeto
        $promocion->nombre_perfil = $data['nombre_promocion'];
        //faltan campos

        if ($request->hasFile('imagen')) {
            $promocion->imagen = $ruta_archivo_original;
        }

        $promocion->save();
    }

    public function putApiUpdatePerfil($id, Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $promocion = Promocion::find($id);

        //Validamos si la imagen se está enviando
        if ($request->hasFile('imagen')) {
            //Valido si van a modificar la imagen y si tengo una imagen en la base de datos
            if ($promocion->imagen != null) {
                //Eliminar la imagen que se tiene en base de datos 
                Storage::delete(public_path('imagenes_perfiles') . '/' . $promocion->imagen);
            }
            //Generamos un nombre aliatorio y concatenamos la extension de la imagen
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta publica con el nombre
            $request->imagen->move(public_path('imagenes_perfiles'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }
        //se asignan los parametros de la peticion a objeto
        $promocion->nombre_perfil = $data['nombre_perfil'];
        //Faltan camposss

        if ($request->hasFile('imagen')) {
            $promocion->imagen = $ruta_archivo_original;
        }

        //Se ejecuta el metodo save para agregar o modificar el registro
        $promocion->save();
    }

    public function getAPIAllFiltro(Request $request)
    {
        //
        $data = $request->all();
        $filtro = $request->input("filtro");
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $Perfiles = Promocion::Where('nombre', 'like', "%$filtro%")->get();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["Perfiles" => $Perfiles];
    }
}
