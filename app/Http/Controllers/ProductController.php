<?php

namespace App\Http\Controllers;

use App\Seller;
use App\Product;

class ProductController extends Controller
{
    public function store()
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
                $filename='images/' . rand() . '.' . $extension;
                $file->move(public_path("images"), $filename);
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
}
