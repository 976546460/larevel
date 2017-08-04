<!DOCTYPE html>
<html>
<!-- Mirrored from www.zi-han.net/theme/hplus/form_editors.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:35 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>H+ 后台主题UI框架</title>
    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

    <link rel="shortcut icon" href="favicon.ico"> <link href="../css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="../css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="../css/animate.min.css" rel="stylesheet">
    <link href="../css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="../css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
    <link href="../css/style.min862f.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="wrapper wrapper-content">

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Wyswig Summernote 富文本编辑器</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_editors.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_editors.html#">选项1</a>
                            </li>
                            <li><a href="form_editors.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content no-padding">

                    <div class="summernote">
                       @foreach($content as $v)
                           <?=$v->content?>
                    </div>
                <!-- Button trigger modal -->
                    <div style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" >
                            提交
                        </button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">请输入文章的标题：</h4>

                                </div>
                                <div class="modal-body content_title" contenteditable="true" style="border: 1px; border-color: #00b7ee; border-style:solid" >
                                    <?=$v->title?>                               </div>
                                <div class="modal-footer">
                                    <div style="display: none;"  class="uniquid"><?=$v->id?></div>
                                    <div style="display: none;"  class="_pid"><?=$v->pid?></div>
                                    <button type="button" class="btn btn-default " data-dismiss="modal">关闭</button>
                                    <button type="button" class="btn btn-primary content_save">保存</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div style="display: none;"  class="_token">{{csrf_token()}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery.min.js?v=2.1.4"></script>
<script src="../js/bootstrap.min.js?v=3.3.6"></script>
<script src="../js/content.min.js?v=1.0.0"></script>
<script src="../js/plugins/summernote/summernote.min.js"></script>
<script src="../js/plugins/summernote/summernote-zh-CN.js"></script>
<script>
    $(document).ready(function(){$(".summernote").summernote({lang:"zh-CN"})});var edit=function(){$("#eg").addClass("no-padding");$(".click2edit").summernote({lang:"zh-CN",focus:true})};var save=function(){$("#eg").removeClass("no-padding");var aHTML=$(".click2edit").code();$(".click2edit").destroy()};
///上传文章图片
    var date=[];
    var imgDate=[];
    $(function(){
        $('.note-editable').bind('DOMSubtreeModified',function(){ //监听事件
            imgDate=$('.note-editable img');
            imgDate.each(function (i,v) {//遍历所有的img标签节点
                var imglen=v.src.length;
               if(imglen<225){//判断图片的src是路径还是二进制数据流
                    date[imglen]=imglen;
                   }
                if (imglen != date[imglen]) {//判断是否是重复数据
                    $.ajax({ //上传图像
                    type: "post",
                         url: "/editorupload",
                         data: {
                             _token:$("._token").text(),
                             img:v.src,
                             productId:Number($("._pid").text())
                         },
                         dataType: "json",
                         success: function (data) {
                             //将上传成功后的图片的src替换为路径字符串
                             imgDate[i].src =data.path;
                         }
                     });
                     date[imglen]=imglen;
                 }
            })
        })
    })
//上传文章内容
    $('.content_save').click(function () {
        $.post("/editsave",
                {
                    content:$('.note-editable').html(),
                    title:$('.content_title').text(),
                    _token:$('._token').text(),
                    id:$('.uniquid').text(),
                },
                function(status){
                    // console.debug(status)
                    if(status[0]==true) {
                            alert('保存成功！');
                        window.location.href='/product/'+Number($('._pid').text());
                    }else{
                        alert('保存失败！ 请重试！');
                    }});
    })
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
</body>

<!-- Mirrored from www.zi-han.net/theme/hplus/form_editors.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:35 GMT -->
</html>

