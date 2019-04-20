<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function store()
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
        if(Buyer::where('api_token', $token)->first()){
            $buyer=Buyer::findorfail($id)->first();
            return ['name' => $buyer->name , 'email' => $buyer->email];
        }
    }

    public function update(Buyer $id)
    {
        $token=request()->get('api_token');
        if(Buyer::where('api_token', $token)->first()){
            if(request()->has(['name', 'email'])){
                $id->update(request(['name', 'email']));
                return $id;
            }
            if(request()->has('password')){
                $id->update(request(['password']));
                return 'ok';
            }
        }
    }
}
