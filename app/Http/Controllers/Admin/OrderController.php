<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PHPUnit\Framework\Constraint\Count;

class OrderController extends Controller
{
    public function orderList()
    {
        $orderData = Order::select('orders.*','users.name as cName', 'pizzas.pizza_name as pName', DB::raw('COUNT(orders.order_id) as count'))
                          ->join('users', 'users.id', 'orders.customer_id')
                          ->join('pizzas', 'pizzas.pizza_id', 'orders.pizza_id')
                          ->groupBy('customer_id','pizza_id')
                          ->orderBy('orders.order_id', 'asc')
                          ->paginate(5);
        return view('admin.order.list')->with(['order' => $orderData]);
    }

    public function searchOrder(Request $request)
    {
        $searchData = $request->searchOrder;
        $orderData = Order::select('orders.*', 'u.name as cName', 'p.pizza_name as pName', DB::raw('COUNT(orders.order_id) as count'))
                          ->join('users as u', 'u.id', 'orders.customer_id')
                          ->join('pizzas as p', 'p.pizza_id', 'orders.pizza_id')
                          ->orWhere('u.name', 'like', '%'.$searchData.'%')
                          ->orWhere('p.pizza_name', 'like', '%'.$searchData.'%')
                          ->groupBy('pizza_id', 'customer_id')
                          ->orderBy('order_id', 'asc')
                          ->paginate(5);
        $orderData->appends($request->all());
        return view('admin.order.list')->with(['order' => $orderData]);
    }
}
