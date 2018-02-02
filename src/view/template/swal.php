<?php
/**
 * Template swal.php
 * 
 * PHP Version 7
 * 
 * @category View
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
define('SWEETALERT', true);
require VIEW.'/template/html-head.php';
ob_start();
?>
<script>
swal({
    title: '<?php echo $title?>',
    text: '<?php echo $text?>',
    type: '<?php echo $type?>',
    confirmButtonClass: 'btn-info btn-raised',
    confirmButtonText: '확인',
    closeOnCancel: false
}, function(isConfirm) {
    <?php if (isset($url)) { ?>
        window.location.replace('<?php echo $url?>');
    <?php } else { ?>
        window.history.go(-1);
    <?php } ?>
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
