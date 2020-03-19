<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Route;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function town(Request $request){
        $pid = $request->get('q');
        return Route::where('p_id', $pid)->get(['id', DB::raw('name as text')]);
    }
}
