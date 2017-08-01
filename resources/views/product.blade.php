<!DOCTYPE html>
<!-- release v4.4.3, copyright 2014 - 2017 Kartik Visweswaran -->
<!--suppress JSUnresolvedLibraryURL -->
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Krajee JQuery Plugins - &copy; Kartik</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="../themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="../js/jquery2.1.1.min.js"></script>
    <script src="../js/plugins/sortable.js" type="text/javascript"></script>
    <script src="../js/fileinput.js" type="text/javascript"></script>
    <script src="../js/locales/fr.js" type="text/javascript"></script>
    <script src="../js/locales/es.js" type="text/javascript"></script>
    <script src="../themes/explorer/theme.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="../css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet"><link href="../css/animate.min.css" rel="stylesheet">
    <link href="../css/style.min862f.css?v=4.1.0" rel="stylesheet">
</head>
<body>
<div class="container kv-main">
    <form enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden"  id="productId" value="{{$productData['id']}}">
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <label><h2>轮播图片管理</h2></label>
            <input id="file-3" type="file" multiple>
        </div>
    </form>
</div>
<hr>

<div class="container kv-main">
    <form enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden"  id="productId" value="{{$productData['id']}}">
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <label><h2>背景图片管理</h2></label>
            <input id="file-0" type="file" multiple>
        </div>
    </form>
</div>

<script>
    $(function(){
        $.ajaxSetup( {
            headers: { // 默认添加请求头
               'X-CSRF-TOKEN' : $("#_token").val()
            }
        });
        $.ajax({
            type : "post",
            url : "../productimglist",
            data:{_token:$("#_token").val(),"productId":$("#productId").val()},
            dataType : "json",
            success : function(data) {
                showPhotos(data);
            }
        });
    });
    /**
     * 照片展示
     * @param djson
     */
    function showPhotos(djson) {
        //后台返回json字符串转换为json对象
        var reData = eval(djson);
        // 预览图片json数据组
        var preList = new Array();
        var backgroundImg=[];
        var array_element = new Array();
        for (var i = 0; i < reData.length; i++) {
            var array_element = reData[i];
            // 此处判断是轮播图片还是背静图片
            if(array_element.fileIdFile.level===1){
                //轮播图
                preList[i] = array_element.fileIdFile.filePath;

            }else if(array_element.fileIdFile.level===3){
                //背景图
                backgroundImg[i] = array_element.fileIdFile.filePath;
            }
        }
        var _maxFileCount = 3 - preList.length;
        var previewJson = preList;
        // 与上面 预览图片json数据组 对应的config数据
        var preConfigList = new Array();
        for (var i = 0; i < reData.length; i++) {
            var array_element = reData[i];
            var tjson = {
                caption: array_element.fileIdFile.fileName, // 展示的文件名
                width: '120px',
                url: '../deleteimg', // 删除url
                extra: {id:array_element.id,_token:$("#_token").val()}
            };
            preConfigList[i] = tjson;
        }
        $("#file-3").fileinput({
            extra: {id:array_element.id,_token:$("#_token").val()},
            language: 'zh', //设置语言
            uploadUrl: '../uploadimg',
            enctype: 'multipart/form-data',
            showUpload: false,
            showCaption: false,
            maxFileCount: 1,
            msgFilesTooMany: "选择上传的文件数量 超过允许的最大数值！",
            browseClass: "btn btn-primary btn-lg",
            fileType: "any",
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: previewJson,
            initialPreviewConfig:preConfigList,
            uploadExtraData: function(previewId, index) {   //额外参数的关键点
                return {"productId":$("#productId").val(),'level':1};
            }
        });
        $("#file-3").on("fileuploaded", function(event, data, previewId, index) {
            if(data.jqXHR.responseJSON.status == false)
            {
                preList.remove(index);
                $("#"+previewId).remove();
            } else {   }
        });

        $("#file-0").fileinput({
            extra: {id:array_element.id,_token:$("#_token").val()},
            language: 'zh', //设置语言
            uploadUrl: '../uploadimg',
            enctype: 'multipart/form-data',
            showUpload: false,
            showCaption: false,
            maxFileCount: _maxFileCount,
            msgFilesTooMany: "选择上传的文件数量 超过允许的最大数值！",
            browseClass: "btn btn-primary btn-lg",
            fileType: "any",
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: backgroundImg,
            initialPreviewConfig:preConfigList,
            uploadExtraData: function(previewId, index) {   //额外参数的关键点
                return {"productId":$("#productId").val(),'level':3};
            }
        });
        $("#file-0").on("fileuploaded", function(event, data, previewId, index) {
            if(data.jqXHR.responseJSON.status == false)
            {
                backgroundImg.remove(index);
                $("#"+previewId).remove();
            } else {   }
        });
    }
</script>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h2>新闻公告管理</h2>
                </div>
                <div class="ibox-content">

                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="8">
                        <thead>
                        <tr>
                            <th data-toggle="true" class="footable-visible footable-first-column footable-sortable">标题<span class="footable-sort-indicator"></span></th>
                            <th class="footable-visible footable-sortable">添加时间<span class="footable-sort-indicator"></span></th>
                            <th class="footable-visible footable-last-column footable-sortable">操作<span class="footable-sort-indicator"></span></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($news as $new)
                        <tr class="footable-even" style="display: table-row;">
                            <td class="footable-visible footable-first-column"><span class="footable-toggle"></span><?=$new->title?></td>
                            <td class="footable-visible"><?=date('Y-m-d H:i:s',$new->time)?></td>
                            <td class="footable-visible footable-last-column">
                                <a href="#" class="glyphicon glyphicon-zoom-in" data-toggle="modal" data-target=".bs-example-modal-lg_<?=$new->id?>"> </a>&nbsp;&nbsp;
                                <a href="/edit/<?=$new->id?>" class="glyphicon glyphicon-pencil"> </a>&nbsp;&nbsp;
                                <a href="#" class="glyphicon glyphicon-remove"> </a>&nbsp;&nbsp;
                            </td>
                        </tr>
                      @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Large modal -->
@foreach( $news as $v)
<div class="modal fade bs-example-modal-lg_<?=$v->id?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=$v->title?></h4>
            </div>
            <?=$v->content?>
        </div>
    </div>
</div>
@endforeach

<?php echo $news->render(); ?>
<!-- Modal -->
<nav aria-label="...">
    <ul class="pagination">
        <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
        ...
    </ul>
</nav>
</body>
</html>