<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $merchants = Merchant::with('hasOutlets')->get();

            return response()->json( [
                'error' => false, 
                'message' => 'List of all merchants', 
                'data' => $merchants
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
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required|min:5',
            'phone' => 'required',
        ]);

        try {
            // create new user for merchant
            $userdata = [
                "role_id" => 2, // role for merchant
                "username" => str_before($request->email, '@'),
                "email" => $request->email,
                "password" => app('hash')->make('majoo-' . $request->phone), // default password for merchant will be majoo-phonenumber
                "password_confirmation" => 'majoo-' . $request->phone,
            ];
            $newUser = User::create($userdata);

            // create new merchant
            $data = [
                "user_id" => $newUser->id,
                "name" => $request->name,
                "address" => $request->address,
                "phone" => $request->phone,
            ];
            $newMerchant = Merchant::create($data);

            return response()->json( [
                'error' => false, 
                'message' => 'Add new merchant successfully', 
                'data' => $newMerchant
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
    public function show($id)
    {
        try {
            $merchant = Merchant::find($id);

            if($merchant) {
                return response()->json( [
                    'error' => false, 
                    'message' => 'Detail of merchant', 
                    'data' => $merchant
                ], 202);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Detail of merchant not found', 
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
            $merchant = Merchant::find($id);

            if($merchant) {
                $data = [
                    "name" => $request->name,
                    "address" => $request->address,
                    "phone" => $request->phone,
                ];
                $merchantUpdated = $merchant->update($data);

                return response()->json( [
                    'error' => false, 
                    'message' => 'Merchant updated successfully', 
                    'data' => $merchantUpdated
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Merchant not found', 
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
        $merchant = Merchant::find($id); // find merchant
        $user = User::find($merchant->user_id); // find user by merchant

        try {

            if($user) {
                $user->delete(); // this will delete user and merchant because ondelete is cascade

                return response()->json( [
                    'error' => false, 
                    'message' => 'Merchant deleted successfully', 
                    'data' => $merchant
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Merchant not found', 
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
