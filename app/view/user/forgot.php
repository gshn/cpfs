<section class="login container">
    <div class="row">
        <div class="offset-lg-4 col-lg-4 col-12">
            <h1 class="weight-700 m-0"><a href="/">동고물</a> <span class="weight-400"><?php echo $package?>계정찾기</span></h1>
            <hr class="hr login-hr mb-4">
            <h6 class="text-right">
                <?php if ($package === '') { ?>
                혹시 기업회원이세요? <a href="/forgot/company">기업계정찾기</a>
                <?php } else { ?>
                혹시 일반회원이세요? <a href="/forgot">계정찾기</a>
                <?php } ?>
            </h6>
        </div>
        <form class="offset-lg-4 col-lg-4 col-12" method="post">
            <fieldset>
                <legend>이메일로 찾기</legend>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="email" name="email" required maxlength="100" placeholder="이메일을 입력하세요.">
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" required>
                        로봇이 아닙니다.
                    </label>
                </div>
                <div class="form-group my-4">
                    <button class="btn btn-<?php echo $package === '' ? 'primary' : 'secondary'?> btn-lg btn-block" type="submit">계정 정보를 이메일로 전송</button>
                </div>
                <small>계정정보를 아시나요? <a href="/login<?php echo $uris[2] ? '/'.$uris[2] : ''?>"><?php echo $package?>로그인</a></small>
            </fieldset>
        </form>
        <div class="col-12">
            <p class="py-4 text-center">&copy; 2017-2018 COZMO Co, Ltd. All rights reserved.</p>
        </div>
    </div>
</section>
