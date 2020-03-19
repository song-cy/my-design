@extends('login.public')
@section('loginform')
    <div class="sub-main-w3">
        <h2>用户登录</h2>
        <form action="{{ '/user/check'}}" method="post">
            <div class="pom-agile">
                <span class="fa fa-user" aria-hidden="true"></span>
                <input placeholder="用户名" name="username" class="username" type="text" required="">
            </div>
            @csrf
            <div class="pom-agile">
                <span class="fa fa-key" aria-hidden="true"></span>
                <input placeholder="密码" name="password" class="pass" type="password" required="">
            </div>
            <div class="right-w3l">
                <br>
                <input type="submit" value="登录">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="/admin/auth/login">后台登录</a>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
@endsection
