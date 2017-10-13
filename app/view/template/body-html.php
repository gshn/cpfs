<noscript id="deferred-styles"></noscript>
<?php
echo js_css([
    '/js/jquery-3.2.0.min.js',
    '/js/vue-2.3.4.min.js',
    '/js/axios-0.12.0.min.js',
    '/js/lodash-1.8.3.min.js',
    '/js/bootstrap.min.js',
    '/js/sweetalert/sweetalert.css',
    '/js/sweetalert/sweetalert.min.js',
    '/fonts/font-awesome/css/font-awesome.min.css'
]);
echo defined('AOS') ? js_css(['/js/aos/aos.css', '/js/aos/aos.js']) : '';
echo defined('OWLCAROUSEL') ? js_css(['/css/animate.min.css', '/js/owl-carousel/owl.carousel.min.css', '/js/owl-carousel/owl.carousel.min.js']) : '';
echo defined('LOGIN') ? js_css('/js/login.js') : '';
echo defined('REGISTER') ? js_css('/js/register.js') : '';
echo defined('DASHBOARD') ? js_css([
    '/js/chartjs/vue-chart-2.6.0.min.js',
    '/js/dashboard.js'
]) : '';
echo defined('DATA') ? js_css([
    '/js/data.js'
]) : '';
echo defined('UPLOAD') ? js_css([
    '/css/jquery-ui.min.css',
    '/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css',
    '/js/jquery-ui.min.js',
    '/js/plupload/plupload.full.min.js',
    '/js/plupload/jquery.ui.plupload/jquery.ui.plupload.js',
    '/js/plupload/i18n/ko.js',
    '/js/upload.js'
]) : '';
echo defined('PATIENT') ? js_css([
    '/js/patient.js'
]) : '';
echo defined('DOCTOR') ? js_css([
    '/js/doctor.js'
]) : '';
echo defined('HOSPITAL') ? js_css([
    '/js/hospital.js'
]) : '';
echo defined('LANDING') ? js_css([
    '/js/jquery.easing.min.js',
    '/js/landing.js'
]) : '';
echo $cf['is_mobile'] ? js_css('/js/fastclick.min.js') : '';
echo js_css('/js/app.js');
?>
</body>
</html>
