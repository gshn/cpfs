<?php
/**
 * @file /app/common.php
 * @author hheo (hheo@cozmoworks.com)
 * 해당 프레임웍이 실행될 때 마다 수행하는 코어 스크립트
 * 데이터 베이스 연결, 기본 설정 fetch, 세션 세팅, 검색관련 전역 변수 인젝션 방지
 * 세션 초기화 및 세션 기본 설정
 * 로그인시 해야하는 기본 동작, 자동로그인 처리, 회원 비회원 구분 처리
 * HTTP 캐시 해더 설정을 처리함
 * @see /app/config.php 전역 상수, 전역 변수
 * @see /app/lib.php 공통 함수
 * @see /app/route.php URI 라우트 설정
 */
require __DIR__.'/config.php';
require PATH.'/lib.php';

/**
 * PDO 연결
 * object PDO
 */
if (!empty($cf['db_name']) && !empty($cf['db_user']) && !empty($cf['db_pass']) && !empty($cf['db_host'])) {
    $pdo = new Database($cf['db_name'], $cf['db_user'], $cf['db_pass'], $cf['db_host']);

    /**
     * 세션 관련 설정
     * session.cache_expire 세션 캐쉬 보관시간 (분)
     * session.gc_maxlifetime 세션 데이터의 가비지 콜렉션 존재 기간을 지정 (초)
     * 세션 유지 시간을 늘리기 위해서는 위의 설정 값을 원하는 기간만큼 늘려주면 됨
     */
    ini_set('session.cache_expire', 1800);
    ini_set('session.gc_maxlifetime', 43200);
    session_start();

    /**
     * 로그인관련 기본 동작
     * $_SESSION['id'] string 로그인 중일 때 존재하는 세션 값
     * array login_check($mb_id) 오늘 처음 로그인인지 확인하고 맴버 전역 변수를 할당
     * 자동로그인 쿠키가 존재 할 때 기본 동작
     * md5로 쿠키 저장 후 서로 일치하는지 검증
     * 서버 IP, 클라이언트 IP, 클라이언트 AGENT, 클라이언트 비밀번호를 해시값으로 사용
     * 쿠키에 저장된 해시값과 현재 해시값이 일치하면 로그인 한 것으로 간주
     * 세션 시작이 제대로 되지 않을 경우 페이지를 다시 리로드
     * @see control/user/Login-check.php
     * @see system/lib.php array login_check()
     */
    $mb = new Member();
    if (!empty($_SESSION['id'])) {
        $member = $mb->getRow('id', $_SESSION['id']);
    } else {
        $member = $mb->getAutoLogin();
    }
}

if (!empty($member['id'])) {
    $is_member = true;
} else {
    $is_guest = true;
}

// 웹뷰, 모바일, 윈도우즈 체크
$cz['is_webview'] = is_webview();
$cz['is_mobile'] = $cz['is_webview'] ? true : is_mobile();
$cz['is_windows'] = is_windows();

/**
 * 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
 * 캐쉬의 내용을 가져옴. 브라우저 별로 모두 완전한지는 검증되지 않음
 */
header('Content-Type: text/html; charset=utf-8');
header('Expires: 0');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Pragma: no-cache');

require PATH.'/route.php';
