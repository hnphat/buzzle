<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CauHinhController@routeView')->name('trangchu');
Route::get('/admin', 'UserController@login')->name('login');
Route::post('/login', 'UserController@postLogin')->name('postlogin');
Route::get('/out',function(){
    Auth::logout();
    session([
        'admin' => 0
    ]);  
    return redirect()->route('login');
})->name('out');
Route::group(['prefix' => 'management', 'middleware' => 'login'], function(){
    // Version 1 
    Route::get('nhomanh','HinhAnhController@getNhomList')->name('nhomanh.panel');
    Route::get('nhomanh/ajax/list','HinhAnhController@getNhomAjaxList');
    Route::post('nhomanh/ajax/post','HinhAnhController@postNhomAjaxHinh');
    Route::post('nhomanh/ajax/postedit','HinhAnhController@postNhomAjaxHinhEdit');
    Route::post('nhomanh/ajax/delete','HinhAnhController@deleteNhomAjaxHinh');
    Route::get('nhomanh/ajax/getedit','HinhAnhController@getNhomEdit')->name("nhom.getedit");

    Route::get('guest','GuestController@getList')->name('guest.panel');
    Route::get('guest/ajax/list','GuestController@getAjaxList')->name("guest.danhsach");
    Route::post('guest/ajax/post','GuestController@postAjax');
    Route::post('guest/ajax/delete','GuestController@deleteAjax');
    Route::post('guest/ajax/delete/all','GuestController@deleteAjaxAll');
    Route::post('guest/ajax/import','GuestController@importGuest')->name("import.guest");
    Route::post('num/ajax/import','GuestController@importNumber')->name("import.num");

    Route::get('cauhinh','CauHinhController@getList')->name('cauhinh.panel');
    Route::get('cauhinh/ajax/get','CauHinhController@getAjax');
    Route::post('cauhinh/ajax/saveconfig','CauHinhController@saveConfig');

    Route::get('khaosat/panel','CauHinhController@getKhaoSatPanel')->name('khaosat.panel');
    Route::get('khaosat/getlist','CauHinhController@getKhaoSatList')->name('khaosat.getlist');
    Route::post('khaosat/delete','CauHinhController@deleteKhaoSat')->name('khaosat.delete');
    Route::post('khaosat/deleteall','CauHinhController@deleteKhaoSatAll')->name('khaosat.delete.all');

    Route::get('quayso','QuaySoController@index')->name('quayso.panel');
    Route::get('quayso/ajax/list','QuaySoController@getDanhSach')->name("quayso.danhsach");
    Route::post('quayso/ajax/post','QuaySoController@postAjax');
    Route::get('batdauquay','QuaySoController@batDauQuaySo')->name('quayso');
    Route::post('quayso/ajax/set','QuaySoController@setSo');
});
Route::get('quaythuong','CauHinhController@getQuayThuong')->name('quaythuong');
Route::get('khaosat','CauHinhController@getKhaoSat')->name('khaosat');
Route::get('khaosatv2','CauHinhController@getKhaoSatV2')->name('khaosatv2');
Route::get('nhanqua','CauHinhController@getNhanQua')->name('nhanqua');
Route::post('setquatang','CauHinhController@setGift')->name('sendtocskh');
Route::post('cauhinh/ajax/post','CauHinhController@postSubmit')->name('postthongtin');
Route::post('cauhinh/ajax/post/tracnghiem','CauHinhController@postSubmitTracNghiem')->name('postthongtintracnghiem');
Route::post('khaosat/post','CauHinhController@postKhaoSat')->name('postkhaosat');
Route::post('khaosat/postv2','CauHinhController@postKhaoSatV2')->name('postkhaosatv2');
Route::post('khaosat/post/somayman','CauHinhController@postKhaoSatSoMayMan')->name('postkhaosat.somayman');

Route::get('traloicauhoi','CauHinhController@getTraLoiCauHoi')->name('traloi.panel');
Route::post('chonsomayman','GuestController@postSoMayMan')->name('chonsomayman.post');
Route::post('tachso','GuestController@tachSo')->name('tachso.post');




