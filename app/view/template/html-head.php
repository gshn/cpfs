<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <title><?php echo $config['cfg_title']?></title>
        <?php
        echo js_css([
            ''
        ]);
        ?>
    </head>
    <body class="
        <?php echo $config['is_webview'] ? 'webview': ''?>
        <?php echo $config['is_windows'] ? 'windows' : ''?>
    ">
