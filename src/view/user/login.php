<?php
/**
 * User login.php
 * 
 * PHP Version 7
 * 
 * @category View
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
?>
<main id="container" class="container">
    <section class="row">
        <div class="offset-lg-4 col-lg-4 offset-1 col-10 login-col rounded border border-secondary mt-5 p-5">
                <h1 class="font-weight-900">Login</h1>
                <hr class="hr login-hr mb-4">
                <h5 class="mb-4">아이디와 비밀번호를 입력해주세요.</h5>
                <form method="post">
                    <input type="hidden" name="url" value="<?php echo $url?>">
                    <div class="form-group">
                        <input class="form-control form-control-lg" type="email" name="email" tabindex="1" placeholder="아이디를 입력해주세요." required value="super@czm.kr">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" type="password" name="password" tabindex="2" placeholder="비밀번호를 입력해주세요." required value="1234">
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="auto" tabindex="3" value="1">
                            자동 로그인
                        </label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block btn-lg" tabindex="4" type="submit">로그인</button>
                    </div>
                    <div class="form-text">
                        <small>허가된 사용자만 접근 할 수 있습니다.</small>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <p class="py-4 text-center">&copy; 2018 CPFS Co, Ltd. All rights reserved.</p>
            </div>
    </section>
</main>
