<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Perfil;

class PerfilController extends Controller
{
    //EJEMPLOS APIISSSSSSSS
    public function getAPIAll()
    {
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $perfiles = Perfil::all();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["perfiles" => $perfiles];
    }

    public function getAPIGetPerfilByID($id = null)
    {
        //Ejecutamos el metodo find para que busque el id
        $perfil = Perfil::find($id);
        return $perfil;
    }

    public function deleteAPI($id)
    {
        //Se consulta el usuario con base al parámetro de id
        //Se usa el método find
        //SELECT * FROM usuarios WHERE id=1
        $perfil = Perfil::find($id);
        //Se borra la imagen
        if (!empty($perfil->imagen)) {
            Storage::delete(public_path('imagenes_perfiles') . '/' . $perfil->imagen);
        }
        //Ejecutamos el método para eliminar
        //DELETE FROM usuarios WHERE id=1
        $perfil->delete();
    }

    public function postApiAddPerfil(Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $perfil = new Perfil();

        //Generamos un nombre aleatorio y concatenamos la estensión de la imagen
        if ($request->hasFile('imagen')) {
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta única
            $request->imagen->move(public_path('imagenes_perfiless'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }

        //Se asignan los parámetros de la petición del objeto
        $perfil->nombre_perfil = $data['nombre_perfil'];

        if ($request->hasFile('imagen')) {
            $perfil->imagen = $ruta_archivo_original;
        }

        $perfil->save();
    }

    public function putApiUpdatePerfil($id, Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $perfil = Perfil::find($id);

        //Validamos si la imagen se está enviando
        if ($request->hasFile('imagen')) {
            //Valido si van a modificar la imagen y si tengo una imagen en la base de datos
            if ($perfil->imagen != null) {
                //Eliminar la imagen que se tiene en base de datos 
                Storage::delete(public_path('imagenes_perfiles') . '/' . $perfil->imagen);
            }
            //Generamos un nombre aliatorio y concatenamos la extension de la imagen
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta publica con el nombre
            $request->imagen->move(public_path('imagenes_perfiles'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }
        //se asignan los parametros de la peticion a objeto
        $perfil->nombre_perfil = $data['nombre_perfil'];

        if ($request->hasFile('imagen')) {
            $perfil->imagen = $ruta_archivo_original;
        }

        //Se ejecuta el metodo save para agregar o modificar el registro
        $perfil->save();
    }

    public function getAPIAllFiltro(Request $request)
    {
        //
        $data = $request->all();
        $filtro = $request->input("filtro");
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $Perfiles = Perfil::Where('nombre', 'like', "%$filtro%")->get();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["Perfiles" => $Perfiles];
    }
}
