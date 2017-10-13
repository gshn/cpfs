<header id="page-header">
    <h5 class="title"><?php echo ucfirst($uris[2])?></h5>
    <ul class="category">
        <li class="slide-bar">
            <a href="/">Home</a>
        </li>
        <li>
            <a href="/<?php echo $uris[1]?>/<?php echo $uris[2]?>"><?php echo ucfirst($uris[2])?></a>
        </li>
    </ul>
</header>
<div class="container-fluid">
    <div class="row main-row">
        <div class="col-xs-12 col">
            <section class="bg-white">
                <div class="section-padding">
                    <h6>
                        <i class="fa fa-fw fa-question-circle-o"></i>
                        <?php echo strtoupper($uris[2])?>
                        <small>
                            <?php echo $stx ? '검색어 '. $stx : ''?>
                            <?php echo number_format($total_count)?>건
                        </small>
                    </h6>
                </div>
                <div class="bg-offwhite section-padding relative">
                    <?php echo ucfirst($uris[2])?> 리스트
                </div>
                <form method="post">
                <input type="hidden" name="req" id="req" value="">
                <input type="hidden" name="tblname" value="<?php echo $uris[2]?>">
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
                            <?php echo $this->getOrderBy('#', 'qna.id')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('제목', 'qna.title')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('작성자', 'usr.login_id')?>
                        </th>
                        <th>
                            <?php echo $this->getOrderBy('작성일', 'create_date')?>
                        </th>
                        <th>
                            <a href="#">관리</a>
                        </th>
                    </tr>
                    <?php foreach($list as $row) { ?>
                    <tr>
                        <td class="text-center">
                            <label>
                                <input type="checkbox" name="ids[]" class="check-list" value="<?php echo $row['id']?>">
                            </label>
                        </td>
                        <td>
                            <?php echo $row['id']?>
                        </td>
                        <td>
                            <?php echo $row['title']?> <?php echo $row['reply_count'] > 0 ? '<small class="text-danger">[추가질문 '.$row['reply_count'].']</small>' : ''?>
                        </td>
                        <td>
                            <?php echo $row['login_id']?>(<?php echo $row['nickname']?>)
                        </td>
                        <td>
                            <?php echo $row['create_date']?>
                        </td>
                        <td>
                            <a href="/<?php echo $uris[1]?>/<?php echo $uris['2']?>/row/<?php echo $row['id']?>?<?php echo $qstr?>">답변</a>
                        </td>
                    </tr>
                    <?php foreach($row['reply'] as $reply) { ?>
                    <tr class="bg-gray">
                        <td class="text-center">
                            <label>
                                <input type="checkbox" name="ids[]" class="check-list" value="<?php echo $reply['id']?>">
                            </label>
                        </td>
                        <td>
                            <small class="text-muted">ㄴ</small>
                        </td>
                        <td>
                            <?php echo $reply['title']?>
                        </td>
                        <td>
                            <?php echo $reply['login_id']?>(<?php echo $reply['nickname']?>)
                        </td>
                        <td>
                            <?php echo $reply['create_date']?>
                        </td>
                        <td>
                            <a href="/<?php echo $uris[1]?>/<?php echo $uris['2']?>/row/<?php echo $reply['id']?>?<?php echo $qstr?>">답변</a>
                        </td>
                    </tr>
                    <?php } ?>
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
