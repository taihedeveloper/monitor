<?php
/**
 * @name Service_Page_ItemShow
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_ItemShow {
    private $objServiceDataItemShow;

    public function __construct(){
        $this->objServiceDataItemShow = new Service_Data_ItemShow();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $type = $arrInput['type'];
        $level = $arrInput['level'];
        $item_name = $arrInput['item_name'];
        $id = $arrInput['taskid'];
        $status = $arrInput['status'];
        $start = $arrInput['start'];
        $limit = $arrInput['limit'];
        $product_line = $arrInput['product_line'];
        if ($start === NULL)
        {
            $start = 0;
        }
        if ($limit === NULL)
        {
            $limit = 10;
        }
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        $ListResult = $this->objServiceDataItemShow->ListShow($type, $level, $item_name, $id, $status, $username, $start, $limit, $product_line);
        if (!$ListResult['listdata'])
        {
            $arrResult['data'] = array();
            $arrResult['count'] = 0;
        }
        else
        {
            $arrResult['data'] = $ListResult['listdata'];
            $arrResult['count'] = $ListResult['listcount'];
        }

        return $arrResult;
    }
}
