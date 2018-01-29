<header id="header" data-aos="slide-down" data-aos-duration="500">
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand" href="/user">CPFS<strong class="weight-900">ADMIN</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor03">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item<?php echo defined('USER') ? ' active' : ''?>">
                        <a class="nav-link" href="/user">회원</a>
                    </li>
                    <li class="nav-item<?php echo defined('NOTICE') ? ' active' : ''?>">
                        <a class="nav-link" href="/notice">공지사항</a>
                    </li>
                </ul>
                <div class="form-inline my-2 my-lg-0">
                    <?php if ($cf['is']['user'] === TRUE) { ?>
                    <a href="/logout" class="btn btn-primary my-2 my-sm-0">로그아웃</a>
                    <?php } else { ?>
                    <a href="/login" class="btn btn-primary my-2 my-sm-0">로그인</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
</header>
