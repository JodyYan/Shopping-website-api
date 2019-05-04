<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Seller;
use App\Http\Requests\Membership;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function store(Membership $request)
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
        if ($token===$id->api_token) {
            $seller=Seller::findorfail($id)->first();
            return ['name' => $seller->name, 'email' => $seller->email];
        }
        return 'id or api_token error';
    }

    public function update(Seller $id, Membership $request)
    {
        $token=request()->get('api_token');
        if ($token===$id->api_token) {
            $id->update(request(['name', 'email', 'password']));
            return $id;
        }
         return 'id or api_token error';
    }

    public function logout()
    {
        $token=request()->get('api_token');
        if($s=Seller::where('api_token', $token)->first()){
            $s->api_token = null;
            $s->save();
            return 'already logout';
        }
    }
}
