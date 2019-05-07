<?php

namespace App\Http\Controllers;

use App\Seller;
use App\Product;
use App\Buyer;
use App\Shopping;
use App\Shoppinglist;
use Illuminate\Http\Request;
use App\Http\Requests\Cart;
use App\Http\Requests\Status;

class ShoppingController extends Controller
{
    public function store(Cart $request){
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
        return 'add successfully';
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

    public function show(Shopping $shopping) {
        $token=request()->get('api_token');
        if ($buyer=Buyer::where('api_token', $token)->first()) {
            $bi=$buyer->id;
            $sbi=$shopping->buyer_id;
            $showList=Shoppinglist::where('shopping_id', $shopping->id)->get();
            if ($bi==$sbi) {
                return $showList;
            }
        }

        if ($seller=Seller::where('api_token', $token)->first()) {
            $si=$seller->id;
            $ssi=$shopping->seller_id;
            $showList=Shoppinglist::where('shopping_id', $shopping->id)->get();
            if ($si==$ssi) {
                return $showList;
            }
        }
    }

    public function buyerdestroy(Shopping $shopping) {
        $token=request()->get('api_token');
        $showList=Shoppinglist::where('shopping_id', $shopping->id)->get();
        if ($buyer=Buyer::where('api_token', $token)->first()) {
            if ($buyer->id!==$shopping->buyer_id) {
                return 'Use wrong buyer token';
            }
            $buyerDelete=request()->get('buyer_delete');
            $shopping->buyer_delete=$buyerDelete;
            $shopping->save();
            $cancel=$this->cancelDeal($shopping);
            return 'already change buyer_delete to ' . "$buyerDelete" . "\n" . $cancel;
        }
    }

    public function sellerdestroy(Shopping $shopping) {
        $token=request()->get('api_token');
        $showList=Shoppinglist::where('shopping_id', $shopping->id)->get();
        if ($seller=Seller::where('api_token', $token)->first()) {
            if ($seller->id!==$shopping->seller_id) {
                return 'Use wrong seller token';
            }
            $sellerDelete=request()->get('seller_delete');
            $shopping->seller_delete=$sellerDelete;
            $shopping->save();
            $cancel=$this->cancelDeal($shopping);
            return 'already change seller_delete to ' . "$sellerDelete" . "\n" . $cancel;
        }
    }

    private function cancelDeal(Shopping $shopping) {
        $showList=Shoppinglist::where('shopping_id', $shopping->id)->get();
        if ($shopping->buyer_delete == 1 && $shopping->seller_delete == 1) {
            foreach ($showList as $deleteList) {
                $deleteList->delete();
            }
            $shopping->delete();
            return 'Successfully delete this order';
        }
    }

    public function sellertrack(Shopping $shopping, Status $request) {
        $token=request()->get('api_token');
        $seller=Seller::where('api_token', $token)->first();
        if ($shopping->seller_id==$seller->id) {
            $track=request()->get('status');
            $shopping->status=$track;
            $shopping->save();
            return $this->trackStatus($track);
        }
        return 'Use wrong token';
    }

    public function buyertrack(Shopping $shopping) {
        $token=request()->get('api_token');
        $buyer=Buyer::where('api_token', $token)->first();
        if ($shopping->buyer_id==$buyer->id) {
            $track=$shopping->status;
            return $this->trackStatus($track);
        }
    }

    private function trackStatus($track) {
        if ($track==1) {return '訂單成立';}
        if ($track==2) {return '確認付款';}
        if ($track==3) {return '付款失敗';}
        if ($track==4) {return '確認出貨';}
        if ($track==5) {return '取貨完成';}
        if ($track==6) {return '訂單完成';}
    }
}
