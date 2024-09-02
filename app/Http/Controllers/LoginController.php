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
            $success['token'] =  $user->createToken('authToken')->plainTextToken; 

            return ResponseFormat::success($success, 'Your login is successful.', 200);
        } else { 
            return ResponseFormat::error(null,'Please re-check your email & password', 400);
        }
    }



    // public function login(LoginRequest $request)
    // {
    //     try {
    //         $user = User::where('email', $request->validated('email'))->firstOrFail();

    //         if (!Auth::attempt($request->validated())) {
    //             throw new AuthenticationException('Invalid credentials');
    //         }

    //         return LoginResource::make($user);
    //     } catch (AuthenticationException $e) {
    //         return response()->json([
    //             'status'  => Response::HTTP_UNAUTHORIZED,
    //             'message' => $e->getMessage(),
    //         ], Response::HTTP_UNAUTHORIZED);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'status'  => Response::HTTP_NOT_FOUND,
    //             'message' => 'Model not found.',
    //         ], Response::HTTP_NOT_FOUND);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'message' => 'Internal server error.',
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
}
