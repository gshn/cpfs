<?php
/**
 * Template header.php
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
<header id="header" data-aos="slide-down" data-aos-duration="500">
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand font-weight-100" href="/">
                <?php echo $cf['head']['title']?><strong class="font-weight-900">ADMIN</strong>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor03">
                <ul class="navbar-nav mr-auto">
                    <?php if ($cf['is']['user'] === true) { ?>
                    <li class="nav-item<?php echo URIS[1] === 'user' ? ' active' : ''?>">
                        <a class="nav-link" href="/user">회원</a>
                    </li>
                    <?php } ?>
                    <li class="nav-item<?php echo URIS[1] === 'notice' ? ' active' : ''?>">
                        <a class="nav-link" href="/notice">공지사항</a>
                    </li>
                </ul>
                <div class="form-inline my-2 my-lg-0 btn-group">
                    <?php if ($cf['is']['user'] === true) { ?>
                    <a href="/user/row/<?php echo $cf['user']['id']?>" class="btn btn-secondary my-2 my-sm-0">정보수정</a>
                    <a href="/logout" class="btn btn-secondary my-2 my-sm-0">로그아웃</a>
                    <?php } else { ?>
                    <a href="/join" class="btn btn-primary my-2 my-sm-0">회원가입</a>
                    <a href="/login" class="btn btn-secondary my-2 my-sm-0">로그인</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
</header>
