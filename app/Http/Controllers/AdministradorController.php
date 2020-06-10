<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Administrador;
use App\Empleado;
// Activamos el uso de las funciones de caché.
use Illuminate\Support\Facades\Cache;
class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
			
         
        $consulta = Administrador::query();

        

        if ($request->filled('filter'))
        {

            $CamposFiltrados = array_filter(explode (',', $request->input('filter','')));        
            foreach ($CamposFiltrados as $campoFiltro)

            {
                [$criterio,$valor] = explode(':',$campoFiltro);

                if ($criterio=='email')
                {$consulta->where($criterio,'LIKE', '%'.$valor.'%');}
                else
                {$consulta->where($criterio, $valor);}

            }
        
        }
        return response()->json(['status'=>'ok','data'=>$consulta->get()], 200);
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
        /* Necesitaremos el fabricante_id que lo recibimos en la ruta
		 #Serie (auto incremental)
		usuario','nombre_completo','correo
		Alcance */

		// Primero comprobaremos si estamos recibiendo todos los campos.
		if ( !$request->input('usuario') || !$request->input('nombre_completo') || !$request->input('correo') )
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
		}

		
		$nuevoAdministrador=Administrador::create($request->all());


        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        return response()->json($nuevoAdministrador);    

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
		//
		// return "Se muestra Fabricante con id: $id";
		// Buscamos un fabricante por el id.
		$administrador=Administrador::find($id);

		// Si no existe ese fabricante devolvemos un error.
		if (!$administrador)
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
			// En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un administrador con ese código.'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$administrador],200);
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
    public function update(Request $request, $id)
    {
        // Comprobamos si el administrador que nos están pasando existe o no.
        $administrador=Administrador::find($id);

        // Si no existe ese fabricante devolvemos un error.
        if (!$administrador)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
        }
        
        // Listado de campos recibidos teóricamente.
        $usuario=$request->input('usuario');
        $nombre=$request->input('nombre_completo');
        $correo=$request->input('correo');
        $contrasena=$request->input('contrasena');

        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        // El método de la petición se sabe a través de $request->method();
        if ($request->method() === 'PATCH')
        {
            // Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            // Actualización parcial de campos.
            if ($usuario)
            {
                $administrador->usuario = $usuario;
                $bandera=true;
            }

            if ($nombre)
            {
                $administrador->nombre_completo = $nombre;
                $bandera=true;
            }


            if ($correo)
            {
                $administrador->correo = $correo;
                $bandera=true;
            }
            if ($contrasena)
            {
                $fabricante->contrasena = $contrasena;
                $bandera=true;
            }

            if ($bandera)
            {
                // Almacenamos en la base de datos el registro.
                $administrador->save();
                return response()->json(['status'=>'ok','data'=>$administrador], 200);
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de Administrador.'])],304);
            }
        }


        // Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$usuario || !$nombre || !$correo || !$contrasena)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }

        $administrador->usuario = $usuario;
        $administrador->nombre_completo = $nombre;
        $administrador->correo = $correo;
        $administrador->contrasena = $contrasena;


        // Almacenamos en la base de datos el registro.
        $administrador->save();
        return response()->json(['status'=>'ok','data'=>$administrador], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Comprobamos si el administrador que nos están pasando existe o no.
        $administrador=Administrador::find($id);

        // Si no existe ese fabricante devolvemos un error.
        if (!$administrador)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un administrador con ese código.'])],404);
        }

         //El fabricante existe entonces buscamos todos los aviones asociados a ese fabricante.
         // Sin paréntesis obtenemos el array de todos los aviones.
         
            $empleados = Empleado::where('adm_id', $id)->delete();
         
        
         // Comprobamos si tiene aviones ese fabricante.
        
 
         // Procedemos por lo tanto a eliminar el fabricante.
         $administrador->delete();
    }
}
