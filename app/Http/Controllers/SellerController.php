<?php

namespace App\Http\Controllers;

use App\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function store()
    {
        return Seller::create(request()->all());
    }
}
