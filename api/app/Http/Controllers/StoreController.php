<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Transformers\StoreTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\Collection;
// use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class StoreController extends Controller
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

        $stores = Store::search($request->word)->paginate(1);

		$app = $stores->getCollection();

		$resource = new Collection($app, new StoreTransformer());	

		$resource->setPaginator(new IlluminatePaginatorAdapter($stores));

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
            'store' => 'required',
        ])->validate();

        $store = new Store();

        $store->store = $request->store;

        $store->save();

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
        
        $store = Store::find($id);

        return response()->json(['data' => $store], 200);

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
        
        \Validator::make($request->all(), [
            'store' => 'required',
        ])->validate();

        $store = Store::find($id);

        $store->update($request->all());

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
        
        $store = Store::find($id);

        $store->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);

    }
}
