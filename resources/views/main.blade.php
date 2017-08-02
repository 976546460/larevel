<!DOCTYPE html>
<html lang="en">
<head>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="../js/jquery2.1.1.min.js"></script>
    <script src="../js/plugins/sortable.js" type="text/javascript"></script>
    <script src="../js/fileinput.js" type="text/javascript"></script>
    <script src="../themes/explorer/theme.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
<div class="container kv-main">
    <form enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden"  id="level" value=1{{--轮播图的传参为 1 --}}>
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <label><h2>轮播图片管理</h2></label>
            <input class="file-1" type="file" multiple>
        </div>
    </form>
</div>

<hr>
<div class="container kv-main">
    <form enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden"  id="level" value=0{{--首页大图的传参为 0  --}}>
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <label><h2>横幅大图管理</h2></label>
            <input class="file-0" type="file" multiple>
        </div>
    </form>
</div>
</body>

<hr>
<div class="container kv-main">
    <form enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden"  id="level" value=2{{--详情小图的传参为 2  --}}>
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <label><h2>详情小图管理</h2></label>
            <input class="file-2" type="file" multiple>
        </div>
    </form>
</div>
</body>
<script>
    $(function(){
        $.ajaxSetup( {
            headers: { // 默认添加请求头
                'X-CSRF-TOKEN' : $("#_token").val()
            }
        });
        $.ajax({
            type : "post",
            url : "../mainimglist",//
            data:{"level":$('#level').val()},
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
        var preListFile_1 = new Array(); var preListFile_0=  new Array(); var preListFile_2=  new Array();
        var array_element = new Array();
        for (var i = 0; i < reData.length; i++) {
            var array_element = reData[i];
            // 此处指针对.txt判断，其余自行添加
            // 图片类型
            // preList[i] = array_element.fileIdFile.filePath;
            if(array_element.fileIdFile.level=== 1){
                preListFile_1[i]= array_element.fileIdFile.filePath;
            }else if(array_element.fileIdFile.level=== 0){
                preListFile_0[i] = array_element.fileIdFile.filePath;

            }else if(array_element.fileIdFile.level=== 2) {
                preListFile_2[i] = array_element.fileIdFile.filePath;

            }
        }
//        console.debug(preListFile_0.length);
        var _maxFileCount_File_0 = 1;
        var _maxFileCount_File_1 = (4 - preListFile_1.length==0)? true:(4 - preListFile_1.length);
        var _maxFileCount_File_2 = (4 - preListFile_2.length==0)? true:(4 - preListFile_2.length);
        // 与上面 预览图片json数据组 对应的config数据
        var preConfigList_All = new Array();
        for (var i = 0; i < reData.length; i++) {
            var array_element = reData[i];
            var tjson = {
                caption: array_element.fileIdFile.fileName, // 展示的文件名
                width: '120px',
                url: '../maindeleteimg', // 删除url
                extra: {id:array_element.id,_token:$("#_token").val()}
            };
            preConfigList_All[i]= tjson;
        }
        $(".file-1").fileinput({
            extra: {id:array_element.id,_token:$("#_token").val()},
            language: 'zh', //设置语言
            uploadUrl: '../mainuploadimg',
            enctype: 'multipart/form-data',
            showUpload: false,
            showCaption: false,
            maxFileCount:_maxFileCount_File_1,
            msgFilesTooMany: '选择上传的文件数量超过允许的最大数值 ！',//一次最多选择上传数量
            browseClass: "btn btn-primary btn-lg",
            fileType: "any",
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: preListFile_1,
            initialPreviewConfig:preConfigList_All,
            uploadExtraData: function(previewId, index) {   //额外参数的关键点
                return {"level":1};
            }
        });

        $(".file-0").fileinput({
            extra: {id:array_element.id,_token:$("#_token").val()},
            language: 'zh', //设置语言
            uploadUrl: '../mainuploadimg',
            enctype: 'multipart/form-data',
            showUpload: false,
            showCaption: false,
            maxFileCount:_maxFileCount_File_0,
            msgFilesTooMany:'选择上传的文件数量超过允许的最大数值 ！',
            browseClass: "btn btn-primary btn-lg",
            fileType: "any",
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: preListFile_0,
            initialPreviewConfig:preConfigList_All,
            uploadExtraData: function(previewId, index) {   //额外参数的关键点
                return {"level":0};
            }
        });
        $(".file-0").on("fileuploaded", function(event, data, previewId, index) {
            if(data.jqXHR.responseJSON.status == false)
            {
                preListFile_0.remove(index);
                $("#"+previewId).remove();
            }
            else {   }
        });

        $(".file-1").on("fileuploaded", function(event, data, previewId, index) {
            if(data.jqXHR.responseJSON.status == false)
            {
                preListFile_1.remove(index);
                $("#"+previewId).remove();
            }
            else {   }
        });


        $(".file-2").fileinput({
            extra: {id:array_element.id,_token:$("#_token").val()},
            language: 'zh', //设置语言
            uploadUrl: '../mainuploadimg',
            enctype: 'multipart/form-data',
            showUpload: false,
            showCaption: false,
            maxFileCount: _maxFileCount_File_2,
            msgFilesTooMany: '选择上传的文件数量超过允许的最大数值 ！',
            browseClass: "btn btn-primary btn-lg",
            fileType: "any",
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: preListFile_2,
            initialPreviewConfig:preConfigList_All,
            uploadExtraData: function(previewId, index) {   //额外参数的关键点
                return {"level":2};
            }
        });
        $(".file-2").on("fileuploaded", function(event, data, previewId, index) {
            if(data.jqXHR.responseJSON.status == false)
            {
                preListFile_0.remove(index);
                $("#"+previewId).remove();
            }
            else {   }
        });
    }
</script>
</html>