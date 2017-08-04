<?php
/*Route::any('main','PublicController@index');
Route::any('searchorder','OrderController@index');
Route::post('searchorderdata','OrderController@search');
Route::get('product/{id}','ProductController@index')->where('id', '[0-9]+');
Route::post('productimglist','ProductController@PhotoList');
Route::post('deleteimg','ProductController@DeleteImg');
Route::post('upload','ProductController@Upload');
*/
Route::group(['middleware' => 'verify'], function ()
{
    Route::get('/'          ,'LoginController@index');
    Route::post('login'     ,'LoginController@login');
    Route::any('signout'    ,'LoginController@signout');
    Route::post('list'      ,'OrderController@search');
    Route::resource('order' , 'OrderController');
    //产品图片
    Route::post('productimglist'    , 'ProductController@ImgList');
    Route::post('deleteimg','ProductController@DeleteImg');
    Route::post('uploadimg','ProductController@UploadImg');
    //主页管理 路由
    Route::resource('main'  , 'PublicController');
    Route::post('mainimglist'    , 'PublicController@ImgList');
    Route::post('mainuploadimg','PublicController@UploadImg');
    Route::post('maindeleteimg','PublicController@DeleteImg');
    //新闻编辑
    Route::resource('product'       ,'ProductController');
    Route::get('edit/{id}', 'ProductController@edit');
    Route::get('deletenews', 'ProductController@delete');
    Route::get('look/{id}', 'ProductController@looknews');
    Route::post('editsave', 'ProductController@editSave');
    Route::post('editorupload', 'ProductController@editorupload');
    Route::post('productTitleSave', 'ProductController@titlesave');
    Route::get('addnews/{id}', 'ProductController@addNews');
    Route::post('addsave', 'ProductController@addSave');
    //产品列表
    Route::resource('productlist','AddProductController');
    Route::post('addproduct','AddProductController@add');

//菜单管理
    Route::get('editorupload', 'ProductController@editorupload');

    //新闻公告分页
});



?>