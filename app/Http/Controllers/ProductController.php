<?php

namespace App\Http\Controllers;

use App\Img;
use App\Product;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use League\Flysystem\Exception;
use Psy\Exception\RuntimeException;

class ProductController extends Controller
{
    public function show($id)
    {
        $productRes = Product::find($id);
        $news = DB::table('ds_notice_form')->select('title', 'id','time','content')->get()->toArray();

        if ($productRes != null) {
            $productData = $productRes->toArray();
        } else {
           // throw new RuntimeException("游戏未找到");
            $this->SenderUserMsg(false, '未找到此游戏');
        }
        return view('product', ['productData' => $productData,'news'=>$news]);
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
        /*  DB::table('users')
              ->where('name', '=', 'John')
              ->orWhere(function ($query) {
                  $query->where('votes', '>', 100)
                      ->where('title', '<>', 'Admin');
              })
              ->get();*/
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
                    'level'=>$value['level'],
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
        $level= intval($request->get('level'));
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

    public function edit($id){
       $content=DB::table('ds_notice_form')->select('title', 'id','content')->where('id','=',$id)->get()->toArray();
        return view('editContent',['content'=>$content]);
    }
}
