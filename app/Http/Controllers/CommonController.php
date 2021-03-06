<?php
/**
 * Created by PhpStorm.
 * User: 何
 * Date: 2017/7/27
 * Time: 11:33
 */

namespace App\Http\Controllers;


use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    public function background()
    {
        return view('background');
    }

    public function getbackgroundimg()
    {

        //查询数据库
        $product = new Product();
        $ImgResult = $product/*->where('id',1)*/->get()->toArray();
        if ($ImgResult != null) {
            $fileList = [];
            {
                foreach ($ImgResult as $key => $value) {
                    $fileList[$key]['id'] = $value['id'];
                    $fileList[$key]['fileIdFile'] = array(
                        'name' => $value['name'],
                        'filePath' => $value['picture']
                    );
                }
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
        $productId   = intval($request->get('id'));
        $Img         = new Product();
        DB::beginTransaction();
        $deleteCount = $Img::destroy($productId);
        if($deleteCount > 0)
        {
            DB::commit();
            $this->SenderUserMsg(true,'删除成功');
        }
        else
        {
            DB::rollBack();
            $this->SenderUserMsg(false,'删除失败');
        }
    }
    public function UploadImg(Request $request)
    {
        $level =   intval($request->get('level'));
        $file = Input::file('file_data');
        if($file -> isValid()) {    //检验一下上传的文件是否有效.
            $clientName = $file -> getClientOriginalName();
            $extension  = $file -> getClientOriginalExtension();
            $newName    = md5(date('ymdhis').$clientName).".".$extension;
            $file -> move('uploads',$newName);
            $filepath   = $request->url().'/../uploads/'.$newName;
            $Img        = new Product();
            $imgId      = $Img->insertGetId(array('name'=>$clientName,'e_name'=>uniqid(),'picture'=>$filepath,'describe'=>'0','e_describe'=>"0","content"=>"0","e_content"=>"0",'create_time'=> date(time())));
            if($imgId)
            {
                $this->SenderUserMsg(true,'上传成功',array('id'=>$imgId));
            }
            else
            {
                $this->SenderUserMsg(false,'上传失败');
            }
        }
        else
        {
            $this->SenderUserMsg(false,'没有上传的文件');
        }
    }

}