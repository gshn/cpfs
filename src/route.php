<?php
use helper\Route;
use helper\Database;
use helper\CkeditorUploader;
use helper\Benchmark;
use helper\Install;
use helper\PushNotification;
use controller\User;
use controller\Notice;
use controller\Api;

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

Route::get('/api', function() {
    $api = new Api();
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

Route::get('/push/android', function() {
    $rst = PushNotification::android([
        'title' => '타이틀타이틀타이틀타이틀타이틀타이틀타이틀타이틀',
        'body' => '테스트 메시지 테스트 메시지 테스트 메시지 테스트 메시지 테스트 메시지 테스트 메시지'
    ], 'APA91bG_aFazHxRLIoCUz8nyQzFrAWBBinNeE6gs4jQYOpymoiUowfcAlqnO3EpvKdOkW5QpTx745CgeUxZJGXydg8TAk64yiFR7lHkefo15A9lz_7VY99APS-qvrTy3-zTBWGRl40-y');
    printr($rst);
});

Route::get('/push/ios', function() {
    $rst = PushNotification::ios([
        'title' => '타이틀타이틀타이틀타이틀타이틀타이틀타이틀타이틀',
        'body' => '테스트 메시지 테스트 메시지 테스트 메시지 테스트 메시지 테스트 메시지 테스트 메시지'
    ], '8c817c0883e30f2f6cdf1018694a75be3952449e65375ed38366b0b4c1a3a668');
    printr($rst);
});

Route::get('/', function($req, $res) {
    Route::main();
});
