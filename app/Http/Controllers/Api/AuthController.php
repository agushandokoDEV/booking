<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;
    public function authenticate(Request $request)
    {
        $request->validate(
            [
                'username' => ['required'],
                'password' => ['required']
            ]
        );

        $auth = User::with(['roles'])->where('username', $request->username)->first();

        if (!$auth || !Hash::check($request->password, $auth->password))
        {
            return $this->unauthorizedResponse('Username & Password salah');
        }

        $data=$auth;
        $data['access_token']=$auth->createToken('booking')->plainTextToken;
        return $this->successResponse($data);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse(null);
    }

    public function refresh(Request $request)
    {
        $data=$request->user()->createToken('booking')->plainTextToken;
        return $this->successResponse($data);
    }

    public function me()
    {
        $data=Auth::user();
        $data['roles']=Auth::user()->roles;
        return $this->successResponse($data);
    }
}
