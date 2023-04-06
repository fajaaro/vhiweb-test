<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;

class AuthController extends Controller
{
    use ResponseTrait;
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->failure($validator->errors()->first(), 400);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return $this->failure('Wrong credentials.', 400);
        }

        return $this->success([
            'user' => auth()->guard('api')->user(),
            'token' => $token
        ]);
    }
}