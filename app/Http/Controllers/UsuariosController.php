<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Usuarios;
use App\PermisosUsuario;
use App\permisos;


class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //se agregan validaciones de requeridos
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
            'names' => 'required',
            'paternal_surname' => 'required',
            'role' => 'required',
            'permisos' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        try
        {
            //si pasan las validaciones se agrega el usuario
            $usuario = new Usuarios;
            $usuario->username = $request->username;
            $usuario->email = $request->email;
            $usuario->names = $request->names;
            $usuario->paternal_surname = $request->paternal_surname;
            $usuario->maternal_surname = $request->maternal_surname;
            $usuario->age = $request->age;
            $usuario->role = $request->role;
            $usuario->activo = true;
            $usuario->save();
            $mensaje = "Todo bien.";

            //se obtienes los permisos que tiene el ROLE
            $permisosRole = DB::table('roles')
                ->leftJoin('roles_permisos', 'roles.id', '=', 'roles_permisos.role_id')
                ->leftJoin('permisos', 'roles_permisos.permiso_id', '=', 'permisos.id')
                ->where('roles.id',$request->role)
                ->get('permisos.nombre');

            //se agregan los permisos del ROLE mas los que trae del post
            $permisosRequest = $request->permisos;
            foreach ($permisosRole as $permisoRole) {
                $permisosRequest[]= $permisoRole->nombre;
            }

            //elimina permisos duplicados
            $permisosRequest = array_unique($permisosRequest);

            foreach ($permisosRequest as $permiso) {
              
                $permisoDB = permisos::where('nombre', $permiso)->first();
              
                if($permisoDB)
                {
                    $permisoUsuario = new PermisosUsuario;
                    $permisoUsuario->permiso_id = $permisoDB->id;
                    $permisoUsuario->usuario_id = $usuario->id;
                    $permisoUsuario->save();
                }else{
                    $mensaje .= " El permiso $permiso no existe en nuestra base de datos.";
                }
                
            }        

            return response()->json([
                'status' => 200,
                'message' => $mensaje
            ]);
        }
        catch(\Exception $e)
        {
           return response()->json([
                'status' => "Error",
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Usuarios::where('id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $usuario_id)
    {
        //se agregan validaciones de requeridos
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
            'names' => 'required',
            'paternal_surname' => 'required',
            'role' => 'required',
            'permisos' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try
        {
            //si pasan las validaciones se agrega el usuario
            Usuarios::find($usuario_id)->update($request->all());
           
            $mensaje = "Todo bien.";

            // //se obtienes los permisos que tiene el ROLE
            $permisosRole = DB::table('roles')
                ->leftJoin('roles_permisos', 'roles.id', '=', 'roles_permisos.role_id')
                ->leftJoin('permisos', 'roles_permisos.permiso_id', '=', 'permisos.id')
                ->where('roles.id',$request->role)
                ->get('permisos.nombre');

            // //se agregan los permisos del ROLE mas los que trae del post
            $permisosRequest = $request->permisos;
            foreach ($permisosRole as $permisoRole) {
                $permisosRequest[]= $permisoRole->nombre;
            }
            $permisosRequest = array_unique($permisosRequest);
            PermisosUsuario::where('usuario_id',$usuario_id)->delete();
            
            foreach ($permisosRequest as $permiso) {
              
                $permisoDB = permisos::where('nombre', $permiso)->first();

                if($permisoDB)
                {
                    $permisoUsuario = new PermisosUsuario;
                    $permisoUsuario->permiso_id = $permisoDB->id;
                    $permisoUsuario->usuario_id = $usuario_id;
                    $permisoUsuario->save();
                }else{
                    echo $mensaje .= " El permiso $permiso no existe en nuestra base de datos.";

                }
            }        
            return response()->json([
                'status' => 200,
                'message' => $mensaje
            ]);
        }
        catch(\Exception $e)
        {
           return response()->json([
                'status' => "Error",
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Usuarios::find($id)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Registro Eliminado'
        ]);

    }

    public function obtenerUsuarios()
    {
        $usuarios = DB::table('usuarios')->get();
        return response()->json($usuarios);
    }

    public function obtenerUsuariosRole($role)
    {
        $usuarios = DB::table('usuarios')
        ->leftJoin('roles', 'usuarios.role', '=', 'roles.id')
        ->where('roles.nombre',$role)
        ->get('usuarios.*');
        return response()->json($usuarios);
    }

    public function obtenerUsuariosPermiso($permiso)
    {

        $usuarios = DB::table('usuarios')
        ->leftJoin('usuarios_permisos', 'usuarios_permisos.usuario_id', '=', 'usuarios.id')
        ->leftJoin('permisos', 'permisos.id', '=', 'usuarios_permisos.permiso_id')
        ->where('permisos.nombre',$permiso)
        ->get('usuarios.*');
        return response()->json($usuarios);
    }
    
    public function obtenerUsuariosActivos()
    {
        $usuarios = DB::table('usuarios')->where('activo',1)->get();
        return response()->json($usuarios);
    }

    public function obtenerUsuariosInactivos()
    {
        $usuarios = DB::table('usuarios')->where('activo',0)->get();
        return response()->json($usuarios);
    }
}
