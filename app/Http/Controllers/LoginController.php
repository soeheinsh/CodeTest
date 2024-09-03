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

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            $success['email'] = $user->email;
            $success['token'] =  $user->createToken('authToken')->plainTextToken; 

            return response()->json([
                            'status'  => 'success',
                            'message' => 'Your login successful.',
                            'data' => $success,
                        ], Response::HTTP_OK);
        } else { 
            return response()->json([
                'status'  => 'error',
                'message' => 'Please recheck your credentials.',
            ], 401);
        }
    }
}
