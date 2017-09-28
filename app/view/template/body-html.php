<noscript id="deferred-styles"></noscript>
<?php
echo js_css([
    '/js/jquery-3.2.0.min.js',
    '/js/bootstrap.min.js',
    '/js/sweetalert/sweetalert.css',
    '/js/sweetalert/sweetalert.min.js',
    '/fonts/font-awesome/css/font-awesome.min.css'
]);
echo $cf['is_mobile'] ? js_css('/js/fastclick.min.js') : '';
echo js_css('/js/app.js');
?>
</body>
</html>
