<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Mesa;

use Illuminate\Support\Facades\Cache;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes=Cache::remember('cacheccliente',15/60,function()
		{
			
			return Cliente::simplePaginate(20);  // Paginamos cada 10 elementos.

		});

		
		return response()->json(['status'=>'ok', 'siguiente'=>$clientes->nextPageUrl(),'anterior'=>$clientes->previousPageUrl(),'data'=>$clientes->items()],200);

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
        

        if ( !$request->input('id_mesa'))
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
		}

		$mesa=Mesa::find($request->input('id_mesa'));

        if (!$mesa)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una mesa con ese código.'])],404);
        }
		$nuevoCliente=Cliente::create($request->all());


        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        return response()->json(['data'=>$nuevoCliente], 201)->header('Location',  url('/api').'/mesa/'.$nuevoCliente->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente=Cliente::find($id);

		if (!$cliente)
		{
			
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra cliente con ese codigo.'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$cliente],200);
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
        $cliente=Cliente::find($id);

        
        if (!$cliente)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un cliente con ese código.'])],404);
        }
        
        // Listado de campos recibidos teóricamente.
        $id_mesa=$request->input('id_mesa');

        
        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        // El método de la petición se sabe a través de $request->method();
        if ($request->method() === 'PATCH')
        {
            // Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            // Actualización parcial de campos.
            if ($id_mesa)
            {
                $cliente->id_mesa = $id_mesa;
                $bandera=true;
            }
            

            if ($bandera)
            {
                // Almacenamos en la base de datos el registro.
                $mesa->save();
                return response()->json(['status'=>'ok','data'=>$mesa], 200);
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de Mesas.'])],304);
            }
        }


        // Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$id_mesa)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }

        $cliente->id_mesa = $id_mesa;
        


        // Almacenamos en la base de datos el registro.
        $cliente->save();
        return response()->json(['status'=>'ok','data'=>$cliente], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $cliente=Cliente::find($id);

       // Si no existe ese fabricante devolvemos un error.
       if (!$cliente)
       {
           // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
           // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
           return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un cliente con ese código.'])],404);
       }

       
        
           //Añadir borrar todas las comandas $clientes = Empleado::where('adm_id', $id)->delete();
        
        $cliente->delete();
    }
}
