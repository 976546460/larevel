<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico"> <link href="css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <!-- jqgrid-->
    <link href="css/plugins/jqgrid/ui.jqgridffe4.css?0820" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/style.min862f.css?v=4.1.0" rel="stylesheet">
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
                        订单编号查询：
                        <div class="input-group" style="width: 25%">
                            <input type="text"  name="order_sn" id="order_sn"  value=""  class="form-control">
                            <span class="input-group-btn"><button type="button" onclick="searchOrderList()"  class="btn btn-primary">搜索</button> </span>
                        </div>
                        <br />
                        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                        <table id="table_list_2"></table>
                        <div id="pager_list_2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js?v=2.1.4"></script>
<script src="js/bootstrap.min.js?v=3.3.6"></script>
<script src="js/plugins/peity/jquery.peity.min.js"></script>
<script src="js/plugins/jqgrid/i18n/grid.locale-cnffe4.js?0820"></script>
<script src="js/plugins/jqgrid/jquery.jqGrid.minffe4.js?0820"></script>
<script src="js/content.min.js?v=1.0.0"></script>
<script>
    $(document).ready(function(){
        $.jgrid.defaults.styleUI="Bootstrap";
        orderList();
    });

    function searchOrderList()
    {
        jQuery("#table_list_2").setGridParam({
            postData:{ _token: $("#_token").val(),
            order_sn:$("#order_sn").val()}
        }).trigger("reloadGrid");
    }
    function orderList()
    {
        $("#table_list_2").jqGrid({
            url : 'list',
            datatype : "json",
            height:450,
            autowidth:true,
            mtype: 'POST',
            postData: {
                _token: $("#_token").val(),
                order_sn:$("#order_sn").val()
            },
            shrinkToFit:true,
            rowNum:10,
            rowList:[10,20,30],
            colNames:["编号","订单号","到账账号","充值金额","到账金额","购买类型","购买途径","充值类型","充值名称","订单确认时间","订单状态"],
            colModel:[
                {name:"id",index:"id",editable:true,width:60,sorttype:"int",search:true},
                {name:"payOrder",index:"payOrder",editable:false,width:90,sorttype:"date"},
                {name:"account",index:"account",editable:false,width:100},
                {name:"amount",index:"amount",editable:false,width:100,search:false},
                {name:"reward",index:"reward",editable:false,width:80,align:"right",sorttype:"float",formatter:"number",search:false},
                {name:"buyType",index:"buyType",editable:false,width:80,align:"right",sorttype:"float",search:false},
                {name:"payType",index:"payType",editable:false,width:80,align:"right",sorttype:"float",search:false},
                {name:"location",index:"location",editable:false,width:100,sortable:false,search:false},
                {name:"locationName",index:"locationName",editable:false,width:100,sortable:false,search:false},
                {name:"createTime",index:"createTime",editable:false,width:100,sortable:false,search:false,formatter:"date"},
                {name:"status",index:"status",editable:true,width:100,sortable:false}
            ],
            pager:"#pager_list_2",
            viewrecords:true,
            caption:"充值订单查询",
            hidegrid:false
        });
        $("#table_list_2").setSelection(4,true);
        $("#table_list_2").jqGrid(
                "navGrid","#pager_list_2",
                {edit : false,add : false,search:false},
                {height:200,reloadAfterSubmit:true}
        );
        $(window).bind(
                "resize",
                function(){
                    var width=$(".jqGrid_wrapper").width();
                    $("#table_list_2").setGridWidth(width)
                }
        )
    }
</script>
</body>
</html>
