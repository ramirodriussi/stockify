<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class UserController extends Controller
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

        $users = User::search($request->word)->orderBy('id','desc')->paginate(1);

        $app = $users->getCollection();

        $resource = new Collection($users, new UserTransformer());	

        $resource->setPaginator(new IlluminatePaginatorAdapter($users));

        // if($request->all){

        //     $stores = Store::orderBy('id','desc')->get();
    
        //     $resource = new Collection($stores, new StoreTransformer());	
       
        // } else {

        //     $stores = Store::search($request->word)->orderBy('id','desc')->paginate(1);

        //     $app = $stores->getCollection();
    
        //     $resource = new Collection($stores, new StoreTransformer());	
    
        //     $resource->setPaginator(new IlluminatePaginatorAdapter($stores));

        // }
       
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
            'form.name' => 'required',
            'form.email' => 'required|email',
            'form.role_id' => 'required',
            'form.password' => 'required|min:8',
            'form.access' => 'required'
        ])->validate();

        User::create([
            'name' => $request->form['name'],
            'email' => $request->form['email'],
            'password' => \Hash::make($request->form['password']),
            'role_id' => $request->form['role_id'],
            'access' => $request->form['access']
        ]);

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

        $user = User::find($id);

        $resource = new Item($user, new UserTransformer());

        return $manager->createData($resource)->toArray();

        // $arr = [
        //     'name' => $request->user()->name,
        //     'email' => $request->user()->email,
        //     'logo' => (isset($request->user()->logo)) ? env('APP_URL') . $request->user()->logo->image : null,
        // ];

        // return response()->json(['data' => $arr], 200);

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

        $arr = [$request->input => ($request->input == 'password') ? \Hash::make($request->value) : $request->value];

        \Validator::make($arr, [
            'email' => 'email',
            'password' => 'min:8',
        ])->validate();

        $user = User::find($id);

        $user->update($arr);

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

        $user = User::find($id);

        $user->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);

    }

}
