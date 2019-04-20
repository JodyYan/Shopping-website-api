<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $guarded = [];
    protected $hidden = ['password'];
}
