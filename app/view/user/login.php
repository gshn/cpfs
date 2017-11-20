<div id="login">
    <aside class="bg-photo" style="background-image: url('/img/login-bg-0<?php echo rand(1, 5)?>.jpg')">
        <hgroup class="text-white" style="position: absolute;bottom: 50px; left: 50px;">
                <h1><span class="weight-900 text-danger">Kepco</span> <span class="weight-100">접촉 고장 예방 시스템</span></h1>
                <h2 class="weight-100">Contact Accident Prevention System <strong class="text-primary weight-900">CAPS</strong></h2>
        </hgroup>
    </aside>
    <main class="bg-photo">
        <div class="login-form-div text-center">
            <div class="login-form text-left">
                <form method="post">
                    <h4>Kepco 접촉 고장 예방 시스템</h4>
                    <p>허가 받은 사용자만 접속 할 수 있는 시스템입니다.</p>
                    <div class="label-input">
                        <label for="hp">휴대전화</label>
                        <input type="text" name="hp" class="form-control" id="hp" maxlength="13" required placeholder="휴대전화 번호를 입력해주세요.">
                    </div>
                    <div class="label-input">
                        <label for="password">비밀번호</label>
                        <input type="password" name="password" class="form-control" id="password" required placeholder="비밀번호를 입력해주세요.">
                    </div>
                    <div class="login-check">
                        <p class="pull-left">
                            <label>
                                <input type="checkbox" name="auto" value="1"> 자동로그인
                            </label>
                        </p>
                    </div>
                    <button type="submit" class="btn btn-block btn-primary">로그인</button>
                </form>
            </div>
        </div>
    </main>
    <div class="logo">
        <a href="//home.kepco.co.kr/kepco/main.do" target="_blank"><img src="/img/kepco-logo.png"></a>
    </div>
</div>
