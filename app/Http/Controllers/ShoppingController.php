<?php

namespace App\Http\Controllers;

use App\Seller;
use App\Product;
use App\Buyer;
use App\Shopping;
use App\Shoppinglist;
use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    public function store(){
        $total_price=0;
        $list=request()->get('list');
        $seller_id=request()->get('seller_id');
        foreach ($list as $productId => $bq) {
            $product=Product::where('id', $productId)->first();
            $sq=$product->quantity;
            $pn=$product->name;
            $pp=$product->price;
            $pi=$product->id;
            $si=$product->seller_id;
            if ($sq<$bq) {
                return "$pn" . 'only remains' . "$sq";
            }
            if ($si!=$seller_id) {
                return 'please choose same seller\'s product';
            }
            $total_price=$total_price+$bq*$pp;
            $product->quantity=$sq-$bq;
            $product->save();
        }

        $token=request()->get('api_token');
        if ($buyer=Buyer::where('api_token', $token)->first()) {
            $shopping=new Shopping();
            $shopping->buyer_id=$buyer->id;
            $shopping->name=request('receiver_name');
            $shopping->email=request('receiver_email');
            $shopping->phone_number=request('receiver_phone');
            $shopping->address=request('address');
            $shopping->total_price=$total_price;
            $shopping->seller_id=$seller_id;
            $shopping->save();
            foreach ($list as $product_id => $bq) {
                $product=Product::where('id', $product_id)->first();
                $shoppinglist=new Shoppinglist;
                $shoppinglist->product_id=$product->id;
                $shoppinglist->quantity=$bq;
                $shoppinglist->shopping_id=$shopping->id;
                $shoppinglist->save();
            }
        }
    }

    public function index() {
        $token=request()->get('api_token');
        if ($buyer=Buyer::where('api_token', $token)->first()) {
            $buyerId=$buyer->id;
            $allList=Shopping::where('buyer_id', $buyerId)->get();
            return $allList;
        }

        if ($seller=Seller::where('api_token', $token)->first()) {
            $sellerId=$seller->id;
            $allList=Shopping::where('seller_id', $sellerId)->get();
            return $allList;
        }
    }
}
