<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Buyer;
use App\Http\Requests\Membership;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function store(Membership $request)
    {
        return Buyer::create(request()->all());
    }

    public function login()
    {
        $email=request()->get('email');
        $password= request()->get('password');
        if($buyer=Buyer::where('email', $email)->where('password', $password)->first()) {
            $token = Str::random(60);
            $buyer->api_token=$token;
            $buyer->save();
            return $buyer;
        }
    }

    public function show(Buyer $id)
    {
        $token=request()->get('api_token');
        if($token===$id->api_token){
            $buyer=Buyer::findorfail($id)->first();
            return ['name' => $buyer->name , 'email' => $buyer->email];
        }
        return 'id or api_token error';
    }

    public function update(Buyer $id, Membership $request)
    {
        $token=request()->get('api_token');
        if($token===$id->api_token){
            if(request()->has(['name', 'email', 'password'])){
                $id->update(request(['name', 'email', 'password']));
                return $id;
            }
        }
        return 'id or api_token error';
    }

    public function logout()
    {
        $token=request()->get('api_token');
             if($buyer=Buyer::where('api_token', $token)->first()){
                 $buyer->api_token = null;
                 $buyer->save();
                 return 'already logout';
             }
    }
}
