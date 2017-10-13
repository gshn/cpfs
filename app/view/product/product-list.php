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
<div class="container-fluid">
    <div class="row main-row">
        <div class="col-xs-12 col">
            <section class="bg-white">
                <div class="section-padding">
                    <h6>
                        <i class="fa fa-fw fa-cubes"></i>
                        상품
                        <small>
                            <?php echo $uris[2] ? $uris[2] : ''?>
                            <?php echo $stx ? '검색어 '. $stx : ''?>
                            <?php echo number_format($total_count)?>건
                        </small>
                    </h6>
                </div>
                <div class="bg-offwhite section-padding relative">
                    <div class="btn-group">
                        <a class="btn btn-<?php echo $uris[2] === null ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>">모든</a>
                        <a class="btn btn-<?php echo $uris[2] === 'used' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/used">중고물품</a>
                        <a class="btn btn-<?php echo $uris[2] === 'donate' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/donate">기부</a>
                        <a class="btn btn-<?php echo $uris[2] === 'collect' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/collect">수거</a>
                        <a class="btn btn-<?php echo $uris[2] === 'demolition' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/demolition">철거</a>
                        <a class="btn btn-<?php echo $uris[2] === 'res_sell' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/res_sell">자원판매</a>
                        <a class="btn btn-<?php echo $uris[2] === 'res_share_biz' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/res_share_biz">공유경제</a>
                        <a class="btn btn-<?php echo $uris[2] === 'hr_get_job' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/hr_get_job">구직</a>
                        <a class="btn btn-<?php echo $uris[2] === 'hr_get_human' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/hr_get_human">구인</a>
                        <a class="btn btn-<?php echo $uris[2] === 'rental_sell' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/rental_sell">장비대여</a>
                        <a class="btn btn-<?php echo $uris[2] === 'rental_buy' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/rental_buy">장비구함</a>
                        <a class="btn btn-<?php echo $uris[2] === 'arrange_normal' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/arrange_normal">일반정리</a>
                        <a class="btn btn-<?php echo $uris[2] === 'arrange_relic' ? 'primary' : 'default'?>" href="/<?php echo $uris[1]?>/arrange_relic">유품정리</a>
                    </div>
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
                            <?php echo $this->getOrderBy('제목', 'pro.title')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('가격', 'pro.price')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('상태', 'pro.this_status')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('판매자', 'usr.login_id')?>
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
                                    <small class="text-danger">삭제</small>
                                <?php } else { ?>
                                    <input type="checkbox" name="ids[]" class="check-list" value="<?php echo $row['id']?>">
                                <?php } ?>
                            </label>
                        </td>
                        <td>
                            <small class="text-muted"><?php echo $this->getProd($row)?></small>
                            <?php echo $row['title']?>
                        </td>
                        <td>
                            <?php echo number_format($row['price'])?>
                        </td>
                        <td>
                            <?php echo $row['this_status']?>
                        </td>
                        <td>
                            <?php echo $row['login_id']?>(<?php echo $row['nickname']?>)
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
