<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>产品管理</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="favicon.ico">
    <script src="../js/jquery2.1.1.min.js"></script>
</head>
<body>
<div class="container kv-main">
    <div class="form-group">
        <h1>&nbsp;</h1>
        <div style="padding-left: 10px">
            <h2>标题修改</h2>
        </div>
        {{--title修改--}}
        <div class="row">
            <div class="form-group  has-feedback ">
                <div class="col-lg-8" style="padding-left: 25px">
                    <label class="control-label" for="inputError2" style="display : none;">输入错误！</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="product_title"
                               value="<?=$product_info->title?>">
                        <input type="hidden" name="title_edit" id="product_id" value="<?=$product_info->id?>">
                        <input type="hidden" name="title_edit" id="product_old_title"
                               value="<?=$product_info->title?>">
                        <span class="input-group-btn">

                            <button class="btn btn-info " type="button"
                                    id="edit_title_btn">&nbsp; 修&nbsp; 改&nbsp; </button>
                        </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->
            <br> <br>
            {{--图片上传框--}}
            <input type="hidden" id="productId" value="{{$productData['id']}}">
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            <div class="input_prent_div" style="padding-left: 28px">
                <label><h2>轮播图片管理</h2></label>

                <input id="file_3" type="file" multiple>
            </div>
        </div>

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
                // 此处判断是轮播图片还是背静图片 还是title字符串
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

        //修改标题ajax、
        $('#edit_title_btn').click(function () {
            var product_title = $('#product_title').val();
            if (product_title == $('#product_old_title').val() || product_title.replace(/\s/g, "") == "") {
                $(".has-feedback").addClass('has-error');
                $(".control-label").show();
            } else {
                $.post('/productTitleSave',
                        {
                            id: $("#product_id").val(),
                            product_new_title: product_title,
                            product_old_title: $('#product_old_title').val(),
                        }, function (data) {
                            if (data) {
                                alert('成功')
                                window.location.href = '/product/' + Number($("#productId").val());
                            } else {
                                alert('失败！请重试！')
                            }
                        }, "json")
            }
        })

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
            #gbox_table_list_2 {
                width: 1110px;
                margin: auto;
            }

            .ibox-content {
                width: 1160px;
                margin: auto;
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
                            <div class="input-group " style="width: 35%">
                                <input type="text" name="title_data" id="title_data" value=""
                                       class="form-control">
                                <span class="input-group-btn">
                                <button type="button" onclick="searchOrderList()" class="btn btn-primary">搜索</button>
                               <a href="/addnews/{{$productData['id']}}"  style="left: 585px;"
                                       class="btn btn-info glyphicon glyphicon-pencil">  添 加</a>
                            </span>

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
                height: 400,
                autowidth: true,
                mtype: 'POST',
                postData: {
                    _token: $("#_token").val(),
                    title_data: $("#title_data").val(),
                    pid: $("#productId").val()
                },
                shrinkToFit: true,
                rowNum: 10,
                rowList: [10, 20, 30],
                colNames: ["标题", "创建时间", "操作"],
                colModel: [
                    {name: "title", index: "title", editable: true, width: 100, sorttype: "int", search: true},
                    {
                        name: "time", index: "time", editable: false, width: 60, editType: "text",
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
                        $("#table_list_2").setGridWidth('1200px')
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
            $.get("/deletenews", {token: $("#_token").val(), id: data,}, function (rs) {
                if (rs.status === true) {
                    //删除页面的
                    $('#' + data + '').remove();
                }
            }, "json")
        }
    </script>
    </body>

</body>
</html>