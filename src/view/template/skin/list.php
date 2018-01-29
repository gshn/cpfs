<main id="container">
    <section class="container my-3 p-5 bg-white border rounded">
        <hgroup class="clearfix my-3">
            <h4 class="d-inline-block float-left">
                <?php echo $table_text?>
            </h4>
            <form class="d-inline-block float-right form-inline">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            <?php echo empty($stx) ? '총' : '검색결과'?>
                            <?php echo $count?>
                            건
                        </span>
                    </span>
                    <input class="form-control" type="search" name="stx" value="<?php echo $stx?>" placeholder="검색어 입력" minlength="2" required>
                    <span class="input-group-append">
                        <?php if (!empty($stx)) { ?>
                        <a href="<?php echo URI?>" class="btn btn-secondary">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            <span class="sr-only">전체목록</span>
                        </a>
                        <?php } ?>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <span class="sr-only">검색</span>
                        </button>
                    </span>
                </div>
            </form>
        </hgroup>
        <form class="table-responsive" method="post">
            <?php echo $inputs?>
            <table class="table table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">
                            <input class="list-check-all" type="checkbox" value="">
                        </th>
                        <?php foreach ($cols as $col) { ?>
                        <th>
                            <?php echo $col?>
                        </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $row) { ?>
                    <tr>
                        <th class="text-center">
                            <input class="list-check" type="checkbox" name="ids[]" value="<?php echo $row['id']?>">
                        </th>
                        <?php foreach ($cols as $key => $value) { ?>
                        <td>
                            <a href="/<?php echo URIS[1]?>/row/<?php echo $row['id']?>?<?php echo $qstr?>">
                                <?php echo $row[$key]?>
                            </a>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php }
                    if (count($list) === 0) { ?>
                    <tr>
                        <td class="bg-light text-center p-5" colspan="<?php echo count($cols) + 1?>">표시할 데이터가 없습니다.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="clearfix">
                <div class="btn-group float-left">
                    <button class="btn btn-danger list-form-submit" type="button" value="삭제">
                        선택삭제
                    </button>
                </div>
                <div class="btn-group float-right">
                    <a class="btn btn-primary" href="/<?php echo URIS[1]?>/row">
                        등록
                    </a>
                </div>
            </div>
            <nav class="mt-4" aria-label="navigation">
                <?php echo $paging?>
            </nav>
        </form>
    </section>
</main>
