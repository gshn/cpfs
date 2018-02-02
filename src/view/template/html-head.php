<?php
/**
 * Template html-head.php
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
<!doctype html>
<html lang="ko">
    <head>
        <title><?php echo $cf['head']['title']?></title>
        <meta name="description" content="<?php echo $cf['head']['description']?>">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
        <?php echo js_css([
            '/js/bootstrap-4.0.0/css/bootstrap.min.css',
            '/css/app.css'
        ])?>
    </head>
    <body class="<?php echo MOBILE ? 'mobile ': ''?><?php echo WINDOWS ? 'windows ' : ''?>top bg-light">
