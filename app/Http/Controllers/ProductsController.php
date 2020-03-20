<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Type;
use App\Exceptions\InvalidRequestException;

class ProductsController extends Controller
{
    public function index(Request $request,$sort = 0){
        if($sort != null){
            // 创建一个查询构造器
            $builder = Product::query()->where('status', true)->where('type_id',$sort);
        }else if($sort == 0){
            $builder = Product::query()->where('status', true);
        }

        // 判断是否有提交 search 参数，如果有就赋值给 $search 变量
        // search 参数用来模糊搜索商品
        if ($search = $request->input('search', '')) {
            $like = '%'.$search.'%';
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            $builder->where(function ($query) use ($like) {
                $query->where('product_name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        // 是否有提交 order 参数，如果有就赋值给 $order 变量
        // order 参数用来控制商品的排序规则
        if ($order = $request->input('order', '')) {
            // 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1], ['price', 'sold_count'])) {
                    // 根据传入的排序值来构造排序参数
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $type=Type::all();//获取分类
        $products = $builder->paginate(18);//分页取出数据
        return view('products.index', [
            'products' => $products,
            'type'=>$type,
            'filters'  => [
                'search' => $search,
                'order'  => $order,
            ],
        ]);
    }

    public function show(Product $product, Request $request)//商品详情页
    {
        // 判断商品是否已经上架，如果没有上架则抛出异常。
        if (!$product->status) {
            throw new InvalidRequestException('商品未上架');
        }
        return view('products.show', ['product' => $product]);
    }


    // public function gettype(){
    //     $type=Type::where('id','!=','0')->get();
    //     return view('pages.root', compact('type'));
    // }
}
