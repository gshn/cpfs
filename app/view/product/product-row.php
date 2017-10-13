<header id="page-header">
    <h5 class="title">Modify</h5>
    <ul class="category">
        <li class="slide-bar">
            <a href="/">Home</a>
        </li>
        <li class="slide-bar">
            <a href="/<?php echo $uris[1]?>?<?php echo $qstr?>"><?php echo ucfirst($uris[1])?></a>
        </li>
        <li>
            <a href="/<?php echo $uris[1]?>/row/<?php echo $id?>">Modify</a>
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
                        <i class="fa fa-fw fa-cubes"></i>
                        판매자 <?php echo $row['login_id']?>(<?php echo $row['nickname']?>)님
                    </h6>
                </div>
                <div class="bg-offwhite section-padding relative">
                    <p>정보를 입력해주세요.</p>
                </div>
                <div class="row section-padding main-row">
                    <?php foreach($cols as $col) { ?>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="<?php echo $col['name']?>"><?php echo $col['comment']?></label>
                        <input type="text" name="<?php echo $col['name']?>" id="<?php echo $col['name']?>" class="form-control input-sm" value="<?php echo $row[$col['name']]?>" placeholder="<?php echo $col['comment']?>">
                    </div>
                    <?php } ?>
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
