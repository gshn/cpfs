<header id="page-header">
    <h5 class="title"><?php echo ucfirst($uris[1])?></h5>
    <ul class="category">
        <li class="slide-bar">
            <a href="/">Home</a>
        </li>
        <li>
            <a href="/<?php echo $uris[1]?>"><?php echo ucfirst($uris[1])?></a>
        </li>
    </ul>
</header>
<div id="cert" class="container-fluid">
    <div class="row main-row">
        <div class="col-xs-12 col">
            <section class="bg-white">
                <div class="section-padding">
                    <h6>
                        <i class="fa fa-fw fa-user"></i>
                        사용자
                        <small>
                            <?php echo $stx ? '검색어 '. $stx : ''?>
                            <?php echo number_format($total_count)?>건
                        </small>
                    </h6>
                </div>
                <div class="bg-offwhite section-padding relative">
                    <p>사용자 리스트</p>
                </div>
                <form method="post">
                <input type="hidden" name="req" id="req" value="">
                <?php echo $this->getQueryStringInput() ?>
                <div class="table-responsive">
                    <table class="table table-hover table-custom">
                    <tr>
                        <th class="text-center">
                            <label>
                                <input type="checkbox" class="check-all">
                            </label>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('아이디', 'login_id')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('이메일', 'email')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('휴대전화', 'hp')?>
                        </th>
                        <th>
                            <a href="#">관리</a>
                        </th>
                    </tr>
                    <?php foreach($list as $row) { ?>
                    <tr>
                        <td class="text-center">
                            <label>
                                <?php if ($row['hidden'] === 'y') { ?>
                                    <small class="text-danger">탈퇴</small>
                                <?php } else { ?>
                                    <input type="checkbox" name="ids[]" class="check-list" value="<?php echo $row['id']?>">
                                <?php } ?>
                            </label>
                        </td>
                        <td>
                            <?php echo $row['login_id']?>(<?php echo $row['nickname']?>)
                        </td>
                        <td>
                            <?php echo $row['email']?>
                        </td>
                        <td>
                            <?php echo get_hp_number($row['hp'])?>
                        </td>
                        <td>
                            <a href="/<?php echo $uris[1]?>/row/<?php echo $row['id']?>?<?php echo $qstr?>">수정</a>
                        </td>
                    </tr>
                    <?php }
                    if ($total_count === 0) { ?>
                    <tr>
                        <td colspan="8" class="empty-td">등록된 데이터가 없습니다.</td>
                    </tr>
                    <?php } ?>
                    </table>
                </div>
                <div class="bg-offwhite section-padding clearfix">
                    <div class="pull-left btn-group">
                        <button type="button" class="btn btn-sm btn-danger list-form-submit" value="삭제"><i class="fa fa-fw fa-trash-o"></i> 선택삭제</button>
                    </div>
                    <div class="pull-right btn-group">
                        <a href="/excel/<?php echo $uris[1]?>" class="btn btn-sm btn-success" target="_blank"><i class="fa fa-fw fa-table"></i> EXCEL 내보내기</a>
                    </div>
                </div>
                </form>
                <div class="list-form-grid">
                    <form class="list-form form-inline">
                        <div class="input-group">
                            <input class="form-control input-sm" type="search" name="stx" required placeholder="검색어" value="<?php echo $stx?>">
                            <div class="input-group-btn">
                                <?php if ($stx) { ?>
                                <a href="<?php echo $uri?>" class="btn btn-sm btn-default"><i class="fa fa-fw fa-list"></i></a>
                                <?php } ?>
                                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <nav class="list-paging">
                        <?php echo $paging?>
                    </nav>
                </div>
            </section>
        </div>
    </div>
</div>
