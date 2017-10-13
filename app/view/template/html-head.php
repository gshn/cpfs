<?php
global $cf;
?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <title>admin | 우리동네고물상 동고물</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
        <?php echo js_css([
            '/css/bootstrap.css',
            '/css/app.css'
        ])?>
    </head>
    <body class="<?php echo $cf['is_mobile'] ? 'mobile': ''?> <?php echo $cf['is_webview'] ? 'webview': ''?> <?php echo $cf['is_windows'] ? 'windows' : ''?>">
