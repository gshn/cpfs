<section class="login container">
    <div class="row">
        <div class="offset-lg-4 col-lg-4 col-12">
            <h1 class="weight-700 m-0"><a href="/">동고물</a> <span class="weight-400"><?php echo $package?>회원가입</span></h1>
            <hr class="hr login-hr mb-4">
            <h6 class="text-right">
                <?php if ($package === '') { ?>
                혹시 기업회원이세요? <a href="/register/company">기업회원가입</a>
                <?php } else { ?>
                혹시 일반회원이세요? <a href="/register">회원가입</a>
                <?php } ?>
            </h6>
        </div>
        <form class="offset-lg-4 col-lg-4 col-12" method="post">
            <fieldset>
                <legend class="text-primary">필수항목</legend>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="text" name="login_id" required maxlength="20" placeholder="아이디를 입력하세요.">
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control form-control-lg" type="password" id="login_pw" name="login_pw" required placeholder="비밀번호를 입력하세요.">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary password-toggle" type="button"><i class="fa fa-eye"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="email" name="email" required maxlength="100" placeholder="이메일 주소를 입력하세요.">
                    <small class="form-text text-muted">비밀번호 분실 시 이용되므로 실제 사용하는 이메일 주소를 입력해 주세요.</small>
                </div>
            </fieldset>
            <fieldset class="mt-4">
                <legend>선택항목</legend>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="text" name="nickname" maxlength="6" placeholder="닉네임을 입력하세요. (최대 6글자)">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="text" name="hp" minlength="10" maxlength="13" placeholder="휴대전화번호를 입력하세요.">
                </div>
                <div class="form-group">
                    <input id="postzip" type="hidden" name="postzip">
                    <div class="input-group">
                        <input class="form-control form-control-lg" id="addr_01" type="text" name="addr_01" maxlength="150" readonly placeholder="주소를 검색하세요.">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" type="button" onclick="sample4_execDaumPostcode()"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <input id="addr_02" class="form-control form-control-lg" type="text" name="addr_02" maxlength="150" placeholder="상세주소를 입력하세요.">
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" required>
                        본인은 14세 이상이며, <a href="/html/term.html" target="_blank">이용약관</a> 및 <a href="/html/privacy.html" target="_blank">개인정보 취급방침</a>, <a href="/html/guideline.html" target="_blank">게시물 운영지침</a>을 모두 확인하였으며 이에 동의합니다.
                    </label>
                </div>
                <div class="form-group my-4">
                    <button class="btn btn-<?php echo $package === '' ? 'primary' : 'secondary'?> btn-lg btn-block" type="submit"><?php echo $package?>회원가입</button>
                </div>
                <small>이미 가입을 하셨나요? <a href="/login<?php echo $uris[2] ? '/'.$uris[2] : ''?>"><?php echo $package?>로그인</a></small>
            </fieldset>
        </form>
        <div class="col-12">
            <p class="py-4 text-center">&copy; 2017-2018 COZMO Co, Ltd. All rights reserved.</p>
        </div>
    </div>
</section>
