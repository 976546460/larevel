<?php

namespace App\Http\Controllers;

use App\Img;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    //页面初始化
    public function show($id)
    {
        $productRes = Product::find($id);

        $product_meun = DB::table('ds_meun')->select('title', 'id')->where('title', $productRes->name)->first();
        if ($productRes != null && $product_meun != null) {
            $productData = $productRes->toArray();
        } else {
            // throw new RuntimeException("游戏未找到");
            $this->SenderUserMsg(false, '未找到此游戏');
        }
        return view('product', ['productData' => $productData, 'product_info' => $product_meun]);
    }

    /**
     * 创建者: YangMin
     * PhotoList
     * 轮播图片
     * @param Request $request
     */
    public function ImgList(Request $request)
    {
        $productId = intval($request->get('productId'));
        $Img = new Img();
        $ImgResult = $Img->where('pid', $productId)
            ->orWhere(function ($query) {
                $query->where('level', '=', 1)
                    ->where('level', '=', 3);
            })->get();
        if ($ImgResult != null) {
            $fileList = array();
            foreach ($ImgResult->toArray() as $key => $value) {
                $fileList[$key]['id'] = $value['id'];
                $fileList[$key]['fileIdFile'] = array(
                    'level' => $value['level'],
                    'name' => $value['name'],
                    'filePath' => $value['url']
                );
            }
            echo json_encode($fileList);
            exit;
        } else {
            echo json_encode(array());
            exit;
        }
    }

    /**
     * 创建者: YangMin
     * DeleteImg
     *
     */
    public function DeleteImg(Request $request)
    {
        $productId = intval($request->get('id'));
        $Img = new Img();
        DB::beginTransaction();
        $deleteCount = $Img::destroy($productId);
        if ($deleteCount > 0) {
            DB::commit();
            $this->SenderUserMsg(true, '删除成功');
        } else {
            DB::rollBack();
            $this->SenderUserMsg(false, '删除失败');
        }
    }

    public function UploadImg(Request $request)
    {
        $productId = intval($request->get('productId'));
        $level = intval($request->get('level'));
        if ($productId == 0) {
            $this->SenderUserMsg(false, '没有发现此产品');
        } else {
            $file = Input::file('file_data');
            if ($file->isValid()) {    //检验一下上传的文件是否有效.
                $clientName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $newName = md5(date('ymdhis') . $clientName) . "." . $extension;
                $file->move('uploads', $newName);
                $filepath = $request->url() . '/../uploads/' . $newName;
                $Img = new Img();
                $imgId = $Img->insertGetId(array('name' => $clientName, 'url' => $filepath, 'href' => "", "pid" => $productId, "level" => $level));
                if ($imgId) {
                    $this->SenderUserMsg(true, '上传成功', array('id' => $imgId));
                } else {
                    $this->SenderUserMsg(false, '上传失败');
                }
            } else {
                $this->SenderUserMsg(false, '没有上传的文件');
            }
        }
    }

    public function edit($id)
    {
        $id = addslashes($id);
        $content = DB::table('ds_notice_form')->select('title', 'pid', 'id', 'content')->where('id', '=', $id)->get()->toArray();
        if (empty($content)) {
           return false;
        } else {
           return view('editContent', ['content' => $content]);
       }
    }

    public function editSave(Request $request)
    {
        // exit;
        $title = $request->get('title');
        $content = $request->get('content');
        $id = intval($request->get('id'));
        $time = date(time());
        $sql = "update  `ds_notice_form` set title='{$title}',content='{$content}',`time`={$time} where id=?";
        $r = DB::update($sql, [$id]);
        if ($r) {
            return [true];
        } else {
            return [false];
        }
    }

    // 保存编辑器ajax提交过来的二进制图片数据流
    public function editorupload(Request $request)
    {
        $img_content = $request->get('img');// 获取数据流内容
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)) {//正则匹配出数据流和图片的格式
            $type = $result[2];
            $dir = "./uploads/newsimg/" . date("Y-m-d", time()) . "/";
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $new_file = $dir . uniqid() . rand(1111, 9999) . ".{$type}";;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img_content)))) {
                return json_encode(['path' => $request->url() . "/.." . strstr($new_file, '/')]);
            } else {
                return json_encode(['path' => false]);
            }
        }
    }

    //分页 + 搜索
    public function newslist(Request $request)
    {
        /**
         * @param  page  integer 当前页数
         * @param  count  int      总条数
         * @param  total_page  int   总页数
         * @param  $pid    int     产品ID
         * @param  $title_data  string   搜索字符
         */
        $page_size = 10;//em($request->get('number'))? $request->get('number'):10;//当前每页显示条数
        $title_data = $request->get('title_data');
        $pid = $request->get('pid');//对应的产品ID
        if (isset($title_data)) {//得到总条数
            $count = DB::table('ds_notice_form')->where('title', 'like', '%' . $title_data . '%')->where('pid', '=', $pid)->count();
        } else {
            $count = DB::table('ds_notice_form')->where('pid', '=', $pid)->count();
        }
        $total_page = ceil($count / $page_size);
        $page = (1 <= ($request->get('page')) && ($request->get('page')) <= $total_page) ? $request->get('page') : $total_page; // 判断当前页数是否默认第一页
        //建立查询数据
        if (isset($title_data)) {
            $_data = DB::table('ds_notice_form')->where('title', 'like', '%' . $title_data . '%')->where('pid', '=', $pid)->orderBy('id', 'DESC')->offset(($page - 1) * $page_size)->limit($page_size)->get();
        } else {
            $_data = DB::table('ds_notice_form')->where('pid', '=', $pid)->orderBy('id', 'DESC')->offset(($page - 1) * $page_size)->limit($page_size)->get();
        }
        $data['page'] = $page;
        $data['total']  = $total_page;
        $data['number'] = $page_size;
        $data['records'] = $count;
        $data['rows']    = $_data;
        return   json_encode($data);
    }

    //删除文章
    public function delete(Request $request)
    {
        $id = $request->get('id');
        $r = DB::table('ds_notice_form')->where('id', $id)->delete();
        if ($r) {
            return json_encode(['status' => true]);
        } else {
            return json_encode(['status' => false]);
        }
    }

    //查看新闻公告
    public function looknews($id)
    {

        $id = addslashes($id);
        $content = DB::table('ds_notice_form')->select('title', 'time', 'content')->where('id', '=', $id)->first();
        if (empty($content)) {
            return view('404');
        } else {
            return view('showNews', ['content' => $content]);
        }
    }

    //修改ds_meun表的产品标题和ds_product表里面的name
    public function titlesave(Request $request)
    {
        DB::beginTransaction();
        $meun_title = DB::table('ds_meun')->where('id', $request->get('id'))->update(['title' => $request->get('product_new_title')]);
        $product_title = DB::table('ds_product')->where('name', $request->get('product_old_title'))->update(['name' => $request->get('product_new_title')]);
        if ($meun_title && $product_title) {
            DB::commit();
            return json_encode(true);
        } else {
            DB::rollBack();
            return json_encode(false);
        }
    }
    //创建新闻
    public  function addNews($id){
        return view('addNews',['id'=>$id]);
    }
    //保存新建的新闻
    public  function  addSave(Request $request){
        $title=$request->get('title');
        $content=$request->get('content');
        $pid=$request->get('pid');
        if(empty($title) || empty($content) || empty($pid)){
            return json_encode(false);
        }
        $r= DB::table("ds_notice_form")->insert(['title'=>$title, 'content'=>$content, 'pid'=>$pid, 'time'=>date(time())]);
        if($r){
            return json_encode(true);
        }else{
            return  json_encode(false);
        }


    }


}

