<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

trait Images {


    public function storeImg($request, $path = null)
    {

    	$validator = Validator::make($request, [
    		'file' => 'required|image|mimes:jpeg,bmp,png|max:3000',
    	]);

    	if ($validator->fails()) {
    		return response()->json(['error'=>$validator->errors()->first()]);
    	}

    	$store = 'images/' . $path;

    	$request['file']->store($store);

    	return $request['file']->hashName();

    }

    public function storeMultiple($request, $path = null)
    {

        $validator = Validator::make($request, [

            'file.*' => 'required|image|mimes:jpeg,bmp,png|max:3000',

        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->first()]);
        }

        $names = [];

        foreach ($request['file'] as $key => $file) {

            $random_str = bin2hex(random_bytes(10));

            $ext = $file->getClientOriginalExtension();

            $name = $random_str . '.' . $ext;

            $storage = Storage::disk('images')->path($path);

            $newPath = $storage . '/' . $name;

            \Image::make($file)->resize(800, 600, function($constraint){
                $constraint->aspectRatio();
            })->save($newPath);

            $names[] = $name;

        }

        return $names;

    }

    public function delete($path = null, $file, $id = null)
    {

        $imgPath = $path . $file;

        Storage::disk('images')->delete($imgPath);

    }


}