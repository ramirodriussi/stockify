<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Transformers\StoreTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class StoreController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $manager = new Manager();

        $manager->setSerializer(new ArraySerializer());

        if($request->all){

            $stores = Store::orderBy('id','desc')->get();
    
            $resource = new Collection($stores, new StoreTransformer());	
       
        } else {

            $stores = Store::search($request->word)->orderBy('id','desc')->paginate(1);

            $app = $stores->getCollection();
    
            $resource = new Collection($stores, new StoreTransformer());	
    
            $resource->setPaginator(new IlluminatePaginatorAdapter($stores));

        }
       
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
        
        $manager = new Manager();

        $manager->setSerializer(new ArraySerializer());

        $store = Store::find($id);

        $resource = new Item($store, new StoreTransformer());

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
            'store' => 'required',
        ])->validate();

        $store = Store::find($id);

        $store->update($arr);

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
