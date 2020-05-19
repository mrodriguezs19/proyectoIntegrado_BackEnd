<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Administrador;

use Illuminate\Support\Facades\Cache;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos=Cache::remember('cacheproducto',15/60,function()
		{
			
			return Producto::simplePaginate(20);  // Paginamos cada 10 elementos.

		});

		
		return response()->json(['status'=>'ok', 'siguiente'=>$productos->nextPageUrl(),'anterior'=>$productos->previousPageUrl(),'data'=>$productos->items()],200);

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
        

        if ( !$request->input('nombre') || !$request->input('precio') || !$request->input('tipo')|| !$request->input('especialidad')|| !$request->input('id_adm'))
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
		}

		$administrador=Administrador::find($request->input('id_adm'));

        // Si no existe el fabricante que le hemos pasado mostramos otro código de error de no encontrado.
        if (!$administrador)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un administrador con ese código.'])],404);
        }
		$nuevoProducto=Producto::create($request->all());


        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        return response()->json(['data'=>$nuevoProducto], 201)->header('Location',  url('/api').'/productos/'.$nuevoProducto->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto=Producto::find($id);

		if (!$producto)
		{
			
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un producto con ese codigo.'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$producto],200);
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
        $producto=Producto::find($id);

        
        if (!$producto)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un producto con ese código.'])],404);
        }

        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $precio=$request->input('precio');
        $tipo=$request->input('tipo');
        $especialidad=$request->input('especialidad');
        $id_adm=$request->input('id_adm');

        

        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        // El método de la petición se sabe a través de $request->method();
        if ($request->method() === 'PATCH')
        {
            // Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            // Actualización parcial de campos.
            if ($nombre)
            {
                $producto->nombre = $nombre;
                $bandera=true;
            }
            if ($precio)
            {
                $producto->precio = $precio;
                $bandera=true;
            }   
            if ($tipo)
            {
                $producto->tipo = $tipo;
                $bandera=true;
            }         
            if ($especialidad)
            {
                $producto->especialidad = $especialidad;
                $bandera=true;
            }

            if ($bandera)
            {
                // Almacenamos en la base de datos el registro.
                $producto->save();
                return response()->json(['status'=>'ok','data'=>$producto], 200);
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de Producto.'])],304);
            }
        }


        // Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$nombre || !$precio || !$tipo || !$especialidad || !$id_adm)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }

        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->tipo = $tipo;
        $producto->especialidad = $especialidad;
        $producto->id_adm = $id_adm;




        // Almacenamos en la base de datos el registro.
        $producto->save();
        return response()->json(['status'=>'ok','data'=>$producto], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $producto=Producto::find($id);

       // Si no existe ese fabricante devolvemos un error.
       if (!$producto)
       {
           // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
           // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
           return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un empleado con ese código.'])],404);
       }        
        $producto->delete();
    }
}
