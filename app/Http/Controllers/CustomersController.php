<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Customer;
use App\Model\Route;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function edit(){    //用户修改个人信息页面
        $county=Route::where('p_id','=','0')->get(); //查询县的数据
        return view('customer.edit',compact('county'));
    }

    public function town($id){
        $data=Route::where('p_id','=',$id)->get();  //根据父级id查询乡镇数据
        return response()->json($data);
    }

    public function update(Request $request){   //用户修改信息操作
        $input=$request->all();
        $res = Customer::where('id', $input['id'])
            ->update([
                'name'=>$input['name'],
                'password'=>$input['password'],
                'phone'=>$input['phone'],
                'shop_name'=>$input['shop_name'],
                'town_id'=>$input['town_id'],
                'dress'=>$input['dress'],
            ]);

        return redirect('/user/edit');
    }
}
