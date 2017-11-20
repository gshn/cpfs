<?php
if ($uri === '/login') {

    if ($_POST) {
        $us->login();
    } else {
        template('/user/login', 'no-header');
    }

} else if ($uri === '/') {
    template('/template/promotion', 'no-header');

} else if ($uri === '/bootstrap') {
    template('/template/bootstrap', 'no-header');

} else {
    location('/');
}
