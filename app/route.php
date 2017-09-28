<?php
Route::get('/', function() {
    Route::template('/template/main');
});

Route::get('/member/login', function() {
    Route::template('/member/login', 'no-header');
});

Route::post('/member/login', function($me) {
    $me->login();
});

Route::get('/member/logout', function($me) {
    $me->logout();
});
