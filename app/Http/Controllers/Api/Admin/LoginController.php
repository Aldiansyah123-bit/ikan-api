<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'     => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],422);
        }

        $credentials = $request->only('email','password');

        if (!$token = auth()->guard('api_admin')->attempt($credentials)) {
            return response()->json([
                'success'   => false,
                'message'   => 'email and password incorret'
            ],401);
        }

        return response()->json([
            'success'   => true,
            'user'      => auth()->guard('api_admin')->user(),
            'token'     => $token,
        ], 200);
    }


    public function getUser()
    {
        return response()->json([
            'success'   => true,
            'user'      => auth()->guard('api_admin')->user()
        ], 200);
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

        $user = JWTAuth::setToken($refreshToken)->toUser();

        $request->headers->set('Authorization','Bearer '.$refreshToken);

        //response data "user" dengan "token" baru
        return response()->json([
            'success' => true,
            'user'    => $user,
            'token'   => $refreshToken,
        ], 200);
    }

    public function logout()
    {
        //remove "token" JWT
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        //response "success" logout
        return response()->json([
            'success' => true,
        ], 200);

    }
}
