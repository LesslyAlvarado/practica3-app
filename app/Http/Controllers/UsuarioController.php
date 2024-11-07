<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    // public function postApiValidacion()
    // {
    //     // $usuario = "admin";
    //     // $password = "123";

    //     if ($usuario = "admin" && $password = "123") {
    //         return true;
    //     }
    //     else
    //     {
    //         return false;
    //     }

    // }

    public function index()
    {
        $usuario_Temp = "Admin";
        $password_Temp = "123";

        //Buscar si existe un usuario con este nombre
        $usuario_existe = Usuario::where("nombre", $usuario_Temp)->first();

        if ($usuario_existe != null) {
            return [
                "message" => "El usuario existe",
                "error" => true
            ];
        }

        $usuario = new Usuario();
        $usuario->nombre = $usuario_Temp;
        $usuario->password = Hash::make($password_Temp);

        $usuario->save();
    }

    // public function login()
    // {
    //     //Validar si las credenciales existen
    //     $usuario_login = "jose";
    //     $usuario_password = "123";

    //     //Se hace la consulta a la base de datos
    //     $usuario_existe = Usuario::where("nombre", $usuario_login)->first();

    //     //Validamos la cantidad de registros con la información que se está validando
    //     if ($usuario_existe && Hash::check($usuario_password, $usuario_existe->password)) {
    //         return ["message" => "Credenciales válidas",
    //                 "exist" => true    
    //         ];
    //     }
    //     else
    //     {
    //         return ["message" => "Credenciales no válidas",
    //                 "exist" => false    
    //         ];
    //     }
    // }

    public function login(Request $request)
    {
        $data = $request->all();

        //Validar si las credenciales existen
        $usuario_login = $data['usuario'];
        $usuario_password = $data['contrasenia'];

        //Se hace la consulta a la base de datos
        $usuario_existe = Usuario::where("nombre", $usuario_login)->first();

        // $perfil = $usuario_existe->perfil;

        //Validamos la cantidad de registros con la información que se está validando
        if ($usuario_existe && Hash::check($usuario_password, $usuario_existe->password)) {
            return [
                "message" => "Credenciales válidas",
                "data" => $usuario_existe->id,
                "nombre" => $usuario_existe->nombre,
                "perfil" => $usuario_existe->perfil->id,
                "exist" => true
            ];
        } else {
            return [
                "message" => "Credenciales no válidas",
                "exist" => false
            ];
        }
    }

    public function autoregistro(Request $request)
    {
        $data = $request->all();

        //Validar si las credenciales existen
        $usuario_login = $data['usuario'];
        $usuario_password = $data['contrasenia'];

        //Se hace la consulta a la base de datos
        $usuario_existe = Usuario::where("nombre", $usuario_login)->first();

        //Validamos la cantidad de registros con la información que se está validando
        if ($usuario_existe) {
            return [
                "message" => "Ya existe un usuario con ese nombre",
                "error" => true
            ];
        } else {
            $usuario = new Usuario();
            $usuario->nombre = $usuario_login;
            $usuario->password = Hash::make($usuario_password);
            $usuario->perfil_id = 1;
            $usuario->save();

            return [
                "message" => "Usuario registrado",
                "error" => false
            ];
        }
    }

    //EJEMPLOS APIISSSSSSSS
    public function getAPIAll()
    {
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $usuarios = Usuario::all();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["usuarios" => $usuarios];
    }

    public function getAPIGetUsuarioByID($id = null)
    {
        //Ejecutamos el metodo find para que busque el id
        $usuario = Usuario::find($id);
        return $usuario;
    }

    public function deleteAPI($id)
    {
        //Se consulta el usuario con base al parámetro de id
        //Se usa el método find
        //SELECT * FROM usuarios WHERE id=1
        $usuario = Usuario::find($id);
        //Se borra la imagen
        if (!empty($usuario->imagen)) {
            Storage::delete(public_path('imagenes_usuarios') . '/' . $usuario->imagen);
        }
        //Ejecutamos el método para eliminar
        //DELETE FROM usuarios WHERE id=1
        $usuario->delete();
    }

    public function postApiAddUsuario(Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $usuario = new Usuario();

        //Generamos un nombre aleatorio y concatenamos la estensión de la imagen
        if ($request->hasFile('imagen')) {
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta única
            $request->imagen->move(public_path('imagenes_usuarios'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }

        //Se asignan los parámetros de la petición del objeto
        $usuario->nombre = $data['nombre'];
        $usuario->password = Hash::make($data['password']);
        // Asignamos el perfil_id recibido del formulario
        $usuario->perfil_id = $data['perfil_id']; // Este es el valor enviado desde el select

        if ($request->hasFile('imagen')) {
            $usuario->imagen = $ruta_archivo_original;
        }

        $usuario->save();
    }

    public function putApiUpdateUsuario($id, Request $request)
    {
        //Obtenemos los parámetros que nos manda la petición
        $data = $request->all();

        //Establecemos un nombre de la imagen
        $ruta_archivo_original = null;

        //Instanciamos un objeto usuario
        $usuario = Usuario::find($id);

        //Validamos si la imagen se está enviando
        if ($request->hasFile('imagen')) {
            //Valido si van a modificar la imagen y si tengo una imagen en la base de datos
            if ($usuario->imagen != null) {
                //Eliminar la imagen que se tiene en base de datos 
                Storage::delete(public_path('imagenes_usuarios') . '/' . $usuario->imagen);
            }
            //Generamos un nombre aliatorio y concatenamos la extension de la imagen
            $nombreImagen = time() . '.' . $request->imagen->extension();
            //Movemos el archivo a la carpeta publica con el nombre
            $request->imagen->move(public_path('imagenes_usuarios'), $nombreImagen);
            //Asignamos el nombre del archivo
            $ruta_archivo_original = $nombreImagen;
        }
        //se asignan los parametros de la peticion a objeto
        $usuario->nombre = $data['nombre'];
        $usuario->password = Hash::make($data['password']);
        $usuario->perfil_id = $data['perfil_id']; // Este es el valor enviado desde el select

        if ($request->hasFile('imagen')) {
            $usuario->imagen = $ruta_archivo_original;
        }

        //Se ejecuta el metodo save para agregar o modificar el registro
        $usuario->save();
    }

    public function getAPIAllFiltro(Request $request)
    {
        //
        $data = $request->all();
        $filtro = $request->input("filtro");
        //Se usa el método all para obtener todos los usuarios
        //"SELECT * FROM usuarios"
        $Usuarios = Usuario::Where('nombre', 'like', "%$filtro%")->get();
        //Ya no devuelve view, sino un arreglo, porque eso es un JSON, un arreglo
        return ["Usuarios" => $Usuarios];
    }
}
