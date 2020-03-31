<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Order extends Model
{
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_APPLIED = 'applied';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_FAILED = 'failed';

    const DELIVERY_STATUS_PENDING = 'pending';
    const DELIVERY_STATUS_DELIVERED = 'delivered';
    const DELIVERY_STATUS_RECEIVED = 'received';

    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => '未退款',
        self::REFUND_STATUS_APPLIED    => '已申请退款',
        self::REFUND_STATUS_PROCESSING => '退款中',
        self::REFUND_STATUS_SUCCESS    => '退款成功',
        self::REFUND_STATUS_FAILED     => '退款失败',
    ];

    public static $shipStatusMap = [
        self::DELIVERY_STATUS_PENDING   => '未处理',
        self::DELIVERY_STATUS_DELIVERED => '待配送',
        self::DELIVERY_STATUS_RECEIVED  => '已收货',
    ];

    protected $fillable = [
        'order_number',
        'total',
        'total_profit',
        'remark',
        'paid_at',
        'payment_method',
        'payment_no',
        'refund_status',
        'refund_no',
        'closed',
        'delivery_status',
        'extra',
    ];

    protected $casts = [
        'closed'    => 'boolean',
        'extra'     => 'json',
    ];

    protected $dates = [
        'paid_at',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->order_number) {
                // 调用 findAvailableNo 生成订单流水号
                $model->order_number = static::findAvailableNo();
                // 如果生成失败，则终止创建订单
                if (!$model->order_number) {
                    return false;
                }
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public static function findAvailableNo()//生成订单流水号
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $order_number = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);//str_pad(要改变的值x1，位数x2，x1位数不够x2位要补充的数x3,是在左边补还是右边补还是左右两侧)
            // 判断是否已经存在
            if (!static::query()->where('order_number', $order_number)->exists()) {
                return $order_number;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }

    public static function getAvailableRefundNo() //生成退款订单号
    {
        do {
            // Uuid类可以用来生成大概率不重复的字符串
            $no = Uuid::uuid4()->getHex();
            // 为了避免重复我们在生成之后在数据库中查询看看是否已经存在相同的退款订单号
        } while (self::query()->where('refund_no', $no)->exists());

        return $no;
    }
}
