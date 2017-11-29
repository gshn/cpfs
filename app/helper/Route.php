<?php
namespace helper;

use controller\Product;
use controller\Promotion;

class Route
{
    public function promotion()
    {
        define('MAIN', TRUE);
        define('PROMOTION', TRUE);
        define('OWLCAROUSEL', TRUE);

        $pr = new \controller\Product();
        $list = $pr->getRecommendList();

        require VIEW.'/template/header.php';
        require VIEW.'/template/promotion.php';
        require VIEW.'/template/footer.php';
    }

    public function main()
    {
        define('MAIN', TRUE);
        define('OWLCAROUSEL', TRUE);

        $pr = new Product();
        $requests = $pr->getProductLatest('요청');
        $useds = $pr->getProductLatest('중고기부');

        $promotion = new Promotion();
        $companys = $promotion->getPromotionLatest();

        require VIEW.'/template/header.php';
        require VIEW.'/template/main.php';
        require VIEW.'/template/footer.php';
    }
}
