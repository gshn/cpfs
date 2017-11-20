<noscript id="deferred-styles">
    <?php echo js_css([
        '/fonts/NanumSquareRound/NanumSquareRound.css',
        '/fonts/font-awesome/css/font-awesome.min.css',
        '/js/sweetalert/sweetalert.css',
        '/js/aos/aos.css'
    ])?>
</noscript>
<?php
echo js_css([
    '/js/jquery-3.2.1.slim.min.js',
    '/js/popper.min.js',
    '/js/bootstrap.min.js',
    '/js/sweetalert/sweetalert.min.js',
    '/js/aos/aos.js'
]);
echo defined('OWLCAROUSEL') ? js_css(['/css/animate.min.css', '/js/owl-carousel/owl.carousel.min.css', '/js/owl-carousel/owl.carousel.min.js']) : '';
echo $cf['is_mobile'] ? js_css('/js/fastclick.min.js') : '';
echo js_css('/js/app.js');
?>
</body>
</html>
