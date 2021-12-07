<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function getImage($imagePath)
    {

        $image = \Storage::disk('images')->get($imagePath);

        return response($image, 200)->header('Content-Type', 'image/jpeg');

    }

    public function getImageSubdirectory($subfolder, $image)
    {

        $imagePath = $subfolder . '/' . $image;

        $image = \Storage::disk('images')->get($imagePath);

        return response($image, 200)->header('Content-Type', 'image/jpeg');

    }

}

