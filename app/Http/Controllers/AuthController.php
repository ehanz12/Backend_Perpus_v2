<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            if(!Auth::guard('web')->attempt($request->only('email', 'password'))) {
                return response()->json([
                    "message" => "invalid login details",
                    "data" => null
                ], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;
            return response()->json([
                "message" => "login successfuly",
                "data" => [
                    "token" => $token,
                    "user" => $user
                ],
            ], 200);

        } catch (\Exception $th) {
            return response()->json(["message" => "error login invalid", "error" => $th->getMessage()], 500);
        }
    }


    public function register(RegisterRequest $request)
    {   
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = new User();
            $user->firstname = $data['firstname'];
            $user->lastname = $data['lastname'];
            $user->address = $data['address'];
            $user->email = $data['email'];
            $user->no_phone = $data['no_phone'];
            $user->password = Hash::make($data['password']);

            $user->save();

            $latest = $user->id;

            $role = new Role();
            $role->user_id = $latest;
            $role->role = "user";
            $role->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                "message" => "successfully created user",
                "data" => [
                    "token" => $token,
                    "users" => $user,
                    "role" => $role->role
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "User created Failed",
                "data" => null,
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function me()
    {
        try {
            $user = Auth::user();
            return response()->json([
                "message" => "User this Found",
                "data" => $user
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error while the detail user",
                "error" => $th->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->currentAccessToken()->delete();

            return response()->json([
                "message" => "logouted successfully",
                "data" => null
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error logout failed",
                "error" => $th->getMessage(),
                "data" => null
            ], 500);
        }
    }
}
