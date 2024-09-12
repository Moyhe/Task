<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' =>  ['required', 'string', 'unique:users', 'max:255', 'email'],
            'password' => ['required', Password::defaults()->min(8), 'confirmed']
        ]);

        if ($validator->fails()) {

            return Response::json($validator->errors(), 400);
        }

        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'role' => 'admin',
            'password' => Hash::make(request('password')),
        ]);


        $user = User::query()->firstOrFail();
        $token = JWTAuth::fromUser($user);


        return Response::json(compact('user', 'token'), 200);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' =>  ['required', 'string', 'max:255', 'email'],
            'password' => ['required', Password::defaults()->min(8)]

        ]);

        if ($validator->fails()) {

            return Response::json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                return Response::json(['error' => 'invalid user name and password'], 401);
            }
        } catch (JWTException $e) {

            return Response::json(['error' => 'could not create token'], 500);
        }

        return response()->json(compact('token'), 200);
    }
}
