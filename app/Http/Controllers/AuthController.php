<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use APIResponse;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->response(null, $validator->errors(), 422);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $this->response("Successfully registered.", $request->all(), 201);
        } catch (\Exception $e) {
            return $this->response("Registration failed.", $e, 409);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->response(null, $validator->errors(), 422);
        }
        
        $credentials = $request->only('email', 'password');
        
        if (!$token = Auth::attempt($credentials)) {
            return $this->response("Unauthorized.", null, 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->response("Login successfully.", ['user' => $user, 'token' => $token], 201);
    }

    public function logout()
    {
        Auth::logout();

        return $this->response("Successfully logout.", null, 201, true);
    }
}
