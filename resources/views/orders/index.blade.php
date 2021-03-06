@extends('layouts.app')
@section('title', '订单列表')

@section('content')


<div class="row">
<div class="col-lg-10 offset-lg-1">
    <ul class="nav nav-tabs" role="tablist">
        @if($sta=='null')
        <li class="nav-item">
            <a class="nav-link active"  href="{{route('orders.index')}}">所有订单</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{route('orders.index',['status'=>'obligation'])}}">待付款</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  href="{{route('orders.index',['status'=>'delivery'])}}">待配送</a>
        </li>
        @elseif($sta=='obligation')
        <li class="nav-item">
            <a class="nav-link"  href="{{route('orders.index')}}">所有订单</a>
         </li>
        <li class="nav-item">
            <a class="nav-link  active" href="{{route('orders.index',['status'=>'obligation'])}}">待付款</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  href="{{route('orders.index',['status'=>'delivery'])}}">待配送</a>
        </li>
        @elseif($sta=='delivery')
        <li class="nav-item">
            <a class="nav-link"  href="{{route('orders.index')}}">所有订单</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('orders.index',['status'=>'obligation'])}}">待付款</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active"  href="{{route('orders.index',['status'=>'delivery'])}}">待配送</a>
        </li>
        @endif
    </ul>
<!-- <div class="card"> -->
  <!-- <div class="card-header">订单列表</div> -->
  <!-- <div class="card-body"> -->
    <!-- <div class="card" > -->
        <table class="table" style="margin: 10px 0px">
                <!-- <thead> -->
            <thead style="background-color: rgba(0, 0, 0, 0.03)">
              <tr>
                <th style="width:365px">商品信息</th>
                <th class="text-center" style="width:80px">单价</th>
                <th class="text-center" style="width: 80px">数量</th>
                <th class="text-center" style="width:130px">订单总价</th>
                <th class="text-center" style="width:100px">状态</th>
                <th class="text-center" style="width:200px">操作</th>
              </tr>
            </thead>
                <!-- </thead> -->
        </table>
    <!-- </div> -->
    <ul class="list-group">
        @foreach($orders as $order)
        <li class="list-group-item">
            <div class="card">
                <div class="card-header">订单号：{{ $order->order_number }}
                    <span class="float-right">{{ $order->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
                <div class="card-body">
                    <table class="table">
                    @foreach($order->items as $index => $item)
                       <tr>
                          <td class="product-info" >
                            <div class="preview">
                                <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">
                                    <img src="{{ $item->product->image_url }}">
                                </a>
                            </div>
                            <div >
                                <span class="product-title">
                                    <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}" >{{ $item->product->product_name }}</a>
                                </span>
                                <span class="sku-title">{{ $item->productSku->title }}</span>
                            </div>
                          </td>
                          <td class="sku-price text-center">￥{{ $item->price }}</td>
                          <td class="sku-amount text-center">{{ $item->quantity }}</td>
                          @if($index === 0)
                            <td rowspan="{{ count($order->items) }}" class="text-center total-amount">￥{{ $order->total }}</td>
                            <td rowspan="{{ count($order->items) }}" class="text-center" style="width: 120px">
                            @if($order->paid_at)
                                @if($order->refund_status === \App\Model\Order::REFUND_STATUS_PENDING)
                                    已支付
                                @else
                                    {{ \App\Model\Order::$refundStatusMap[$order->refund_status] }}
                                @endif
                            @elseif($order->closed)
                                已关闭
                            @else
                                未支付<br>
                                请于 {{ $order->created_at->addSeconds(config('app.order_ttl'))->format('H:i') }} 前完成支付<br>
                                否则订单将自动关闭
                            @endif
                            </td>
                            <td rowspan="{{ count($order->items) }}" class="text-center"><a class="btn btn-primary btn-sm" href="{{route('orders.show',['order' => $order->id])}}">查看订单</a></td>
                           @endif
                       </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    <div class="float-right">{{ $orders->render() }}</div>
</div>
</div>
@endsection
