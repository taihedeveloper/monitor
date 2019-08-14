<?php
/**
 * @name Service_Page_ItemDetail
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_ItemDetail {
    private $objServiceDataItemDetail;

    public function __construct(){
        $this->objServiceDataItemDetail = new Service_Data_ItemDetail();
    }

    public function execute($arrInput){
        $id = $arrInput['taskid'];
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        $detailResult = $this->objServiceDataItemDetail->ListDetail($id);
        if (!$detailResult)
        {
            $arrResult = array('error_code' => Monitor_Conf::FAILED);
        }
        else
        {
            $arrResult['data'] = $detailResult;
        }
        return $arrResult;
    }
}
