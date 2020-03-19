@extends('login.public')
@section('loginform')
    <div class="sub-main-w3">
        <h2>后台登录</h2>
        <form action="/admin/auth/login" method="post">
            <div class="pom-agile">
                <span class="fa fa-user" aria-hidden="true"></span>
                <input placeholder="管理员账号" name="username" class="uaername" type="text" required="">
            </div>
            @csrf
            <div class="pom-agile">
                <span class="fa fa-key" aria-hidden="true"></span>
                <input placeholder="密码" name="password" class="pass" type="password" required="">
            </div>
            <div class="right-w3l">
                <br>
                <input type="submit" value="登录">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href='/'>用户登录</a>
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
