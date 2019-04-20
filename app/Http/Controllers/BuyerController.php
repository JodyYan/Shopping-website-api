<?php

namespace App\Http\Controllers;

use App\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function store()
    {
        return Buyer::create(request()->all());
    }
}
