<?php
require VIEW.'/template/html-head.php';
global $uris, $uri;
?>
<div id="wrap">
    <nav id="tnb">
        <h1 class="logo pull-left">
            <a href="/">
                <span class="fullname">
                    동고물<span class="weight-100">Admin</span>
                </span>
                <span class="symbol">
                    GM
                </span>
            </a>
        </h1>
        <ul class="tnb-list pull-left">
            <li>
                <a href="javascript:;" class="navToggle">
                    <i class="fa fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="tnb-list pull-right">
            <li>
                <a href="/logout" class="tnb-user">
                    <i class="fa fa-fw fa-power-off"></i>
                    <span>로그아웃</span>
                </a>
            </li>
        </ul>
    </nav>
    <aside id="lnb">
        <ul class="lnb-list">
            <li>
                <a href="/dashboard" class="<?php echo $uris[1] === 'dashboard' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span>대시보드</span>
                </a>
            </li>
            <li>
                <a href="/user" class="<?php echo $uris[1] === 'user' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-user"></i>
                    <span>사용자</span>
                </a>
            </li>
            <li>
                <a href="/product" class="<?php echo $uris[1] === 'product' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-cubes"></i>
                    <span>상품</span>
                </a>
            </li>
            <li>
                <a href="/push" class="<?php echo $uris[1] === 'push' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-comments-o"></i>
                    <span>푸시</span>
                </a>
            </li>
            <li>
                <a href="/board/notice" class="<?php echo $uris[2] === 'notice' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-exclamation"></i>
                    <span>공지사항</span>
                </a>
            </li>
            <li>
                <a href="/board/event" class="<?php echo $uris[2] === 'event' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-calendar-o"></i>
                    <span>이벤트</span>
                </a>
            </li>
            <li>
                <a href="/board/qna" class="<?php echo $uris[2] === 'qna' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-question-circle-o"></i>
                    <span>Q&amp;A</span>
                </a>
            </li>
            <li>
                <a href="/board/faq" class="<?php echo $uris[2] === 'faq' ? 'active' : ''?>">
                    <i class="fa fa-fw fa-question"></i>
                    <span>FAQ</span>
                </a>
            </li>
        </ul>
    </aside>
    <div id="container">
