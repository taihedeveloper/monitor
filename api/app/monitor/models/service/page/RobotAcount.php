<?php
/**
 * @name Service_Page_RobotAcount
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_RobotAcount {
    private $objServiceDataRobotAcount;

    public function __construct(){
        $this->objServiceDataRobotAcount = new Service_Data_RobotAcount();
    }

    public function execute($arrInput){
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        $ListResult = $this->objServiceDataRobotAcount->ListShow();
        if (!$ListResult)
        {
            $arrResult['data'] = array();
        }
        else
        {
            $arrResult['data'] = $ListResult;
        }

        return $arrResult;
    }
}
