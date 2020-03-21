<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
  <div class="container">
    <!-- Branding Image -->
    <a class="navbar-brand " href="{{route('products.index')}}" onmouseover="this.style.color='#FF4500'" onmouseout="this.style.color='black'">
      天天旺商品批发
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">

      </ul>

      <!-- Right Side Of Navbar -->
      <!-- <ul class="nav navbar-nav navbar-right">
        <li class="nav-item ">
           <a class="nav-link mt-1" href="{{ route('cart.index') }}"><i class="fa fa-shopping-cart"></i> 购物车</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{Auth::guard('customer')->user()->name}}
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" id="edit" href="/user/edit">修改信息</a>
            <a class="dropdown-item" id="logout" href="{{url('user/logout')}}">退出登录</a>
          </div>
        </li>
      </ul> -->
      <ul class="nav navbar-nav">
            <li class="nav-item">
               <a class="nav-link" href="{{ route('cart.index') }}" onmouseover="this.style.color='#FF4500'" onmouseout="this.style.color='#7D7D7D'"><i class="fa fa-shopping-cart" style="color:#FF4500"></i> 购物车</a>
            </li>
            <!-- <li><a href="#">SVN</a></li> -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" onmouseover="this.style.color='#FF4500'" onmouseout="this.style.color='#7D7D7D'">
                    {{Auth::guard('customer')->user()->name}}
                    <b class="caret"></b>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" id="edit" href="/user/edit">修改信息</a>
                    <a class="dropdown-item" id="logout" href="{{url('user/logout')}}">退出登录</a>
                </div>
            </li>
       </ul>
    </div>
  </div>
</nav>
