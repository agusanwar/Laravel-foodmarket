<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //Create Data API
     public function create(Request $request)
    {
    try {
        //request validasi
        $request->validate([
            'name_product' => ['required','string','max:255'],
            'harga' => ['required','integer'],
            'description' => ['required','string','max:255'],
        ]);

        Product::create([
            'name_product' => $request->name_product,
            'harga' => $request->harga,
            'description' => $request->description,
        ]);

        //untuk mengambil data didatabase user
            $product = Product::where('name_product', $request->name_product)->first();
            
            //mengembalika token beserta data user
            return ResponseFormatter::success([
                'product' => $product,
            ]);
          //untuk mengecek eroor dan mengmablikan
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authenticated Failed', 500);
        }
    }

    //GetData API
    public function show(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name_product = $request->input('name_product');
        $harga = $request->input('harga');
        $description = $request->input('description');

        //pengambailan data menurut id
        if($id)
        {
            $product = Product::find($id);

            if($product)
            {
                return ResponseFormatter::success(
                    $product,
                    'Data  product berhasil diambil'
                );
            }
            else
            {
                return ResponseFormatter::error(
                    null,
                    'Data product tidak ada',
                    401
                );
            }
        }
        
        $product = Product::query();

        if($name_product)
        {
            $product->where('name_product', 'like', '%' . $name_product . '%');
            // $product->where('name_product',  $name_product );
        }
        if($harga)
        {
            $product->where('harga', 'like', '%' . $harga . '%');
            //  $product->where('harga',  $harga );
        }
        if($description) 
        {
            $product->where('description', 'like', '%' . $description .'%');
            //  $product->where('description',  $description );
        }
        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data list product berhasil diambil');
    }

    //Update Data API
    public function update(Request $request)
    {
        $product = Product::findOrFail($id);

        $product->update($request->all());
        return ResponseFormatter::success($product, 'Product berhasil diperbaharui');
    }

    //Delete Data API
    public function delete(Product $product)
    {
        $product->delete();
        return response()->json([
        "success" => true,
        "message" => "Product deleted successfully.",
        "data" => $product
        ]);
    }
}
