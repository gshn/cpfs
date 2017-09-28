<?php
Route::get('/', function() {
    Route::template(VIEW.'/template/main.php');
});

Route::get('/member/login', function() {
    Route::template(VIEW.'/member/login.php', 'no-header');
});

Route::post('/member/login', function($me) {
    $me->login();
});
