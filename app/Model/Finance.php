<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $fillable = [
                    'title','type','total'
    ];
}
