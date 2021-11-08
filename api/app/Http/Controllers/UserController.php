<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function show(Request $request)
    {

        $arr = [
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'logo' => (isset($request->user()->logo)) ? env('APP_URL') . $request->user()->logo->image : null,
        ];

        return response()->json(['data' => $arr], 200);

    }

}
