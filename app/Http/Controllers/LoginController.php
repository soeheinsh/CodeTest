<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use App\ResponseFormat;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email', 'min:6'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if($validator->fails()){
            return ResponseFormat::error($validator->errors(), "Fields validation error", 400);
        }

        if (Auth::guard('ctj-api')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('ctj-api')->user();
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            $success['token'] =  $user->createToken('authToken')->plainTextToken; 

            return ResponseFormat::success($success, 'Your login is successful.', 200);
        } else { 
            return ResponseFormat::error(null,'Please re-check your email & password', 400);
        }
    }
}
