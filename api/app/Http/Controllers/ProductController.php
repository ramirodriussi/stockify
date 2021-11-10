<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Transformers\ProductTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ProductController extends Controller
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

        $products = Product::search($request->word)->store($request->store)->orderBy('id', 'desc')->paginate(1);

		$app = $products->getCollection();

		$resource = new Collection($app, new ProductTransformer());	

		$resource->setPaginator(new IlluminatePaginatorAdapter($products));

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

        \Validator::make($request->form, [
            'product' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'stock_notification_below' => 'required|integer',
            'store_id' => 'required',
            'code' => 'required',
        ])->validate();

        $product = new Product();

        $product->create($request->form);

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

        $product = Product::find($id);

        $resource = new Item($product, new ProductTransformer());

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
            'product' => 'string',
            'price' => 'numeric',
            'stock' => 'integer',
            'stock_notification_below' => 'integer',
        ])->validate();

        $product = Product::find($id);

        $product->update($arr);

        return response()->json(['message' => 'Actualizado correctamente'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product = Product::find($id);

        $product->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);

    }
}
