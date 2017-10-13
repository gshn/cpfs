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
                <input type="hidden" name="id" value="<?php echo $id?>">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']?>">
                <input type="hidden" name="parent_id" value="<?php echo $row['parent_id']?>">
                <input type="hidden" name="create_date" value="<?php echo $row['create_date']?>">
                <input type="hidden" name="tblname" value="<?php echo $uris[2]?>">
                <?php echo $this->getQueryStringInput() ?>
                <div class="section-padding">
                    <h6>
                        <i class="fa fa-fw fa-pencil"></i>
                        게시글 <?php echo $id === null ? '작성' : '수정'?>
                    </h6>
                </div>
                <div class="bg-offwhite section-padding relative">
                    <p>정보를 입력해주세요.</p>
                </div>
                <div class="row section-padding main-row">
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="title">ㅇ 제목</label>
                        <input type="text" name="title" id="title" class="form-control input-sm" value="<?php echo $row['title']?>" placeholder="ㅇ 제목">
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="login_id">ㅇ 작성자</label>
                        <input type="text" id="login_id" class="form-control input-sm" value="<?php echo $row['login_id']?>(<?php echo $row['nickname']?>)" readonly placeholder="ㅇ 제목">
                    </div>
                    <div class="col-sm-12 col-xs-12 col">
                        <label for="contents">ㅇ 질문내용</label>
                        <textarea name="contents" id="contents" class="form-control" placeholder="ㅇ 질문내용" rows="10"><?php echo $row['contents']?></textarea>
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="res_title">ㅇ 답변제목</label>
                        <input type="text" name="res_title" id="res_title" class="form-control input-sm" value="<?php echo $row['res_title']?>" placeholder="ㅇ 답변제목">
                    </div>
                    <div class="col-sm-6 col-xs-12 col">
                        <label for="res_username">ㅇ 답변작성자</label>
                        <input type="text" name="res_username" id="res_username" class="form-control input-sm" value="<?php echo $row['res_username']?>" placeholder="ㅇ 답변작성자">
                    </div>
                    <div class="col-sm-12 col-xs-12 col">
                        <label for="res_contents">ㅇ 답변내용</label>
                        <textarea name="res_contents" id="res_contents" class="form-control" placeholder="ㅇ 답변내용" rows="10"><?php echo $row['res_contents']?></textarea>
                    </div>
                </div>
                <div class="bg-offwhite section-padding clearfix">
                    <div class="pull-left btn-group">
                        <a href="/<?php echo $uris[1]?>/<?php echo $uris[2]?>?<?php echo $qstr?>" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-ban"></i> 취소</a>
                    </div>
                    <div class="pull-right btn-group">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-fw fa-check-square-o"></i> 입력완료</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
