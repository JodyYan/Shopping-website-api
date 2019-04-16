<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function store()
    {
        return Seller::create(request()->all());
    }

    public function login()
    {
        $i=request()->get('email');
        $q=request()->get('password');
        if($s=Seller::where('email', $i)->where('password', $q)->first()) {
            $token = Str::random(60);
            $s->api_token=$token;
            $s->save();
            return $s;
        }

    }

    public function show(Seller $id)
    {
        $token=request()->get('api_token');
        if(Seller::where('api_token', $token)->first()){
            $seller=Seller::findorfail($id)->first();
            return ['name' => $seller->name, 'email' => $seller->email];
        }
    }

    public function update(Seller $id)
    {
        $token=request()->get('api_token');
        if(Seller::where('api_token', $token)->first()){
            $id->update(request(['name', 'email']));
            return $id;
        }
    }
}
