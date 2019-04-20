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
}
