<noscript id="deferred-styles">
<?php
echo js_css([
    '/css/bootstrap.css',
    '/fonts/NanumSquareRound/NanumSquareRound.css',
    '/fonts/font-awesome/css/font-awesome.min.css',
    '/js/sweetalert/sweetalert.css',
]);
?>
</noscript>
<?php
echo js_css([
    '/js/jquery-3.2.1.slim.min.js',
    '/js/popper.min.js',
    '/js/bootstrap.min.js',
    '/js/sweetalert/sweetalert.min.js',
    '/js/aos/aos.css',
    '/js/aos/aos.js'
]);
echo defined('OWLCAROUSEL') ? js_css([
  '/css/animate.min.css',
  '/js/owl-carousel/owl.theme.default.css',
  '/js/owl-carousel/owl.carousel.min.css',
  '/js/owl-carousel/owl.carousel.min.js'
]) : '';
echo defined('REGISTER') ? js_css([
    '/js/register.js'
]).'<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>'.PHP_EOL : '';
echo $cf['is_mobile'] ? js_css('/js/fastclick.min.js') : '';
echo js_css('/js/app.js');
?>
</body>
</html>
