<?php
/**
 * Skin row.php
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
<main id="container">
    <section class="container my-lg-5 my-3 p-lg-5 p-3 bg-white border rounded">
        <hgroup class="clearfix my-3">
            <h4 class="d-inline-block float-left">
                <?php echo $heading?> <?php echo !empty($row['id']) ? '수정' : '등록'?>
            </h4>
        </hgroup>
        <form method="post" enctype="multipart/form-data">
            <?php echo $inputs?>
            <input type="hidden" name="id" value="<?php echo $row['id']?>">
            <table class="table table-bordered">
                <?php foreach ($cols as $key => $value) { ?>
                <tr>
                    <th class="bg-light">
                        <?php echo $key?>
                    </th>
                    <td>
                        <?php if ($value['type'] === 'file') { ?>
                        <div class="input-group">
                            <?php if (!empty($value['value'])) {?>
                            <span class="input-group-addon">
                                <a href="<?php echo $value['value']?>" target="_blank">
                                    <img src="<?php echo $value['value']?>" width="100" height="100" alt="첨부파일">
                                </a>
                            </span>
                            <?php } ?>
                            <input id="<?php echo $value['name']?>" class="form-control" type="<?php echo $value['type']?>" name="<?php echo $value['name']?>" value="<?php echo $value['value']?>" placeholder="<?php echo $value['placeholder']?>"<?php echo $value['attribute']?>>
                        </div>
                        <?php } elseif($value['type'] === 'textarea') { ?>
                        <textarea id="<?php echo $value['name']?>" class="form-control" name="<?php echo $value['name']?>" placeholder="<?php echo $value['placeholder']?>"<?php echo $value['attribute']?>><?php echo $value['value']?></textarea>
                        <?php } else { ?>
                        <input id="<?php echo $value['name']?>" class="form-control" type="<?php echo $value['type']?>" name="<?php echo $value['name']?>" value="<?php echo $value['value']?>" placeholder="<?php echo $value['placeholder']?>"<?php echo $value['attribute']?>>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <div class="clearfix">
                <div class="btn-group float-right">
                    <a class="btn btn-secondary" href="/<?php echo URIS[1].'?'. $qstr?>">
                        목록
                    </a>
                    <button class="btn btn-primary" type="submit">
                        저장
                    </button>
                </div>
            </div>
        </form>
    </section>
</main>
