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
}
