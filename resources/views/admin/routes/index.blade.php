<div class="btn-group" >
    <a href="{{ route('admin.routes.create') }}" class="btn btn-info" role="button" style="margin-bottom: 5px">新增路线</a>
</div>

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
                        <td >ID</td>
                        <td >县-乡镇</td>
                        <td >路线</td>
                        <!-- <td >操作</td> -->
                    </thead>
                    @foreach($towns as $town)
                       @if($town->route_id === $route->route_id)
                    <tr data-id="{{$town->id}}">
                        <td >{{$town->id}}</td>
                        <td >{{$town->pname}}-{{$town->name}}</td>
                        <!-- <td style="width:100px">{{$town->pname}}</td> -->
                        <td >{{$town->route_id}}号路线</td>
                        <!-- <td ><button class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash" ></i></button></td> -->
                    </tr>
                       @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
<script type="text/javascript">
// $(function () {
//     $('#1').collapse('hide')
// });

// $(document).ready(function (){
//     // 监听 移除 按钮的点击事件
//     $('.btn-remove').click(function () {
//       // $(this) 可以获取到当前点击的 移除 按钮的 jQuery 对象
//       // closest() 方法可以获取到匹配选择器的第一个祖先元素，在这里就是当前点击的 移除 按钮之上的 <tr> 标签
//       // data('id') 方法可以获取到我们之前设置的 data-id 属性的值，也就是对应的 SKU id
//       var id = $(this).closest('tr').data('id');
//       swal({
//         title: "确认要将该商品移除？",
//         icon: "warning",
//         buttons: ['取消', '确定'],
//         dangerMode: true,
//       })
//       .then(function(willDelete) {
//         // 用户点击 确定 按钮，willDelete 的值就会是 true，否则为 false
//         if (!willDelete) {
//           return;
//         }
//         axios.delete('/admin/routes/' + id)
//           .then(function () {
//             location.reload();
//           })
//       });
//     });
// })

</script>
