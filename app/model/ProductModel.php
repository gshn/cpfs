<?php
class ProductModel extends ModelHelper
{
    protected $id;
    protected $user_id;
    protected $cate_id;
    protected $image_id;
    protected $prod_used;
    protected $prod_donate;
    protected $prod_collect;
    protected $prod_demolition;
    protected $prod_res_sell;
    protected $prod_res_share_biz;
    protected $prod_hr_get_job;
    protected $prod_hr_get_human;
    protected $prod_rental_sell;
    protected $prod_rental_buy;
    protected $prod_arrange_normal;
    protected $prod_arrange_relic;
    protected $title;
    protected $create_date;
    protected $prod_used_status;
    protected $deal_direct;
    protected $deal_delivery;
    protected $price;
    protected $postzip;
    protected $addr_01;
    protected $addr_02;
    protected $contents;
    protected $exist_lift;
    protected $exist_parking;
    protected $collect_date_start;
    protected $collect_date_end;
    protected $tel;
    protected $demolition_range;
    protected $demolition_date;
    protected $share_biz_goal;
    protected $share_biz_goal_date;
    protected $share_biz_current_amount;
    protected $resource_amount;
    protected $rental_sell_date_start;
    protected $rental_sell_date_end;
    protected $rental_buy_date_start;
    protected $rental_buy_date_end;
    protected $hr_get_human_date_start;
    protected $hr_get_human_date_end;
    protected $hr_get_human_check_01;
    protected $hr_get_human_check_02;
    protected $hr_get_human_check_03;
    protected $hr_get_human_check_04;
    protected $hr_get_human_check_05;
    protected $hr_get_human_check_06;
    protected $hr_get_human_check_07;
    protected $arrange_date_start;
    protected $arrange_date_end;
    protected $this_status;
    protected $latitude;
    protected $longitude;
    protected $delete_date;
    protected $hidden;

    public function __construct($table = 'tblproduct')
    {
        parent::__construct($table);
        extract(self::_getQueryVars());

        $this->select = "pro.*, usr.login_id, usr.email, usr.nickname";
        $this->common = "FROM tblproduct AS pro
                        LEFT JOIN tbluser AS usr
                        ON (pro.user_id = usr.id)";

        if ($stx) {
            $this->where .= " AND (pro.title LIKE '%{$stx}%' ";
            $this->where .= " OR pro.price = '{$stx}' ";
            $this->where .= " OR pro.this_status LIKE '%{$stx}%' ";
            $this->where .= " OR usr.login_id LIKE '%{$stx}%' ";
            $this->where .= " OR usr.nickname LIKE '%{$stx}%' ";
            $this->where .= " ) ";
        }

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY pro.id DESC";
        }
    }
}
