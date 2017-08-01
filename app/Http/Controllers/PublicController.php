<?php

namespace App\Http\Controllers;
use App\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PublicController extends Controller
{
    //
    public function index()
    {
        return view('main');
    }
    public function show()
    {
        return view('main');
    }

    public  function imgList(Request  $request){
        $Img       = new Img();
        $ImgResult = $Img->where('pId',0)->get();
        if($ImgResult != null)
        {
            $fileList   = array();
            foreach ($ImgResult->toArray() as $key=>$value)
            {
                $fileList[$key]['id']     = $value['id'];
                $fileList[$key]['fileIdFile']   = array(
                    'level'=>$value['level'],
                    'name'      =>$value['name'],
                    'filePath'  =>$value['url']
                );
            }
            echo json_encode($fileList);
            exit;
        }
        else
        {
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
        $Img         = new Img();
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
            $Img        = new Img();
            $imgId      = $Img->insertGetId(array('name'=>$clientName,'url'=>$filepath,'href'=>"","pid"=>0,"level"=>$level));
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
