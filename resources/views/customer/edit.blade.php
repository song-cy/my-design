@extends('layouts.app')
@section('title', '修改信息')

@section('content')
<div class="row">
<div class="col-md-10 offset-lg-1">
<div class="card">
  <div class="card-header">

      修改个人信息

  </div>
  <div class="card-body">
      <form class="form-horizontal" role="form" action="{{route('user.update')}}" method="post">
        <!-- 会在页面中插入一个隐藏的 input 来告诉 Laravel 把这个表单的请求方式当成 PUT。 -->
      @method('PUT')
        <!-- 引入 csrf token 字段 -->
      {{ csrf_field() }}

        <div class="form-group row">
          <label class="col-form-label text-md-right col-sm-2">ID</label>
          <div class="col-sm-9">
            <input type="text" readonly  unselectable="on" class="form-control" name="id" value="{{Auth::guard('customer')->user()->id}}">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label text-md-right col-sm-2">登录名</label>
          <div class="col-sm-9">
            <input type="text" readonly  unselectable="on" class="form-control" name="username" value="{{Auth::guard('customer')->user()->username}}">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label text-md-right col-sm-2">姓名</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="name" value="{{Auth::guard('customer')->user()->name}}">
          </div>
        </div>
        <div class="form-group row">
           <label class="col-form-label text-md-right col-sm-2">密码</label>
           <div class="col-sm-9">
             <input type="password" class="form-control" autocomplete="off" name="password" value="{{Auth::guard('customer')->user()->password}}">
           </div>
        </div>
        <div class="form-group row">
           <label class="col-form-label text-md-right col-sm-2">确认密码</label>
           <div class="col-sm-9">
             <input type="password" class="form-control" autocomplete="off"  name="password1" value="{{Auth::guard('customer')->user()->password}}">
           </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label text-md-right col-sm-2">电话</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="phone" value="{{Auth::guard('customer')->user()->phone}}">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label text-md-right col-sm-2">商店名称</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="shop_name" value="{{Auth::guard('customer')->user()->shop_name}}">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-sm-2 text-md-right">所属县/乡镇</label>
          <div class="col-sm-3">
              <span class="select-box">
                <select class="form-control" name="county_id">
                   <option value="">请选择县区</option>
                   @foreach($county as $val)
                     <option value="{{$val -> id}}">{{$val -> name}}</option>
                   @endforeach
                </select>
              </span>
          </div>
          <div class="col-sm-3">
              <span class="select-box">
                <select class="form-control" name="town_id">
                  <option value="0">请选择乡镇</option>
                </select>
              </span>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label text-md-right col-sm-2">详细地址</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="dress" value="{{Auth::guard('customer')->user()->dress}}">
          </div>
        </div>
        <div class="form-group row text-center">
          <div class="col-12">
            <button type="submit" class="btn btn-primary">提交</button>
          </div>
        </div>
      </form>
  </div>
</div>
</div>
</div>
@endsection
@section('scriptsAfterJs')
<script type="text/javascript">
    $(function(){
        //在选择县之后列出乡镇的数据
        $('select[name=county_id]').change(function(){
            //获取当前县id
            var id = $(this).val();
            $.get('/user/town/'+id,function(data){
                //jQuery的循环操作
                var str = '';
                $.each(data,function(index,el){
                    str += "<option value=" + el.id + ">" + el.name + "</option>";
                });
                //在追加之前先清除之前的二级之后的数据
                $('select[name=town_id]').find('option:gt(0)').remove();
                //将数据放到对应的option之后
                $('select[name=town_id]').append(str);
            },'json');
        });

      });
</script>
@endsection
