<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Image;
use App\Traits\Images;
use App\Transformers\UserTransformer;
use App\Notifications\ConfirmEmail;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use Illuminate\Support\Carbon;

class ProfileController extends Controller
{

    use Images;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $user = User::find($request->user()->id);

        $manager = new Manager();
            
        $manager->setSerializer(new ArraySerializer());

        // Make a resource out of the data and
        $resource = new Item($user, new UserTransformer(), 'user');

        // Run all transformers
        return $manager->createData($resource)->toArray();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $input = $request->input('input');

        $user = User::find($request->user()->id);

        $user->$input = $request->input('value');

        if ($input === 'email') {
            $user->email_verified_at = null;
            // $user->notify(new ConfirmEmail(false));
        }

        $user->save();

        $user->sendEmailVerificationNotification();

    }

    public function passwordUpdate(Request $request)
    {

        // current pass

        $currentPass = $request->input('currentPass');

        $currentPassHashed = Hash::make($currentPass);

        $user = User::select(['id','password'])->where('id', $request->user()->id)->first();

        if (!Hash::check($currentPass, $user->password)) {
            return response()->json(['error'=>'La contraseña actual es incorrecta.'], 403);
        }

        // update pass

        $newPass = Hash::make($request->input('newPass'));

        $user->password = $newPass;

        $user->save();

        return response()->json(['success'=>'La contraseña fue modificada correctamente.'], 200);


    }

	public function updateLogo(Request $request)
	{

		$file = $request->file('file');

		$user = User::where('id', $request->user()->id)->first();

		$image = Image::query();

		$logo = $image->where('logo', 1)->where('user_id', $user->id)->first();

		// si hay imagen la elimino antes de subir la nueva

		if ($logo) {

			$img = explode('/', $logo->image);

			$this->delete('/logos/', $img[3]);

			$logo->delete();

		}		

		$folder = 'logos';

		$imgName = $this->storeImg(['file' => $file], $folder);

		if (isset($imgName->original['error'])) {
			return response()->json(['error'=>$imgName->original['error']], 400);
		}

		$imgPath = '/imagen/logos/' . $imgName;

		$image->insert(

			[
				'image' => $imgPath,
				'logo' => 1,
				'user_id' => $user->id,
				'created_at' => \Carbon\Carbon::now(),

			]

		);

		return response()->json(['message'=>'Imagen subida correctamente'], 200);
		
	}

}
