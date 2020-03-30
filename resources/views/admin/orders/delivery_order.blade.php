<div class="panel-group" id="accordion">
    @foreach($routes as $route)
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion"
                   href="#{{$route->route_id}}">
                    {{$route->route_id}}号路线
                </a>
            </h4>
        </div>
        <div id="{{$route->route_id}}" class="panel-collapse collapse in">
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead style="background-color:#F8F8FF">
                        <td >订单流水号</td>
                        <td >客户姓名</td>
                        <td >总金额</td>
                        <td >支付时间</td>
                        <td >配送状态</td>
                        <td >退款状态</td>
                        <td >操作</td>
                    </thead>
                    @foreach($orders as $order)
                       @if($order->customer->route->route_id === $route->route_id)
                    <tr>
                        <td >{{$order->order_number}}</td>
                        <td >{{$order->customer->name}}</td>
                        <td >{{$order->total}}</td>
                        <td >{{$order->paid_at}}</td>
                        <td >{{ \App\Model\Order::$shipStatusMap[$order->delivery_status] }}</td>
                        <td >{{ \App\Model\Order::$refundStatusMap[$order->refund_status] }}</td>
                        <td ><a href="{{route('admin.delivery.show',['order'=>$order->id])}}">订单详情</a></td>
                    </tr>
                       @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    @endforeach

<script type="text/javascript">


</script>
