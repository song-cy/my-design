<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">订单流水号：{{ $order->order_number }}</h3>
    <div class="box-tools">
      <div class="btn-group float-right" style="margin-right: 10px">
        @if(URL::current() === 'http://tiantianw.com/admin/orders/pending/'.$order->id)
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
        @elseif(URL::current() === 'http://tiantianw.com/admin/orders/finished/'.$order->id)
        <a href="{{ route('admin.orders.finished') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
        @elseif(URL::current() === 'http://tiantianw.com/admin/orders/delivery/'.$order->id)
        <a href="{{ route('admin.orders.delivery') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
        @elseif(URL::current() === 'http://tiantianw.com/admin/orders/refund/'.$order->id)
        <a href="{{ route('admin.orders.refund') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
        @elseif(URL::current() === 'http://tiantianw.com/admin/orders/exchange/'.$order->id)
        <a href="{{ route('admin.orders.exchange') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
        @endif
      </div>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered">
      <tbody>
      <tr>
        <td>客户姓名：</td>
        <td>{{ $order->customer->name }}</td>
        <td>支付时间：</td>
        <td>{{ $order->paid_at->format('Y-m-d H:i:s') }}</td>
      </tr>
      <tr>
        <td>支付方式：</td>
        <td>{{ $order->payment_method }}</td>
        <td>支付渠道单号：</td>
        <td>{{ $order->payment_no }}</td>
      </tr>
      <tr>
        <td>商店名称</td>
        <td>{{ $order->customer->shop_name }}</td>
        <td>商店详细地址</td>
        <td>{{ $order->customer->route->name }} {{ $order->customer->dress }}</td>
      </tr>
      <tr>
        <td rowspan="{{ $order->items->count() + 1 }}">商品列表</td>
        <td>商品名称</td>
        <td>单价</td>
        <td>数量</td>
      </tr>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product->product_name }} {{ $item->productSku->title }}</td>
        <td>￥{{ $item->price }}</td>
        <td>{{ $item->quantity }}</td>
      </tr>
      @endforeach
      <tr>
        <td>订单金额：</td>
        <td style="color:red">￥{{ $order->total}}</td>
        <td>订单状态：</td>
        <td style="color:red">{{ \App\Model\Order::$shipStatusMap[$order->delivery_status] }}</td>
      </tr>
      @if($order->delivery_status === \App\Model\Order::DELIVERY_STATUS_PENDING)
      <tr>
        <td colspan="3"></td>
        <td >
          <a href="{{ route('admin.orders.ship', [$order->id]) }}" class="btn btn-info" role="button">配送</a>
        </td>
      </tr>
      @endif
      @if($order->refund_status !== \App\Model\Order::REFUND_STATUS_PENDING)
      <tr>
        <td>退款状态：</td>
        <td colspan="2">{{ \App\Model\Order::$refundStatusMap[$order->refund_status] }}，理由：{{ $order->extra['refund_reason'] }}</td>
        <td>
          <!-- 如果订单退款状态是已申请，则展示处理按钮 -->
          @if($order->refund_status === \App\Model\Order::REFUND_STATUS_APPLIED)
          <button class="btn btn-sm btn-success" id="btn-refund-agree">同意</button>
          <button class="btn btn-sm btn-danger" id="btn-refund-disagree">不同意</button>
          @endif
        </td>
      </tr>
      @endif
      @if($order->exchange_status !== \App\Model\Order::REFUND_STATUS_PENDING)
      <tr>
        <td>换货状态：</td>
        <td colspan="2">{{ \App\Model\Order::$exchangeStatusMap[$order->exchange_status] }}，理由：{{ $order->extra['refund_reason'] }}</td>
        <td>
          <!-- 如果订单退款状态是已申请，则展示处理按钮 -->
          @if($order->exchange_status === \App\Model\Order::REFUND_STATUS_APPLIED)
          <button class="btn btn-sm btn-success" id="btn-exchange-agree">同意</button>
          <button class="btn btn-sm btn-danger" id="btn-exchange-disagree">不同意</button>
          @endif
        </td>
      </tr>
      @endif
      </tbody>
    </table>
  </div>
</div>
<script>
$(document).ready(function() {
  // 不同意 按钮的点击事件
  $('#btn-refund-disagree').click(function() {
    // Laravel-Admin 使用的 SweetAlert 版本与我们在前台使用的版本不一样，因此参数也不太一样
    swal({
      title: '输入拒绝退款理由',
      input: 'text',
      showCancelButton: true,
      confirmButtonText: "确认",
      cancelButtonText: "取消",
      showLoaderOnConfirm: true,
      preConfirm: function(inputValue) {
        if (!inputValue) {
          swal('理由不能为空', '', 'error')
          return false;
        }
        // Laravel-Admin 没有 axios，使用 jQuery 的 ajax 方法来请求
        return $.ajax({
          url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
          type: 'POST',
          data: JSON.stringify({   // 将请求变成 JSON 字符串
            agree: false,  // 拒绝申请
            reason: inputValue,
            // 带上 CSRF Token
            // Laravel-Admin 页面里可以通过 LA.token 获得 CSRF Token
            _token: LA.token,
          }),
          contentType: 'application/json',  // 请求的数据格式为 JSON
        });
      },
      allowOutsideClick: false
    }).then(function (ret) {
      // 如果用户点击了『取消』按钮，则不做任何操作
      if (ret.dismiss === 'cancel') {
        return;
      }
      swal({
        title: '操作成功',
        type: 'success'
      }).then(function() {
        // 用户点击 swal 上的按钮时刷新页面
        location.reload();
      });
    });
  });

  // 同意 按钮的点击事件
    $('#btn-refund-agree').click(function() {
      swal({
        title: '确认要将款项退还给用户？',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: "确认",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        preConfirm: function() {
          return $.ajax({
            url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
            type: 'POST',
            data: JSON.stringify({
              agree: true, // 代表同意退款
              _token: LA.token,
            }),
            contentType: 'application/json',
          });
        },
        allowOutsideClick: false
      }).then(function (ret) {
        // 如果用户点击了『取消』按钮，则不做任何操作
        if (ret.dismiss === 'cancel') {
          return;
        }
        swal({
          title: '操作成功',
          type: 'success'
        }).then(function() {
          // 用户点击 swal 上的按钮时刷新页面
          location.reload();
        });
      });
    });

    // 不同意换货 按钮的点击事件
  $('#btn-exchange-disagree').click(function() {
    // Laravel-Admin 使用的 SweetAlert 版本与我们在前台使用的版本不一样，因此参数也不太一样
    swal({
      title: '输入拒绝退款理由',
      input: 'text',
      showCancelButton: true,
      confirmButtonText: "确认",
      cancelButtonText: "取消",
      showLoaderOnConfirm: true,
      preConfirm: function(inputValue) {
        if (!inputValue) {
          swal('理由不能为空', '', 'error')
          return false;
        }
        // Laravel-Admin 没有 axios，使用 jQuery 的 ajax 方法来请求
        return $.ajax({
          url: '{{ route('admin.orders.handle_exchange', [$order->id]) }}',
          type: 'POST',
          data: JSON.stringify({   // 将请求变成 JSON 字符串
            agree: false,  // 拒绝申请
            reason: inputValue,
            // 带上 CSRF Token
            // Laravel-Admin 页面里可以通过 LA.token 获得 CSRF Token
            _token: LA.token,
          }),
          contentType: 'application/json',  // 请求的数据格式为 JSON
        });
      },
      allowOutsideClick: false
    }).then(function (ret) {
      // 如果用户点击了『取消』按钮，则不做任何操作
      if (ret.dismiss === 'cancel') {
        return;
      }
      swal({
        title: '操作成功',
        type: 'success'
      }).then(function() {
        // 用户点击 swal 上的按钮时刷新页面
        location.reload();
      });
    });
  });

   // 同意 按钮的点击事件
    $('#btn-exchange-agree').click(function() {
      swal({
        title: '确认同意换货？',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: "确认",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        preConfirm: function() {
          return $.ajax({
            url: '{{ route('admin.orders.handle_exchange', [$order->id]) }}',
            type: 'POST',
            data: JSON.stringify({
              agree: true, // 代表同意退款
              _token: LA.token,
            }),
            contentType: 'application/json',
          });
        },
        allowOutsideClick: false
      }).then(function (ret) {
        // 如果用户点击了『取消』按钮，则不做任何操作
        if (ret.dismiss === 'cancel') {
          return;
        }
        swal({
          title: '操作成功',
          type: 'success'
        }).then(function() {
          // 用户点击 swal 上的按钮时刷新页面
          location.reload();
        });
      });
    });
});
</script>
