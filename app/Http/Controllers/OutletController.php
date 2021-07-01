<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $outlets = Outlet::with('hasMerchant')->get();

            return response()->json( [
                'error' => false, 
                'message' => 'List of all outlets', 
                'data' => $outlets
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'merchant_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required|min:5',
            'phone' => 'required',
        ]);

        try {
            // create new user for outlet
            $userdata = [
                "role_id" => 3, // role for outlet
                "username" => str_before($request->email, '@'),
                "email" => $request->email,
                "password" => app('hash')->make('majoo-' . $request->phone), // default password for outlet will be majoo-phonenumber
                "password_confirmation" => 'majoo-' . $request->phone,
            ];
            $newUser = User::create($userdata);

            // create new outlet
            $data = [
                "user_id" => $newUser->id,
                "merchant_id" => $request->merchant_id,
                "name" => $request->name,
                "address" => $request->address,
                "phone" => $request->phone,
            ];
            $newOutlet = Outlet::create($data);

            return response()->json( [
                'error' => false, 
                'message' => 'Add new outlet successfully', 
                'data' => $newOutlet
            ], 201);

        } catch (\Exception $e) {
            return response()->json( [
                'error' => true, 
                'message' => $e, 
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
    public function show($id)
    {
        try {
            $outlet = Outlet::find($id);

            if($outlet) {
                return response()->json( [
                    'error' => false, 
                    'message' => 'Detail of outlet', 
                    'data' => $outlet
                ], 202);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Detail of outlet not found', 
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required|min:5',
            'phone' => 'required',
        ]);

        try {
            $outlet = Outlet::find($id);

            if($outlet) {
                $data = [
                    "name" => $request->name,
                    "address" => $request->address,
                    "phone" => $request->phone,
                ];
                $outletUpdated = $outlet->update($data);

                return response()->json( [
                    'error' => false, 
                    'message' => 'Outlet updated successfully', 
                    'data' => $outletUpdated
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Outlet not found', 
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
        $outlet = Outlet::find($id); // find outlet
        $user = User::find($outlet->user_id); // find user by outlet

        try {

            if($user) {
                $user->delete(); // this will delete user and outlet because ondelete is cascade

                return response()->json( [
                    'error' => false, 
                    'message' => 'Outlet deleted successfully', 
                    'data' => $outlet
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Outlet not found', 
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
