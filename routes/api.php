<?php

Route::group(['namespace' => 'Api'], function () {
    Route::any('yzy/create', 'YzyController@create');
    Route::resource('yzy', 'YzyController');
    Route::any('call','YzyController@callback');
    // 定制客户端
    Route::get('login', 'LoginController@login');

});
