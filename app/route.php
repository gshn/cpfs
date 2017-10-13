<?php
if ($is_guest && $uri !== '/login') {
    location('/login');
}

if ($uri === '/dashboard') {

    define('DASHBOARD', true);
    template('/template/main');

} else if ($uri === '/login') {

    if ($_POST) {
        $ad->login();
    } else {
        template('/admin/login', 'no-header');
    }

} else if ($uri === '/logout') {

    $ad->logout();

} else if (strpos($uri, '/excel') === 0) {

    $excel = new ExcelHelper();
    $method = $uris[2];
    $excel->$method();

} else if ($uri === '/user') {

    $us = new User();
    if ($_POST) {
        $us->listUpdate();
    } else {
        $us->list();
    }

} else if (strpos($uri, '/user/row') === 0) {

    $us = new User();
    if ($_POST) {
        $us->rowUpdate();
    } else {
        $us->row($uris[3]);
    }

} else if (strpos($uri, '/product/row') === 0) {

    $pr = new Product();
    if ($_POST) {
        $pr->rowUpdate();
    } else {
        $pr->row($uris[3]);
    }

} else if (strpos($uri, '/product') === 0) {

    $pr = new Product();
    if ($_POST) {
        $pr->listUpdate();
    } else {
        $pr->list($uris[2]);
    }

} else if (strpos($uri, '/board') === 0) {

    switch($uris[2]) {
        case 'notice':
            $bo = new Notice();
            break;
        case 'event':
            $bo = new Event();
            break;
        case 'qna':
            $bo = new Qna();
            break;
        case 'faq':
            $bo = new Faq();
            break;
    }

    if (strpos($uri, 'row') !== false) {
        if ($_POST) {
            $bo->rowUpdate();
        } else {
            $bo->row($uris[4]);
        }
    } else {
        if ($_POST) {
            $bo->listUpdate();
        } else {
            $bo->list();
        }
    }

} else if (strpos($uri, '/push') === 0) {

    $pu = new Push();

    if (strpos($uri, 'row') !== false) {
        if ($_POST) {
            $pu->rowUpdate();
        } else {
            $pu->row($uris[3]);
        }
    } else if (strpos($uri, 'send') !== false) {
        $pu->send($uris[3]);
    } else {
        if ($_POST) {
            $pu->listUpdate();
        } else {
            $pu->list();
        }
    }

} else {
    location('/dashboard');
}
