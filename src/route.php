<?php
use helper\Route;
use helper\Database;
use helper\CkeditorUploader;
use helper\Benchmark;
use helper\Install;
use controller\User;
use controller\Notice;

$route = new Route();
$is = Route::$is;

if ($is['user']) {
    // 회원
    Route::get('/user/row', function() {
        $user = new User();
        $user->row(URIS[3]);
    });

    Route::post('/user/row', function() {
        $user = new User();
        $user->rowUpdate();
    });

    Route::get('/user', function() {
        $user = new User();
        $user->rows();
    });

    Route::post('/user', function() {
        $user = new User();
        $user->rowsUpdate();
    });

} else {
    // 로그인
    Route::get('/login', function() {
        User::formLogin();
    });

    Route::post('/login', function() {
        $user = new User();
        $user->login();
    });
}

// 공지사항
Route::get('/notice/row', function() {
    $notice = new Notice();
    $notice->row(URIS[3]);
});

Route::post('/notice/row', function() {
    $notice = new Notice();
    $notice->rowUpdate();
});

Route::get('/notice', function() {
    $notice = new Notice();
    $notice->rows();
});

Route::post('/notice', function() {
    $notice = new Notice();
    $notice->rowsUpdate();
});

Route::post('/uploader', function() {
    $ckeditorUploader = new CkeditorUploader();
});

// 로그아웃
Route::get('/logout', function() {
    User::logout();
});

// 벤치마크
Route::get('/benchmark', function() {
    $benchmark = new Benchmark();
});

// 부트스트랩
Route::get('/bootstrap', function() {
    Route::bootstrap();
});

// 인스톨
Route::get('/install', function() {
    $install = new Install();
});

Route::get('/', function($req, $res) {
    if ($res['user']) {
        Route::location('/user');
    } else {
        Route::location('/bootstrap');
    }
});
