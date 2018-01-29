<?php
define('SWEETALERT', true);
require VIEW.'/template/html-head.php';
ob_start();
?>
<script>
swal({
    title: '<?php echo $title?>',
    text: '<?php echo $text?>',
    type: '<?php echo $type?>',
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: '확인',
    cancelButtonText: "취소",
    closeOnConfirm: false,
    closeOnCancel: false
}, function(isConfirm) {
    if (isConfirm) {
        <?php if (isset($url)) { ?>
            window.location.replace('<?php echo $url?>');
        <?php } else { ?>
            window.history.go(-1);
        <?php } ?>
    } else {
        window.history.go(-1);
    }

});
</script>
<?php
$swal = ob_get_contents();
ob_end_clean();
ob_start();
require VIEW.'/template/body-html.php';
$tail = ob_get_contents();
ob_end_clean();
$tail = str_replace('</body>', $swal.PHP_EOL.'</body>', $tail);
echo $tail;
