<?php
use helper\Route;

$ro = new Route();
if (($uri === '/login' || $uri === '/login/company') && $is_guest) {
    // 로그인
    if ($_POST) {
        $us->login($uris[2]);
    } else {
        $us->formLogin($uris[2]);
    }
} else if (($uri === '/login/naver' || $uri === '/login/naver/company') && $is_guest) {
    // 네이버 아이디 로그인
    $us->naverLogin($uris[3]);

} else if (($uri === '/login/facebook' || $uri === '/login/facebook/company') && $is_guest) {
    // 페이스북 아이디 로그인
    $us->facebookLogin($uris[3]);

} else if (($uri === '/login/kakao' || $uri === '/login/kakao/company') && $is_guest) {
    // 카카오 아이디 로그인
    $us->kakaoLogin($uris[3]);

} else if ($uri === '/logout') {
    // 로그아웃
    controller\User::logout();

} else if (($uri === '/register' || $uri === '/register/company') && $is_guest) {
    // 회원가입
    if ($_POST) {
        $us->register($uris[2]);
    } else {
        $us->formRegister($uris[2]);
    }

} else if (($uri === '/forgot' || $uri === '/forgot/company') && $is_guest) {
    // 아이디/비밀번호 찾기
    if ($_POST) {
        $us->forgot($uris[2]);
    } else {
        $us->formForgot($uris[2]);
    }

} else if ($uri === '/') {
    // 메인
    $ro->main();

} else if ($uri === '/promotion') {
    // 프로모션
    $ro->promotion();

} else if ($uri === '/bootstrap') {
    // 레이아웃 샘플
    template('/template/bootstrap', null, 'no-header');

} else {
    // 잘못된 경로일 경우 메인으로
    location('/');

}
