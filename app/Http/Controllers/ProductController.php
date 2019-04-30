<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seller;
use App\Product;
use App\Http\Requests\ShoppingRequest;

class ProductController extends Controller
{
    public function store(ShoppingRequest $request)
    {
        $token=request()->get('api_token');
        if($s=Seller::where('api_token', $token)->first()){
            $product=new Product();
            $product->seller_id=$s->id;
            $product->name=request('name');
            $product->describe=request('describe');
            $product->price=request('price');
            $product->quantity=request('quantity');
            if(request()->hasfile('image')) {
                $file=request()->file('image');
                $extension=$file->getClientOriginalExtension();
                $filename='storage/' . rand() . '.' . $extension;
                $file->move(public_path("storage"), $filename);
                $product->image=$filename;
            }
            $product->save();
            return $product;
        }
    }

    public function index()
    {
        $token=request()->get('api_token');
        if($seller=Seller::where('api_token', $token)->first()){
            $product=Product::where('seller_id', $seller->id)->get();
            return $product;
        }
    }

    public function show()
    {
        $token=request()->get('api_token');
        if($seller=Seller::where('api_token', $token)->first()){
            $name=request()->get('name');
            $product=Product::where('seller_id', $seller->id)->where('name', 'LIKE', "%$name%")->get();
            return $product;
        }
    }

    public function update(Request $request, Product $product)
    {
        request()->validate([
            'name'=>['required', 'max:20'],
            'describe'=>['required', 'max:255'],
            'price'=>['required', 'max:10', 'integer'],
            'quantity'=>['required', 'max:10', 'integer'],
            'image'=>['required', 'image']
        ]);
        $token=request()->get('api_token');
        if($seller=Seller::where('api_token', $token)->first()){
            if($product->seller_id==$seller->id){
                $product->update(request()->except('api_token'));
                return $product;
            }
        }
    }

    public function destroy(Product $product)
    {
        $token=request()->get('api_token');
        if($seller=Seller::where('api_token', $token)->first()){
            if($product->seller_id==$seller->id){
                $product->delete();
                return 'ok';
            }
        }
    }
}
