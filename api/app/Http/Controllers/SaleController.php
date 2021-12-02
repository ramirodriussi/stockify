<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Transformers\SaleTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Mail\StockNotification;
use Illuminate\Support\Facades\Mail;


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
        $stockArray = [];
		$ids = [];

        foreach ($request->cart as $value) {

            $ids[] = $value['id'];

            $arr[$value['id']] = [

                    'product' => $value['product'],
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],

            ];

            $stockArray[] = [
                'id' => $value['id'],
                'stock' => ['-', $value['quantity']]
            ];

        }

        $sale->product()->attach($arr);

        // stock decrement in product table

        $product = new Product;
        $index = 'id';

        \Batch::update($product, $stockArray, $index);

        // chequeo de stock

		$products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {

            if($product->stock < $product->stock_notification_below){

                Mail::to(env('APP_EMAIL'))->send(new StockNotification($product));

            }

        }

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

        if($request->input == 'quantity'){

            // descuento o sumo la diferencia en stock dependiendo si se agrega o resta cantidad del producto

            $product = Product::find($request->id);

            $request->operator == '+' ? $product->increment('stock', $request->diff) : $product->decrement('stock', $request->diff);

            // actualizo tabla pivot con cantidades

            $sale->product()->updateExistingPivot($request->id, $arr);

        } else {

            $sale->payment_type = $request->value;
            
            $sale->save();

        }

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
        // dd($request->all());
        if($request->id){

            // elimino item de una venta realizada

            $sale = Sale::find($id);

            $sale->product()->detach($request->id);

            // reintegro stock al producto

            $product = Product::find($request->id);

            $product->increment('stock', $request->quantity);

            return response()->json(['message' => 'Item eliminado correctamente'], 200);

        }

        // eliminio una venta completa

        $sale = Sale::find($id);

        // reintegro stock de los productos eliminados

        if($request->reintegrate == 'true'){

            $product = new Product;

            $arr = [];

            foreach ($sale->product()->get() as $key => $item) {

                $arr[] = [
                    'id' => $item->pivot->product_id,
                    'stock' => ['+', $item->pivot->quantity],
                ];

            }

            $index = 'id';

            \Batch::update($product,$arr,$index);

        }

        $sale->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);

    }

}
