<section class="login container">
    <div class="row">
        <div class="offset-lg-1 col-lg-10 col-12">
            <a href="/" class="logo"><img src="/img/main/donggomool_logo.png" alt="우리동네 고물상 동고물"></a>
            <hr class="hr login-hr mb-4">
            <h6 class="text-right">
                <?php if ($package === '') { ?>
                혹시 기업회원이세요? <a href="/login/company">기업로그인</a>
                <?php } else { ?>
                혹시 일반회원이세요? <a href="/login">로그인</a>
                <?php } ?>
            </h6>
        </div>
        <div class="offset-lg-1 col-lg-4 col-12">
            <h4 class="mb-4">SNS <?php echo $package?> 로그인</h4>
            <a class="sns-login naver-login-btn" href="<?php echo $naver_login_uri?>">네이버 <?php echo $package?> 로그인</a>
            <a class="my-2 sns-login facebook-login-btn" href="<?php echo $facebook_login_uri?>" scope="public_profile,email">페이스북 <?php echo $package?> 로그인</a>
            <a class="sns-login kakao-login-btn" href="<?php echo $kakao_login_uri?>">카카오 <?php echo $package?> 로그인</a>
        </div>
        <div class="col-lg-2 col-12 text-center login-bar py-5"></div>
        <div class="col-lg-4 col-12">
            <h4 class="mb-4">동고물 <?php echo $package?> 로그인</h4>
            <form method="post">
                <div class="form-group">
                    <input class="form-control form-control-lg" type="text" name="login_id" placeholder="아이디를 입력해주세요." required>
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="password" name="login_pw" placeholder="비밀번호를 입력해주세요." required>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="auto" value="1">
                        자동 로그인
                    </label>
                    <small>계정정보를 잊어버리셨어요? <a href="/forgot<?php echo $uris[2] ? '/'.$uris[2] : ''?>">계정 찾기</a></small>
                </div>
                <div class="form-group">
                    <button class="btn btn-<?php echo $package === '' ? 'primary' : 'secondary'?> btn-block btn-lg" type="submit"><?php echo $package?>로그인</button>
                </div>
                <div class="form-check">
                    <small>아직 동고물 계정이 없으신가요? <a href="/register<?php echo $uris[2] ? '/'.$uris[2] : ''?>"><?php echo $package?>회원가입</a></small>
                </div>
            </form>
        </div>
        <div class="col-12">
            <p class="py-4 text-center">&copy; 2017-2018 COZMO Co, Ltd. All rights reserved.</p>
        </div>
    </div>
</section>
