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
use App\Exports\ExportProducts;
use App\Imports\ImportProducts;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $store = ($request->store && $request->store != 'all') ? $request->store : null;

		$manager = new Manager();

		$manager->setSerializer(new ArraySerializer());

        $products = Product::search($request->word)->store($store)->orderBy('id', 'desc')->paginate(10);

		$app = $products->getCollection();

        $fields = ['id','product','code','stock','stock_notification_below','price','store'];

		$resource = new Collection($app, new ProductTransformer($fields));	

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

        $fields = ['id','product','code','stock','stock_notification_below','price','store'];

        $resource = new Item($product, new ProductTransformer($fields));

        return $manager->createData($resource)->toArray();

    }

    public function searchByCode($code)
    {

        $manager = new Manager();

        $manager->setSerializer(new ArraySerializer());

        $product = Product::where('code', $code)->first();

        if(!$product){
            return response()->json(['error' => 'El código ingresado no existe'], 400);
        }

        $fields = ['id','product','code','price','stock'];

        $resource = new Item($product, new ProductTransformer($fields));

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

    public function import(Request $request)
    {

        // $ext = $request->file('excel')->getClientOriginalExtension();

        // $name = 'products.' . $ext;

        // $path = $request->file('excel')->storeAs('files',$name);

        // \DB::table('files')->delete();

        // $file = new File();

        // $file->file = $name;
        // $file->path = $path;

        // $file->save();

        // Import

        // dd($request->all());

        $import = new ImportProducts;
        $import->import($request->file('file'));

        return response()->json(['message' => 'Subiendo archivo']);

        // Excel::queueImport(new ProductsImport, request()->file('excel'));

        // return back()->withStatus('uploading');

    }

    public function export()
    {
        return (new ExportProducts)->download('export.xlsx');
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
