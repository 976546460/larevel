<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: he
 * Date: 2017/8/3
 * Time: 10:57
 */
class AddProductController extends Controller
{
    public function index()
    {
        $rs = DB::table('ds_meun')->get()->toArray();
        $product_id = DB::table('ds_product')->select('id', 'name')->get()->toArray();
       $rows=[];
        array_unique()
        /*foreach ($rs as $r) {
            foreach ($product_id as $pro) {
                if ($r->product_id != $pro->id && $r->product_id > 0){
                    var_dump($r->product_id);
                    var_dump($pro->id);
                    echo '<hr>';


                   /* $rows[]=['id'=>$pro->id];
                    $rows[]=['name'=>$pro->name];
                }
            }
        }*/
         return view('ProductList',['rs'=>$rs,'rows'=>$rows]);
    }
    //save
    public function add(Request $request)
    {
        $title = $request->get("title");
        $name = $request->get("name");
        $url = $request->get("url");
        $r = DB::table('ds_meun')->insert(['title' => $title, 'name' => $name, 'url' => $url, 'pid' => 2, 'lv' => 2, 'status' => 1]);
        if ($r) {
            return json_encode(true);
        } else {
            return json_encode(false);
        }
    }
}

