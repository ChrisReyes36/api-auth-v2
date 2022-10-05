<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $data = $request->all();
        // Validate the data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }
        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        // Create the token
        $token = $user->createToken('authToken')->accessToken;
        // Return the response
        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado correctamente',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $data = $request->all();
        // Validate the data
        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }
        // Check the user
        if (!auth()->attempt($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
            ], 401);
        }
        // Create the token
        $token = auth()->user()->createToken('authToken')->accessToken;
        // Return the response
        return response()->json([
            'success' => true,
            'message' => 'Usuario logeado correctamente',
            'user' => auth()->user(),
            'token' => $token
        ], 200);
    }

    // Logout
    public function logout(Request $request)
    {
        // Revoke the token
        $request->user()->token()->revoke();
        // Return the response
        return response()->json([
            'success' => true,
            'message' => 'Usuario deslogeado correctamente',
        ], 200);
    }
}
