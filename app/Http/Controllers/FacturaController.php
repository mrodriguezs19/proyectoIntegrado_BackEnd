<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factura;
use App\Cliente;

use Illuminate\Support\Facades\Cache;

class FacturaController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth.basic');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facturas=Cache::remember('cachefactura',15/60,function()
		{
			
			return Factura::all();  // Paginamos cada 10 elementos.

		});

		
		return response()->json(['status'=>'ok','data'=>$facturas],200);
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
        
        if ( !$request->input('id_cliente') || !$request->input('cuenta') || !$request->input('pagado'))
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
		}

		$cliente=Cliente::find($request->input('id_cliente'));

        // Si no existe el fabricante que le hemos pasado mostramos otro código de error de no encontrado.
        if (!$cliente)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un cliente con ese código.'])],404);
        }
		$nuevaFactura=Factura::create($request->all());


        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        return response()->json($nuevaFactura);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $factura=Factura::find($id);

		if (!$factura)
		{
			
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra factura con ese codigo.'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$factura],200);
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
        $factura=Factura::find($id);

        
        if (!$factura)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una factura con ese código.'])],404);
        }
        
        // Listado de campos recibidos teóricamente.
        $id_cliente=$request->input('id_cliente');
        $cuenta=$request->input('cuenta');
        $pagado=$request->input('pagado');
        

        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        // El método de la petición se sabe a través de $request->method();
        if ($request->method() === 'PATCH')
        {
            // Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            // Actualización parcial de campos.
            if ($id_cliente)
            {
                $factura->id_cliente = $id_cliente;
                $bandera=true;
            }
            if ($cuenta)
            {
                $factura->cuenta = $cuenta;
                $bandera=true;
            }
            if ($pagado)
            {
                $factura->pagado = $pagado;
                $bandera=true;
            }
            

            if ($bandera)
            {
                // Almacenamos en la base de datos el registro.
                $factura->save();
                return response()->json(['status'=>'ok','data'=>$factura], 200);
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de factura.'])],304);
            }
        }


        // Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$id_cliente || !$cuenta || !$pagado )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }

        $factura->id_cliente = $id_cliente;
        $factura->cuenta = $cuenta;
        $factura->pagado = $pagado;
        


        // Almacenamos en la base de datos el registro.
        $factura->save();
        return response()->json(['status'=>'ok','data'=>$factura], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $factura=Factura::find($id);

       // Si no existe ese fabricante devolvemos un error.
       if (!$factura)
       {
           // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
           // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
           return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una factura con ese código.'])],404);
       }

        $factura->delete();
    }
}
