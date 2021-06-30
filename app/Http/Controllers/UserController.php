<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $users = User::all();

            return response()->json( [
                'error' => false, 
                'message' => 'List of all users', 
                'data' => $users
            ], 202);

        } catch (\Exception $e) {
            return response()->json( [
                'error' => true, 
                'message' => 'Something wrong on the server, please contact us.', 
                'data' => null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'role_id' => 'required',
            'username' => 'required|unique:users',
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
                'message' => 'Add new user successfully', 
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $user = User::find($id);

            if($user) {
                return response()->json( [
                    'error' => false, 
                    'message' => 'Detail of user', 
                    'data' => $user
                ], 202);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Detail of user not found', 
                'data' => null
            ], 404);

        } catch (\Exception $e) {
            return response()->json( [
                'error' => true, 
                'message' => 'Something wrong on the server, please contact us.', 
                'data' => null
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'role_id' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        try {
            $user = User::find($id);

            if($user) {
                $data = [
                    "role_id" => $request->role_id,
                    "username" => $request->username,
                    "email" => $request->email,
                    "password" => app('hash')->make($request->password),
                    "password_confirmation" => $request->password_confirmation,
                ];
                $userUpdated = $user->update($data);

                return response()->json( [
                    'error' => false, 
                    'message' => 'User updated successfully', 
                    'data' => $userUpdated
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'User not found', 
                'data' => null
            ], 404);

        } catch (\Exception $e) {
            return response()->json( [
                'error' => true, 
                'message' => 'Something wrong on the server, please contact us.', 
                'data' => null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if($user) {
                $user->delete();

                return response()->json( [
                    'error' => false, 
                    'message' => 'User deleted successfully', 
                    'data' => $user
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'User not found', 
                'data' => null
            ], 404);

        } catch (\Exception $e) {
            return response()->json( [
                'error' => true, 
                'message' => 'Something wrong on the server, please contact us.', 
                'data' => null
            ], 500);
        }
    }
}
