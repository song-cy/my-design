<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class LoginController extends Controller
{
    public function login(){
        return view('login.user_login');
    }

    public function check(Request $request){
        $validator = Validator::make($input=$request->all(), [
                'username' => 'required|between:2,10',//用户ID必填，长度最大10位
                'password' => 'required|min:5',
                ]);
        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }
        //验证是否有此用户
        $data=$request->only('username','password');
        $data['status']='1';//要求状态为启动的用户
        if(Auth::guard('customer')->attempt($data)){
            return redirect('/user/products/sort/');   //验证客户通过
        }else {
            return redirect('/')->withErrors([
                'errors'=>'用户ID或密码错误'
            ]);
        }
    }

    public function logout(Request $request){
        //退出
        Auth::guard('customer') -> logout();
        $request->session()->invalidate();
        //跳转到登录页面
        return redirect('/');
    }

}
