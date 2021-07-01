<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
        $this->expired = env('JWT_TTL');
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request) {
        
        $this->validate($request, [
            'role_id' => 'required',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        try {
            $data = [
                "role_id" => $request->role_id,
                "username" => $request->username,
                "email" => $request->email,
                "password" => app('hash')->make($request->password),
                "password_confirmation" => $request->password_confirmation,
            ];
            $newUser = User::create($data);

            return response()->json( [
                'error' => false, 
                'message' => 'Register successfully', 
                'data' => $newUser
            ], 201);

        } catch (\Exception $e) {
            return response()->json( [
                'error' => true, 
                'message' => 'Something wrong on the server, please contact us.', 
                'data' => null
            ], 500);
        }
    }
	
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */	 
    public function login(Request $request) {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['username', 'password']);

        if (!$token = auth()->setTTL($this->expired)->attempt($credentials)) {			
            return response()->json( [
                'error' => true, 
                'message' => 'Invalid username or password', 
                'data' => null
            ], 401);
        }
        return $this->respondWithToken($token);
    }
	
     /**
     * Get user details.
     *
     * @param  Request  $request
     * @return Response
     */	 	
    public function profile() {
        return response()->json(auth()->user());
    }
}
