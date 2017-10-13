<header id="page-header">
    <h5 class="title">Modify</h5>
    <ul class="category">
        <li class="slide-bar">
            <a href="/">Home</a>
        </li>
        <li class="slide-bar">
            <a href="/user?<?php echo $qstr?>">User</a>
        </li>
        <li>
            <a href="/user/row/<?php echo $id?>">Modify</a>
        </li>
    </ul>
</header>
<div class="container-fluid">
    <div class="row main-row">
        <div class="col-xs-12 col">
            <form class="bg-white" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']?>">
                <?php echo $this->getQueryStringInput() ?>
                <div class="section-padding">
                    <h6>
                        <i class="fa fa-fw fa-comments-o"></i>
                        <?php echo $row['login_id']?>(<?php echo $row['nickname']?>)님 정보
                    </h6>
                </div>
                <div class="bg-offwhite section-padding relative">
                    <p><span class="text-danger">필수</span> 정보를 입력해주세요.</p>
                </div>
                <div class="row section-padding main-row">
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="account_type">계정타입</label>
                        <input type="text" name="account_type" id="account_type" class="form-control input-sm" required value="<?php echo $row['account_type']?>" placeholder="계정타입">
                        <p class="help-block"><sup class="text-danger">필수</sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="login_id">아이디</label>
                        <input type="text" name="login_id" id="login_id" class="form-control input-sm" required value="<?php echo $row['login_id']?>" placeholder="아이디">
                        <p class="help-block"><sup class="text-danger">필수</sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="login_pw">비밀번호 변경</label>
                        <input type="text" name="login_pw" id="login_pw" class="form-control input-sm" value="" placeholder="">
                        <p class="help-block"><sup class="text-danger"> </sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="email">이메일</label>
                        <input type="text" name="email" id="email" class="form-control input-sm" required value="<?php echo $row['email']?>" placeholder="이메일">
                        <p class="help-block"><sup class="text-danger">필수</sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="nickname">닉네임</label>
                        <input type="text" name="nickname" id="nickname" class="form-control input-sm" required value="<?php echo $row['nickname']?>" placeholder="닉네임">
                        <p class="help-block"><sup class="text-danger">필수</sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="hp">휴대전화</label>
                        <input type="text" name="hp" id="hp" class="form-control input-sm" required value="<?php echo get_hp_number($row['hp'])?>" placeholder="휴대전화">
                        <p class="help-block"><sup class="text-danger">필수</sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="postzip">우편번호</label>
                        <input type="text" name="postzip" id="postzip" class="form-control input-sm" value="<?php echo $row['postzip']?>" placeholder="우편번호">
                        <p class="help-block"><sup class="text-danger"> </sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="addr_01">기본주소</label>
                        <input type="text" name="addr_01" id="addr_01" class="form-control input-sm" value="<?php echo $row['addr_01']?>" placeholder="기본주소">
                        <p class="help-block"><sup class="text-danger"> </sup></p>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="addr_02">상세주소</label>
                        <input type="text" name="addr_02" id="addr_02" class="form-control input-sm" value="<?php echo $row['addr_02']?>" placeholder="상세주소">
                        <p class="help-block"><sup class="text-danger"> </sup></p>
                    </div>
                </div>
                <div class="bg-offwhite section-padding clearfix">
                    <div class="pull-left btn-group">
                        <a href="/<?php echo $uris[1]?>?<?php echo $qstr?>" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-ban"></i> 취소</a>
                    </div>
                    <div class="pull-right btn-group">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-fw fa-check-square-o"></i> 입력완료</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
