<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{
    public function getLogin(){
        return view('login.admin_login');
    }
}
