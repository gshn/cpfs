<?php
global $user;
require VIEW.'/template/html-head.php';
?>
<div id="wrap" class="top">
    <header id="header" data-aos="slide-down" data-aos-duration="500">
        <div class="container relative">
            <h1 class="logo" data-aos="fade-in" data-aos-delay="500" data-aos-duration="1500">
                <a href="/">
                    <img class="img-fluid" src="/img/main/donggomool_logo.png" alt="동고물 - 우리동네 고물상">
                </a>
            </h1>
            <nav class="tnb">
                <ul class="tnb-list">
                    <li>
                        <a href="/promotion">
                            <i class="fa fa-fw fa-gift text-success"></i>
                            <span class="text-success font-weight-extra-bold">동고물 프로모션</span>
                        </a>
                    </li>
                    <?php if ($is_guest) { ?>
                    <li>
                        <a href="/login">
                            <i class="fa fa-fw fa-power-off text-primary"></i>
                            <span class="text-dark">로그인</span>
                        </a>
                    </li>
                    <li>
                        <a href="/register">
                            <i class="fa fa-fw fa-user text-gray"></i>
                            <span class="text-dark">회원가입</span>
                        </a>
                    </li>
                    <?php } else { ?>
                    <li>
                        <a href="/logout">
                            <i class="fa fa-fw fa-power-off text-gray"></i>
                            <span class="text-dark">로그아웃</span>
                        </a>
                    </li>
                    <li>
                        <a href="/mypage">
                            <i class="fa fa-fw fa-user text-primary"></i>
                            <span class="text-dark"><?php echo $user['nickname']?>님</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
            <nav class="gnb">
                <ul class="gnb-list">
                    <li>
                        <a href="/collect">
                            거래하기
                            <i class="fa fa-plus d-lg-none"></i>
                        </a>
                        <ul class="gnb-sub-list">
                            <li><a href="/recommend">추천</a></li>
                            <li><a href="/collect">수거</a></li>
                            <li><a href="/demolition">철거</a></li>
                            <li><a href="/clean">정리대행</a></li>
                            <li><a href="/donate">기부</a></li>
                            <li><a href="/used">중고</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/share">
                            공유경제
                            <i class="fa fa-plus d-lg-none"></i>
                        </a>
                        <ul class="gnb-sub-list">
                            <li><a href="/sell">자원판매</a></li>
                            <li><a href="/share">공유경제</a></li>
                            <li><a href="/rental">장비대여</a></li>
                            <li><a href="/job">일자리</a></li>
                            <li><a href="/company">기업홍보</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/notice">
                            커뮤니티
                            <i class="fa fa-plus d-lg-none"></i>
                        </a>
                        <ul class="gnb-sub-list">
                            <li><a href="/notice">공지사항</a></li>
                            <li><a href="/board/suda">수다방</a></li>
                            <li><a href="/board/info">정보공유</a></li>
                            <li><a href="/faq">문의하기</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/mypage">
                            내 페이지
                            <i class="fa fa-plus d-lg-none"></i>
                        </a>
                        <ul class="gnb-sub-list">
                            <li><a href="/myinfo">내 정보</a></li>
                            <li><a href="/mylist">거래내역</a></li>
                            <li><a href="/wishlist">찜한내역</a></li>
                            <li><a href="/review">후기</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <form class="search-form form-inline" action="/search">
                <label class="search-label">
                    <input class="form-control search-input" type="search" placeholder="검색어를 입력하세요" value="" required>
                    <button type="submit"><i class="fa fa-search text-primary"></i></button>
                </label>
            </form>
            <a class="gnb-trigger d-lg-none" href="#">
                <span></span>
            </a>
        </div>
    </header>
    <main id="container" class="<?php echo defined('MAIN') ? 'main' : ''?>">
