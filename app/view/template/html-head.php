<?php
global $cf, $uris, $uri, $is_guest, $is_user, $is_normal, $is_company;
?>
<!doctype html>
<html lang="ko">
    <head>
        <title><?php echo $cf['title']?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
        <?php echo js_css([
            '/css/app.css'
        ])?>
    </head>
    <body class="<?php echo $cf['is_mobile'] ? 'mobile ': ''?><?php echo $cf['is_windows'] ? 'windows ' : ''?>bg-light">
