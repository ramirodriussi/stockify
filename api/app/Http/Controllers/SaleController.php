<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Transformers\SaleTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

		$manager = new Manager();

		$manager->setSerializer(new ArraySerializer());

        $sales = Sale::search($request->word)->orderBy('id', 'desc')->paginate(1);

		$app = $sales->getCollection();

		$resource = new Collection($app, new SaleTransformer());	

		$resource->setPaginator(new IlluminatePaginatorAdapter($sales));

		return $manager->createData($resource)->toArray();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $arr = $request->all();

        // $cart = collect(json_decode($request->cart, true));

        // dd($cart);

        \Validator::make($request->all(), [
            'cart' => 'required',
            'payment' => 'required',
        ])->validate();

        $sale = new Sale();

        $sale->sale_id = hexdec(uniqid());
        $sale->payment_type = $request->payment;

        $sale->save();

        // attach product to product_sale table

        $arr = [];

        foreach ($request->cart as $value) {

            $arr[$value['id']] = [

                    'product' => $value['product'],
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],

            ];

        }

        $sale->product()->attach($arr);

        // dd($arr);

        // $products = [];



        // $sale->product()->attach($sale->id,)
        
        // $a = array_merge($arr, $sale_id);

        // dd($a);

        // $sale->create($a);

        return response()->json(['message' => 'Agregado correctamente'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $manager = new Manager();

        $manager->setSerializer(new ArraySerializer());

        $sale = Sale::find($id);

        $resource = new Item($sale, new SaleTransformer());

        return $manager->createData($resource)->toArray();

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

        $arr = [$request->input => $request->value];

        \Validator::make($arr, [
            'quantity' => 'numeric',
        ])->validate();

        $sale = Sale::find($id);

        $sale->product()->updateExistingPivot($request->id, $arr);

        return response()->json(['message' => 'Actualizado correctamente'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        if($request->id){

            // elimino item de una venta realizada

            // !!!!!!!!!!!!!!!!!!!!-------------------*************************

            // tengo que buscar la cantidad que se vendio, y al eliminar el item, reintegrar el stock al inventario general del producto.

            $sale = Sale::find($id);

            $sale->product()->detach($request->id);

            return response()->json(['message' => 'Item eliminado correctamente'], 200);

        }
        
        // eliminio una venta completa

        // tengo que buscar la cantidad de cada producto que se vendio, y reintegrar al inventario genral del producto

        $sale = Sale::find($id);

        $sale->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);

    }

}
