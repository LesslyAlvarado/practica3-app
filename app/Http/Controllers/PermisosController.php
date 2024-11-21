<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permisos;
use Illuminate\Support\Facades\Storage;

class PermisosController extends Controller
{
    //EJEMPLOS APIISSSSSSSS
    public function getAPIAll()
    {
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $permisos = Permisos::all();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["permisos" => $permisos];
    }

    public function getAPIGetPermisoByID($id = null)
    {
        //Ejecutamos el metodo find para que busque el id
        $permiso = Permisos::find($id);
        return $permiso;
    }

    public function deleteAPI($id)
    {
        //Se consulta el usuario con base al parámetro de id
        //Se usa el método find
        //SELECT * FROM usuarios WHERE id=1
        $permiso = Permisos::find($id);
        //Se borra la imagen
        if (!empty($permiso->imagen)) {
            Storage::delete(public_path('imagenes_permisos') . '/' . $permiso->imagen);
        }
        //Ejecutamos el método para eliminar
        //DELETE FROM usuarios WHERE id=1
        $permiso->delete();
    }

    public function postApiAddPermiso(Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $permiso = new Permisos();

        //Generamos un nombre aleatorio y concatenamos la estensión de la imagen
        if ($request->hasFile('imagen')) {
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta única
            $request->imagen->move(public_path('imagenes_permisos'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }

        //Se asignan los parámetros de la petición del objeto
        $permiso->nombre = $data['nombre'];
        $permiso->clave = $data['clave'];
        // $permiso->password = Hash::make($data['password']);
        // Asignamos el perfil_id recibido del formulario
        // $permiso->perfil_id = $data['perfil_id']; // Este es el valor enviado desde el select

        if ($request->hasFile('imagen')) {
            $permiso->imagen = $ruta_archivo_original;
        }

        $permiso->save();
    }

    public function putApiUpdatePermiso($id, Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $permiso = Permisos::find($id);

        //Validamos si la imagen se está enviando
        if ($request->hasFile('imagen')) {
            //Valido si van a modificar la imagen y si tengo una imagen en la base de datos
            if ($permiso->imagen != null) {
                //Eliminar la imagen que se tiene en base de datos 
                Storage::delete(public_path('imagenes_permisos') . '/' . $permiso->imagen);
            }
            //Generamos un nombre aliatorio y concatenamos la extension de la imagen
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta publica con el nombre
            $request->imagen->move(public_path('imagenes_permisos'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }
        //se asignan los parametros de la peticion a objeto
        $permiso->nombre = $data['nombre'];
        $permiso->clave = $data['clave'];
        // $permiso->perfil_id = $data['perfil_id']; // Este es el valor enviado desde el select

        if ($request->hasFile('imagen')) {
            $permiso->imagen = $ruta_archivo_original;
        }

        //Se ejecuta el metodo save para agregar o modificar el registro
        $permiso->save();
    }

    public function getAPIAllFiltro(Request $request)
    {
        //
        $data = $request->all();
        $filtro = $request->input("filtro");
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $Permisos = Permisos::Where('nombre', 'like', "%$filtro%")->get();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["Permisos" => $Permisos];
    }
}
