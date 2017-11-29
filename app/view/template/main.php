<section class="app-down relative bg-photo" data-aos="slide-right" data-aos-duration="1500">
    <div class="container relative">
        <div class="app-down-text" data-aos="fade-left" data-aos-delay="1400" data-aos-duration="1500">
            <h4>내 손안의 1등 고물상, 동고물에서</h4>
            <h1 class="weight-700">수거부터 판매까지<br><span class="text-primary">한방에</span> 해결하세요!</h1>
            <hr class="hr bg-primary">
            <p>아직도 번거롭게 따로따로 알아보시나요?<br>이제 고민하지말고 한 곳에서 모두 해결해보세요!</p>
            <span><a class="btn btn-success btn-lg" href="https://play.google.com/store/apps/details?id=com.cozmo.gomool.normar" target="_blank"><i class="fa fa-fw fa-android"></i> Android</a></span>
            <span class="ml-3"><a class="btn btn-primary btn-lg" href="">&nbsp;<i class="fa fa-fw fa-apple"></i> iPhone&nbsp;&nbsp;</a></span>
        </div>
    </div>
</section>

<div class="main-wrap bg-light">
    <div class="container clearfix">
        <nav id="lnb">
            <div class="lnb-btn-wrap">
                <div class="row">
                    <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                        <a class="lnb-btn btn btn-primary btn-lg radius" href="/">
                            <span class="font-weight-bold"><i class="fa fa-fw fa-bolt"></i> 빠른 수거 요청</span>
                        </a>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-6 col-6 pt-lg-2">
                        <a class="lnb-btn btn btn-dark btn-lg radius" href="/">
                            <span class="font-weight-bold"><i class="fa fa-fw fa-cubes"></i> 중고 기부 등록</span>
                        </a>
                    </div>
                </div>
            </div>
            <ul class="lnb-list relative clearfix radius overflow-hidden my-4">
                <?php if ($uri === '/recommend') { ?>
                <li class="lnb-header"><span class="text-muted weight-700">필터 카테고리</span></li>
                <li><a href="/">전체</a></li>
                <li><a href="/">여성의류</a></li>
                <li><a href="/">남성의류</a></li>
                <li><a href="/">패션잡화</a></li>
                <li><a href="/">뷰티·미용</a></li>
                <li><a href="/">문화·도서</a></li>
                <li><a href="/">유아동·출산</a></li>
                <li><a href="/">스포츠·레저</a></li>
                <li><a href="/">취미·애완</a></li>
                <li><a href="/">디지털·가전</a></li>
                <li><a href="/">차량·오토바이</a></li>
                <li><a href="/">생활·가구</a></li>
                <li><a href="/">기타</a></li>
                <?php } else if ($uri === '/') { ?>
                <li class="lnb-header"><span class="text-muted weight-700">거래하기</span></li>
                <li><a href="/">추천</a></li>
                <li><a href="/">수거</a></li>
                <li><a href="/">철거</a></li>
                <li><a href="/">정리대행</a></li>
                <li><a href="/">기부</a></li>
                <li><a href="/">중고</a></li>
                <li class="lnb-header"><span class="text-muted weight-700">공유경제</span></li>
                <li><a href="/">자원판매</a></li>
                <li><a href="/">공유경제</a></li>
                <li><a href="/">장비대여</a></li>
                <li><a href="/">일자리</a></li>
                <li><a href="/">기업홍보</a></li>
                <?php } ?>
                <li class="lnb-header"><span class="text-muted weight-700">커뮤니티</span></li>
                <li><a href="/notice">공지사항</a></li>
                <li><a href="/board/suda">수다방</a></li>
                <li><a href="/board/info">정보공유</a></li>
                <li><a href="/faq">문의하기</a></li>
                <li class="lnb-footer"><a href="#" class="btn btn-block">펼쳐보기 <i class="fa fa-angle-down"></i></a></li>
            </ul>
        </nav>
        <section class="product-section">
            <h3 class="m-0 pb-2 clearfix">
                실시간 수거/철거 요청
                <small class="float-right"><a href="/collect">더보기 +</a></small>
            </h3>
            <div class="clearfix product-row">
            <?php foreach($requests as $row) { ?>
                <div class="item">
                    <article class="thumbnail overflow-hidden radius relative">
                        <a href="<?php echo $row['href']?>/view/<?php echo $row['id']?>">
                            <div class="image">
                                <img class="img-fluid" src="<?php echo $row['thumb_url']?>" alt="<?php echo $row['title']?>">
                            </div>
                            <div class="text bg-white p-3">
                                <p class="text-gray mb-1"><?php echo $row['prod']?> | <?php echo $row['time']?></p>
                                <h5 class="product-title m-0 text-dark"><?php echo $row['title']?></h5>
                                <h5 class="text-primary m-0"><?php echo $row['price']?></h5>
                                <span class="product-address text-gray"><?php echo $row['address']?></span>
                            </div>
                        </a>
                    </article>
                </div>
            <?php } ?>
            </div>
            <h3 class="m-0 mt-4 pb-2 clearfix">
                신뢰가는 기업찾기
                <small class="float-right"><a href="/company">더보기 +</a></small>
            </h3>
            <div class="clearfix product-row">
            <?php foreach($companys as $row) { ?>
                <div class="item">
                    <article class="thumbnail overflow-hidden radius relative">
                        <a href="/company/view/<?php echo $row['id']?>">
                            <div class="image">
                                <img class="img-fluid" src="<?php echo $row['thumb_url']?>" alt="<?php echo $row['name']?>">
                            </div>
                            <div class="text bg-white p-3">
                                <p class="text-gray mb-1"><?php echo $row['cate']?> | <?php echo $row['active_area_01']?></p>
                                <h5 class="mb-1 text-primary"><?php echo $row['name']?></h5>
                                <p class="product-title mb-1"><span class="text-muted"><?php echo $row['contents']?></span></p>
                            </div>
                        </a>
                    </article>
                </div>
            <?php } ?>
            </div>
            <div class="py-4 text-center">
                <a href="/donate/write"><img class="img-fluid" src="/img/main/banner01.jpg" alt="광고배너"></a>
            </div>
            <h3 class="m-0 mt-4 pb-2 clearfix">
                중고/기부 추천 상품
                <small class="float-right"><a href="/used">더보기 +</a></small>
            </h3>
            <div class="clearfix product-row">
            <?php foreach($useds as $row) { ?>
                <div class="item">
                    <article class="thumbnail overflow-hidden radius relative">
                        <a href="<?php echo $row['href']?>/view/<?php echo $row['id']?>">
                            <div class="image">
                                <img class="img-fluid" src="<?php echo $row['thumb_url']?>" alt="<?php echo $row['title']?>">
                            </div>
                            <div class="text bg-white p-3">
                                <p class="text-gray mb-1"><?php echo $row['prod']?> | <?php echo $row['time']?></p>
                                <h5 class="product-title m-0 text-dark"><?php echo $row['title']?></h5>
                                <h5 class="text-primary m-0"><?php echo $row['price']?></h5>
                                <span class="product-address text-gray"><?php echo $row['address']?></span>
                            </div>
                        </a>
                    </article>
                </div>
            <?php } ?>
            </div>
        </section>
    </div>
</div>
