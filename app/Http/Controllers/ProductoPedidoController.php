<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comanda;
use App\Producto;
use App\ProductoPedido;



use Illuminate\Support\Facades\Cache;

class ProductoPedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productopedidos=Cache::remember('cacheproductopedido',15/60,function()
		{
			
			return ProductoPedido::simplePaginate(20);  // Paginamos cada 10 elementos.

		});

		
		return response()->json(['status'=>'ok', 'siguiente'=>$productopedidos->nextPageUrl(),'anterior'=>$productopedidos->previousPageUrl(),'data'=>$productopedidos->items()],200);

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
        
        if ( !$request->input('id_producto') || !$request->input('id_comanda') || !$request->input('cantidad'))
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
		}

		$id_producto=Producto::find($request->input('id_producto'));
        $id_comanda=Comanda::find($request->input('id_comanda'));

        if (!$id_producto)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra el producto con ese código.'])],404);
        }
        if (!$id_comanda)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una comanda con ese código.'])],404);
        }
        
		$nuevoPedido=ProductoPedido::create($request->all());


        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        return response()->json(['data'=>$nuevoPedido], 201)->header('Location',  url('/api').'/productopedidos/'.$nuevoPedido->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productopedidos=ProductoPedido::find($id);

		if (!$productopedidos)
		{
			
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Pedido con ese codigo.'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$productopedidos],200);
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
        $productoPedido=ProductoPedido::find($id);

        
        if (!$productoPedido)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un productoPedido con ese código.'])],404);
        }
        
        // Listado de campos recibidos teóricamente.
        $id_comanda=$request->input('id_comanda');
        $id_producto=$request->input('id_producto');
        $cantidad=$request->input('cantidad');
        

        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        // El método de la petición se sabe a través de $request->method();
        if ($request->method() === 'PATCH')
        {
            // Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            // Actualización parcial de campos.
            if ($id_comanda)
            {
                $productoPedido->id_comanda = $id_comanda;
                $bandera=true;
            }
            if ($id_producto)
            {
                $productoPedido->id_producto = $id_producto;
                $bandera=true;
            }
            if ($cantidad)
            {
                $productoPedido->cantidad = $cantidad;
                $bandera=true;
            }
            

            if ($bandera)
            {
                // Almacenamos en la base de datos el registro.
                $productoPedido->save();
                return response()->json(['status'=>'ok','data'=>$productoPedido], 200);
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato del productoPedido.'])],304);
            }
        }


        // Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$id_comanda || !$id_producto || !$cantidad )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }

        $productoPedido->id_comanda = $id_comanda;
        $productoPedido->id_producto = $id_producto;
        $productoPedido->cantidad = $cantidad;
        


        // Almacenamos en la base de datos el registro.
        $productoPedido->save();
        return response()->json(['status'=>'ok','data'=>$productoPedido], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $productoPedido=ProductoPedido::find($id);

       // Si no existe ese fabricante devolvemos un error.
       if (!$productoPedido)
       {
           // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
           // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
           return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un pedido con ese código.'])],404);
       }

        $productoPedido->delete();
    }
}
