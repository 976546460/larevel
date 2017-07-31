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
    Route::post('productimglist'    , 'ProductController@ImgList');
    Route::post('deleteimg','ProductController@DeleteImg');
    Route::post('uploadimg','ProductController@UploadImg');
  //  Route::get('edit','ProductController@edit');
    //主页管理 路由
    Route::resource('main'  , 'PublicController');
    Route::post('mainimglist'    , 'PublicController@ImgList');
    Route::post('mainuploadimg','PublicController@UploadImg');
    Route::post('maindeleteimg','PublicController@DeleteImg');

    //新闻公告管理路由
    Route::resource('news',   'NewsController');


    Route::resource('product'       ,'ProductController');

});
Route::get('edit/{id}', 'ProductController@edit');

?>