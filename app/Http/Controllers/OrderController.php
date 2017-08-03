<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view("order");
    }
    public function search(Request $request)
    {
        $paramData  = $request->all();
        $page       = isset($paramData['page']) ?(intval($paramData['page']) > 1 ? intval($paramData['page']) - 1:0):0;
        $number     = isset($paramData['number'])?intval($paramData['number']):10;
        $orderSn    = isset($paramData['order_sn'])?(empty($paramData['order_sn']) ? null:strip_tags(trim($paramData['order_sn']))):null;
        $queryNumber      = Order::query();
        if ($orderSn != null)
        {
            $queryNumber->where('payOrder',$orderSn);
        }
        $count      = $queryNumber->count();
        $_data      = array();
        if($count > 0)
        {
            $queryData      = Order::query();
            $id             = $queryData->orderBy('id')->limit(1)->offset($page*$number)->first()->value('id');
            $queryData->where("id",">=",$id);
            if($orderSn)
            {
                $queryData->where("payOrder",$orderSn);
            }
            $_data          = $queryData->limit($number)->get()->toArray();
        }
        $data['page']= isset($paramData['page'])?intval($paramData['page']):1;
        $data['total']  = ($count%$number) > 0 ?  intval($count/$number) + 1: intval($count/$number);
        $data['number']     = $number;
        $data['records'] = $count;
        $data[';']       = $_data;
        echo json_encode($data);
        exit;
    }
}
