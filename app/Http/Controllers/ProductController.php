<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::all();

            return response()->json( [
                'error' => false, 
                'message' => 'List of all products', 
                'data' => $products
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
            'sku' => 'required|unique:products',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $data = [
                "merchant_id" => $request->merchant_id,
                "sku" => $request->sku,
                "name" => $request->name,
            ];
            if($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $file->storeAs('public', $filename);
                $data['image'] = storage_path('app/public/'.$filename);
            }
            $newProduct = Product::create($data);

            return response()->json( [
                'error' => false, 
                'message' => 'Add new product successfully', 
                'data' => $newProduct
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
            $product = Product::find($id);

            if($product) {
                return response()->json( [
                    'error' => false, 
                    'message' => 'Detail of product', 
                    'data' => $product
                ], 202);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Detail of product not found', 
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
            'merchant_id' => 'required',
            'sku' => 'required|unique:products',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $product = Product::find($id);

            if($product) {
                $data = [
                    "merchant_id" => $request->merchant_id,
                    "sku" => $request->sku,
                    "name" => $request->name,
                ];
                if($request->hasFile('image')) {
                    $file = $request->file('image');
                    $filename = $file->getClientOriginalName();
                    $file->storeAs('public', $filename);
                    $data['image'] = storage_path('app/public/'.$filename);
                }
                $productUpdated = $product->update($data);

                return response()->json( [
                    'error' => false, 
                    'message' => 'Product updated successfully', 
                    'data' => $productUpdated
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Product not found', 
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
        $product = Product::find($id);

        try {

            if($product) {
                $product->delete();

                return response()->json( [
                    'error' => false, 
                    'message' => 'Product deleted successfully', 
                    'data' => $product
                ], 200);
            }

            return response()->json( [
                'error' => true, 
                'message' => 'Product not found', 
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
