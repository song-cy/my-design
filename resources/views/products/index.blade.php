@extends('layouts.app')
@section('title', '商品列表')

@section('content')

<!-- 筛选组件开始 -->
<form action="{{ route('products.index') }}" class="search-form">
    <div class="form-row">
        <div class="col">
            <div class="form-row">
                <div class="col-2"></div>
                <div class="col-7"><input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="搜索" style="border-color:#FF4500;border-width:2px;height:40px;font-size:15px "></div>
                <div class="col-2"><button class="btn btn-primary btn-sm" style="background-color: #FF4500;border-color: #FF4500;height: 40px;font-size: 15px">&nbsp;&nbsp;搜索&nbsp;&nbsp;</button></div>
            </div>
        </div>
    </div>
</form>
<!-- 筛选组件结束 -->


<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#home">所有商品</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu1">购买过</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu2">Menu 2</a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div id="home" class="container tab-pane active"><br>
       <div class="card bg-light text-dark">
            <div class="card-body">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link"  href="{{route('products.index')}}">所有分类</a>
                    </li>
                    @foreach($type as $t)
                        <li class="nav-item">
                            <a class="nav-link"  href="{{route('products.index',['sort'=>$t->id])}}">{{$t->type_name}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="flex-container">
            <div style="width:100%">
             <!-- 筛选组件开始 -->
                <form action="{{ route('products.index') }}" class="search-form">
                    <div class="form-row" style="margin-top:5px">
                        <div class="col-md-9">
                        </div>
                        <div class="col-md-3" >
                            <select name="order" class="form-control form-control-sm float-right" style="width:150px;border-width: 2px;border-color: #FF4500" >
                                <option value="">排序方式</option>
                                <option value="price_asc">价格从低到高</option>
                                <option value="price_desc">价格从高到低</option>
                                <option value="sold_count_desc">销量从高到低</option>
                                <option value="sold_count_asc">销量从低到高</option>
                            </select>
                        </div>
                    </div>
                </form>
            <!-- 筛选组件结束 -->

                <div class="row products-list">
                @foreach($products as $product)
                    <div class="col-2 product-item" >
                        <div class="product-content">
                            <div class="top">
                                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                    <img src="{{ $product->image_url }}" alt="">
                                </a>
                                <div class="price">
                                    <b>￥</b>{{ $product->price }}
                                </div>
                                <div class="title">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}" onmouseover="this.style.color='#FF4500'" onmouseout="this.style.color='#333'">{{ $product->product_name }}
                                    </a>
                                </div>
                            </div>
                            <div class="bottom">
                                <div class="sold_count">
                                    销量 <span>{{ $product->sold_count }}笔</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="float-right">{{ $products->appends($filters)->render() }}
                </div><!--appends() 方法接受一个 Key-Value 形式的数组作为参数，在生成分页链接的时候会把这个数组格式化成查询参数。-->
            </div>
        </div>
    </div>

    <div id="menu1" class="container tab-pane fade"><br>
        <h3>Menu 1</h3>
        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
    <div id="menu2" class="container tab-pane fade"><br>
        <div class="flex-container">
            <div class="flex-item">flex item 1</div>
            <div class="flex-item">flex item 2</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 1</div>
            <div class="flex-item">flex item 2</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
            <div class="flex-item">flex item 3</div>
        </div>
    </div>
</div>

@endsection
@section('scriptsAfterJs')
    <script>
        var filters = {!! json_encode($filters) !!};
        $(document).ready(function () {
            $('.search-form input[name=search]').val(filters.search);
            $('.search-form select[name=order]').val(filters.order);

            $('.search-form select[name=order]').on('change', function() {
               $('.search-form').submit();
            });
        })
    </script>
@endsection
