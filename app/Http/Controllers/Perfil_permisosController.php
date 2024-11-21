<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perfil_permisos;
use App\Models\Permisos;
use App\Models\Perfil;
use Illuminate\Support\Facades\Storage;


class Perfil_permisosController extends Controller
{

    public function index($Perfil_id)
    {

        $Perfil = Perfil::find($Perfil_id);
        $Lista_Permisos = Permisos::all();
        $permisosAsignados = Perfil_permisos::Where("perfil_id", $Perfil_id)->get();
        return ["Perfil" => $Perfil, "Permisos" => $Lista_Permisos, "Permisos_Asignados" => $permisosAsignados];
    }

    public function asignar_permisos($Perfil_id, Request $request)
    {
        $data = $request->all();
        //Validar el id del perfil
        if($data['id']) {
            //SELECT * FROM perfil_permisos WHERE perfil_id=1
            //DELETE de todos los registros que me devuelva la consulta SELECT
            Perfil_permisos::Where("perfil_id", $data['id'])->delete();

            foreach($data['permisos'] as $permiso) {
                $perfil_permiso = new Perfil_permisos();
                $perfil_permiso->perfil_id = $data['id'];
                $perfil_permiso->permiso_id = $permiso;
                $perfil_permiso->save();
            }
        }
    }

    // public function asignar_permisos(Request $request, $perfilId)
    // {
    //     // Validar la entrada
    //     $request->validate([
    //         'permisos' => 'array|required', // Se espera un array con los IDs de los permisos
    //         'permisos.*' => 'exists:permisos,id' // Cada permiso debe existir en la tabla permisos
    //     ]);

    //     // Buscar el perfil por ID
    //     $perfil = Perfil::find($perfilId);

    //     if (!$perfil) {
    //         return response()->json([
    //             'message' => 'Perfil no encontrado'
    //         ], 404);
    //     }

    //     // Sincronizar permisos: elimina los existentes y agrega los nuevos
    //     $perfil->permisos()->sync($request->permisos);

    //     return response()->json([
    //         'message' => 'Permisos actualizados con éxito',
    //         'perfil' => $perfil,
    //         'permisos_asignados' => $perfil->permisos
    //     ]);
    // }



    //EJEMPLOS APIISSSSSSSS
    public function getAPIAll()
    {
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $perfil_permisos = Perfil_permisos::all();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["permisos" => $perfil_permisos];
    }

    public function getAPIGetPerfil_permisosByID($id = null)
    {
        //Ejecutamos el metodo find para que busque el id
        $perfil_permiso = Perfil_permisos::find($id);
        return $perfil_permiso;
    }

    public function deleteAPI($id)
    {
        //Se consulta el usuario con base al parámetro de id
        //Se usa el método find
        //SELECT * FROM usuarios WHERE id=1
        $perfil_permiso = Perfil_permisos::find($id);
        //Se borra la imagen
        if (!empty($perfil_permiso->imagen)) {
            Storage::delete(public_path('imagenes_perfil_permisos') . '/' . $perfil_permiso->imagen);
        }
        //Ejecutamos el método para eliminar
        //DELETE FROM usuarios WHERE id=1
        $perfil_permiso->delete();
    }

    public function postApiAddPerfil_permisos(Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $perfil_permiso = new Perfil_permisos();

        //Generamos un nombre aleatorio y concatenamos la estensión de la imagen
        if ($request->hasFile('imagen')) {
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta única
            $request->imagen->move(public_path('imagenes_perfil_permisos'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }

        //Se asignan los parámetros de la petición del objeto
        $perfil_permiso->perfil_id = $data['perfil_id'];
        $perfil_permiso->permiso_id = $data['permiso_id'];
        // $permiso->password = Hash::make($data['password']);
        // Asignamos el perfil_id recibido del formulario
        // $permiso->perfil_id = $data['perfil_id']; // Este es el valor enviado desde el select

        if ($request->hasFile('imagen')) {
            $perfil_permiso->imagen = $ruta_archivo_original;
        }

        $perfil_permiso->save();
    }

    public function putApiUpdatePerfil_permisos($id, Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $perfil_permiso = Perfil_permisos::find($id);

        //se asignan los parametros de la peticion a objeto
        $perfil_permiso->perfil_id = $data['perfil_id'];
        $perfil_permiso->permiso_id = $data['permiso_id'];
        // $permiso->perfil_id = $data['perfil_id']; // Este es el valor enviado desde el select

        //Se ejecuta el metodo save para agregar o modificar el registro
        $perfil_permiso->save();
    }

    public function getAPIAllFiltro(Request $request)
    {
        //
        $data = $request->all();
        $filtro = $request->input("filtro");
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $Perfil_permisos = Perfil_permisos::Where('nombre', 'like', "%$filtro%")->get();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["Perfil_permisos" => $Perfil_permisos];
    }
}
