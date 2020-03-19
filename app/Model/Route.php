<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['name','p_id','route_id'];
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
