<!DOCTYPE html>
<!-- release v4.4.3, copyright 2014 - 2017 Kartik Visweswaran -->
<!--suppress JSUnresolvedLibraryURL -->
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Krajee JQuery Plugins - &copy; Kartik</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="favicon.ico">
    <script src="../js/jquery2.1.1.min.js"></script>
</head>
<body>
<div class="container kv-main">
    <form enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden" id="productId" value="{{$productData['id']}}">
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <label><h2>轮播图片管理</h2></label>
            <input id="file_3" type="file" multiple>
        </div>
    </form>
</div>
<hr>

<div class="container kv-main">
    <form enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden" id="productId" value="{{$productData['id']}}">
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <label><h2>背景图片管理</h2></label>
            <input id="file_0" type="file" multiple>
        </div>
    </form>
</div>

<script>
    $(function () {
        $.ajaxSetup({
            headers: { // 默认添加请求头
                'X-CSRF-TOKEN': $("#_token").val()
            }
        });
        $.ajax({
            type: "post",
            url: "../productimglist",
            data: {_token: $("#_token").val(), "productId": $("#productId").val()},
            dataType: "json",
            success: function (data) {
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
        var backgroundImg = [];
        var array_element = new Array();
        for (var i = 0; i < reData.length; i++) {
            var array_element = reData[i];
            // 此处判断是轮播图片还是背静图片
            if (array_element.fileIdFile.level === 1) {
                //轮播图
                preList[i] = array_element.fileIdFile.filePath;

            } else if (array_element.fileIdFile.level === 3) {
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
                extra: {id: array_element.id, _token: $("#_token").val()}
            };
            preConfigList[i] = tjson;
        }
        $("#file_3").fileinput({
            extra: {id: array_element.id, _token: $("#_token").val()},
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
            initialPreviewConfig: preConfigList,
            uploadExtraData: function (previewId, index) {   //额外参数的关键点
                return {"productId": $("#productId").val(), 'level': 1};
            }
        });
        $("#file_3").on("fileuploaded", function (event, data, previewId, index) {
            if (data.jqXHR.responseJSON.status == false) {
                preList.remove(index);
                $("#" + previewId).remove();
            } else {
            }
        });

        $("#file_0").fileinput({
            extra: {id: array_element.id, _token: $("#_token").val()},
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
            initialPreviewConfig: preConfigList,
            uploadExtraData: function (previewId, index) {   //额外参数的关键点
                return {"productId": $("#productId").val(), 'level': 3};
            }
        });
        $("#file_0").on("fileuploaded", function (event, data, previewId, index) {
            if (data.jqXHR.responseJSON.status == false) {
                backgroundImg.remove(index);
                $("#" + previewId).remove();
            } else {
            }
        });
    }
</script>
<script src="../js/fileinput.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js" type="text/javascript"></script>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">
    <link href="../css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="../css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <!-- jqgrid-->
    <link href="../css/plugins/jqgrid/ui.jqgridffe4.css?0820" rel="stylesheet">
    <link href="../css/animate.min.css" rel="stylesheet">
    <link href="../css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <style>
        #alertmod_table_list_2 {
            top: 900px !important;
        }
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <p>&nbsp;</p>
                    <div class="jqGrid_wrapper">
                        输入标题查询：
                        <div class="input-group" style="width: 25%">
                            <input type="text" name="title_data" id="title_data" value="" class="form-control">
                            <span class="input-group-btn"><button type="button" onclick="searchOrderList()"
                                                                  class="btn btn-primary">搜索</button> </span>
                        </div>
                        <br/>
                        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                        <table id="table_list_2"></table>
                        <div id="pager_list_2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/plugins/peity/jquery.peity.min.js"></script>
<script src="../js/plugins/jqgrid/i18n/grid.locale-cnffe4.js?0820"></script>
<script src="../js/plugins/jqgrid/jquery.jqGrid.minffe4.js?0820"></script>
<script src="../js/content.min.js?v=1.0.0"></script>
<script>
    $(document).ready(function () {
        $.jgrid.defaults.styleUI = "Bootstrap";
        orderList();
    });

    function searchOrderList() {
        jQuery("#table_list_2").setGridParam({
            postData: {
                _token: $("#_token").val(),
                title_data: $("#title_data").val()
            }
        }).trigger("reloadGrid");
    }
    function orderList() {
        $("#table_list_2").jqGrid({
            url: '/newslist',
            datatype: "json",
            height: 450,
            autowidth: true,
            mtype: 'POST',
            postData: {
                _token: $("#_token").val(),
                title_data: $("#title_data").val(),
                pid:$("#productId").val()
            },
            shrinkToFit: true,
            rowNum: 10,
            rowList: [10, 20, 30],
            colNames: ["标题", "创建时间", "操作"],
            colModel: [
                {name: "title", index: "title", editable: true, width: 200, sorttype: "int", search: true},
                {
                    name: "time", index: "time", editable: false, width: 100, editType: "text",
                    formatter: function dateTime(cellvalue) {
                        if (cellvalue != undefined) {
                            var date = new Date(parseInt(cellvalue) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
                            return date;
                        }
                    }
                },
                {
                    name: "id", index: "id", edtiable: false, width: 50, editType: "text",
                    formatter: function imageFormatter(cellvalue) {
                        var str = "<a href='/look/" + cellvalue + "' class='btn btn-outline btn-info'><span class='glyphicon glyphicon-zoom-in'></span></a> " +
                                " <a href='/edit/" + cellvalue + "' class='btn btn-outline btn-warning'><span class='glyphicon glyphicon-edit'></apan></a>  " +
                                "<a href='javascript:;' onclick=\"deleteData(" + cellvalue + ")\" class='btn btn-outline btn-danger'><span class='glyphicon glyphicon-remove'></apan></a>";
                        return str;
                    },
                },
            ],
            pager: "#pager_list_2",
            viewrecords: true,
            caption: "新闻公告：",
            hidegrid: false,
        });
        $("#table_list_2").setSelection(4, true);
        $("#table_list_2").jqGrid(
                "navGrid", "#pager_list_2",
                {edit: false, add: false, search: false},
                {height: 200, reloadAfterSubmit: true}
        );
        $(window).bind(
                "resize",
                function () {
                    var width = $(".jqGrid_wrapper").width();
                    $("#table_list_2").setGridWidth(width)
                }
        )
    }
    //文章删除
    function deleteData(data) {
        $.ajaxSetup({
            headers: { // 默认添加请求头
                'X-CSRF-TOKEN': $("#_token").val()
            }
        });
        $.get("/deletenews",{token: $("#_token").val(),id: data, },function (rs){
                    if(rs.status===true){
                    //删除页面的
                        $('#'+data+'').remove();
                    }
        },"json")
    }
</script>
</body>


</body>
</html>