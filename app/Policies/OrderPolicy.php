<?php

namespace App\Policies;

use App\Model\Order;
use App\Model\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function own(Customer $customer, Order $order)
    {
        return $order->customer_id == $customer->id;
    }
}
