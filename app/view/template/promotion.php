<section class="app-down relative bg-photo" data-aos="slide-right" data-aos-duration="1500">
    <div class="container relative">
        <div class="app-down-text" data-aos="fade-left" data-aos-delay="1400" data-aos-duration="1500">
            <h4>내 손안의 1등 고물상, 동고물에서</h4>
            <h1 class="weight-700">수거부터 판매까지<br><span class="text-primary">한방에</span> 해결하세요!</h1>
            <hr class="hr bg-primary">
            <p>아직도 번거롭게 따로따로 알아보시나요?<br>이제 고민하지말고 한 곳에서 모두 해결해보세요!</p>
            <span><a class="btn btn-success btn-lg" href="https://play.google.com/store/apps/details?id=com.cozmo.gomool.normar"><i class="fa fa-fw fa-android"></i> Android</a></span>
            <span class="ml-3"><a class="btn btn-primary btn-lg" href="">&nbsp;<i class="fa fa-fw fa-apple"></i> iPhone&nbsp;&nbsp;</a></span>
        </div>
    </div>
</section>
<section class="feature text-center bg-light py-6">
    <hgroup>
        <h1 class="weight-700">나에게 <span class="text-primary">최적화</span>된 고물상</h1>
        <h5>언제 어디서나, 누구든지 쉽고 빠른 거래가 가능합니다.</h5>
    </hgroup>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12" data-aos="fade-up" data-aos-duration="1500">
                <div class="py-4">
                    <img class="img-fluid" src="/img/promotion/sec2_icon1.png" alt="icon">
                </div>
                <h3 class="weight-700">자원거래도 스마트하게</h3>
                <p>직접 찾아가지 않고도<br>거래를 할 수 있어 편리합니다.</p>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1500">
                <div class="py-4">
                    <img class="img-fluid" src="/img/promotion/sec2_icon2.png" alt="icon">
                </div>
                <h3 class="weight-700">용량과 가격을 정확하게</h3>
                <p>제시된 가격에서 조정요청을 하거나<br>등록자원의 거래량을 수정 할 수 있습니다.</p>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12" data-aos="fade-up" data-aos-delay="400" data-aos-duration="1500">
                <div class="py-4">
                    <img class="img-fluid" src="/img/promotion/sec2_icon3.png" alt="icon">
                </div>
                <h3 class="weight-700">관심자원을 쉽고 빠르게</h3>
                <p>똑똑한 알림으로<br>자원매입에 활력이 생깁니다.</p>
            </div>
        </div>
    </div>
</section>
<section class="local-to-world bg-photo bg-dark text-white py-6">
    <div class="container">
        <h1 class="weight-300" data-aos="fade-up" data-aos-duration="1500">이제는<br>지역 거래에서</br>전국 거래로!</h1>
        <p class="py-3 weight-300" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1500">동고물은 순환자원거래 활성화를 위한 서비스입니다.<br>언제, 어디서나 보유한 자원과 상품을 등록하고 편의 기능을 통해 간편하게 거래할 수 있습니다.</p>
        <span data-aos="fade-in" data-aos-delay="700" data-aos-duration="1500"><img class="img-fluid" src="/img/promotion/sec3_phone1.png" alt="동고물 기업용 앱"></span>
        <span class="ml-4" data-aos="fade-in" data-aos-delay="700" data-aos-duration="1500"><img class="img-fluid" src="/img/promotion/sec3_phone2.png" alt="동고물 일반용 앱"></span>
    </diV>
</section>
<section class="recommend bg-secondary py-6">
    <div class="container">
        <hgroup class="text-center text-white">
            <h1 class="weight-300" data-aos="fade-up" data-aos-duration="1500">오늘의 추천상품</h1>
            <h5 class="weight-300 py-2" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1500">기업에 필요한 자원들을 비교하여 합리적으로 판매 및 매입하세요!</h5>
        </hgroup>
        <div class="owl-carousel owl-theme recommend-list pt-4" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1500">
        <?php foreach($list as $row) { ?>
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
    </div>
</section>
<section class="testimony py-6">
    <hgroup>
        <h1 class="text-center" data-aos="fade-up" data-aos-duration="1500">이용사례</h1>
        <h5 class="text-center py-2" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1500">우리동네 1등 고물상 동고물을 이용한 회원들의 이야기</h5>
    </hgroup>
    <div class="owl-carousel owl-theme testimony-list pt-4" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1500">
    <?php for($i = 1; $i <= 5; $i += 1) { ?>
        <div class="item">
            <article class="clearfix testimony-card radius py-5 px-3 bg-white">
                <div class="testimony-image text-center">
                    <img class="img-fluid img-radius" src="/img/promotion/sec5_img.png" alt="user 아바타">
                </div>
                <div class="testimony-text pl-3">
                    <h4 class="weight-700">나는요 <span class="text-primary">iam***</span></h4>
                    <h5 class="text-gray">KSA자원 대표</h5>
                    <p class="py-2">이제까지 동고물을 몰랐던 시절을 생각하면 정말 암울했습니다. 한정된 지역에서만 자원을 수집하고, 또 팔기 위해 여러 업체를 한곳한곳 찾아다녀야만 했고, 어렵게 성사시킨 거래가 사기였었죠.</p>
                </div>
            </article>
        </div>
    <?php } ?>
    </div>
</section>
<section class="share py-6 bg-dark bg-photo-parallax">
    <div class="container">
        <hgroup class="text-white">
            <p class="mb-2" data-aos="fade-up" data-aos-duration="1500">협력적 판매의 장</p>
            <h1 class="weight-700" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1500">공유경제</h1>
            <hr class="hr bg-white my-4" data-aos="zoom-in" data-aos-delay="1000" data-aos-duration="1500">
            <h5 class="weight-300 share-text" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1500">바쁜 일상 속 거래전화를 놓치거나 무작정 연락을 기다리지 않아도 괜찮습니다. 필요한 정보는 사진으로, 바쁜 일상 속 거래처와의 대화를 한곳에서 관리 할 수 있습니다. 또한 필요한 인력을 구하거나 일자리를 구하고 필요한 장비를 대여할 수도 있는 곳, 바로 <strong>동고물</strong> 입니다.</h5>
            <a class="btn btn-outline-light btn-lg mt-4" href="/share" data-aos="zoom-in" data-aos-delay="700" data-aos-duration="1500"><span class="weight-300">공유경제 바로가기</span></a>
        </hgroup>
    </div>
</section>
<section class="partner bg-photo py-5"></section>
