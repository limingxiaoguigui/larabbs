<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //修改白名单
    protected  $fillable = [

        'name', 'description'

    ];
}