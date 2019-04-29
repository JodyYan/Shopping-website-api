<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function store()
    {
        request()->validate([
            'name'=>['required', 'max:20', 'string'],
            'email'=>['required', 'max:255', 'email'],
            'password'=>['required', 'max:30']
        ]);
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
        request()->validate([
            'name'=>['required', 'max:20', 'string'],
            'email'=>['required', 'max:255', 'email'],
            'password'=>['required', 'max:30']
        ]);
        $token=request()->get('api_token');
        if (Seller::where('api_token', $token)->first()){
            $id->update(request(['name', 'email']));
            return $id;
        }
        if (request()->has('password')){
                $id->update(request(['password']));
                return 'ok';
            }
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
