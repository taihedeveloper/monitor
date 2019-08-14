<?php
/**
 * @name Service_Page_AlertManage
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_AlertManage {
    private $objServiceDataAlertManage;

    public function __construct(){
        $this->objServiceDataAlertManage = new Service_Data_AlertManage();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $type = $arrInput['type'];
        $class_id = $arrInput['class_id'];
        $class_name = $arrInput['class_name'];
        $arrResult = array();
        if($type == 1){
            $add_result = $this->objServiceDataAlertManage->addAlertManage($username, $class_name);
            if (!$add_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['error_code'] = Monitor_Conf::SUCCESS;
            }
        }else if ($type == 2){
            $delete_result = $this->objServiceDataAlertManage->deleteAlertManage($username, $class_id);
            if (!$delete_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['error_code'] = Monitor_Conf::SUCCESS;
            }
        }else if ($type == 3){
            $update_result = $this->objServiceDataAlertManage->updateAlertManage($username, $class_id, $class_name);
            if (!$update_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['error_code'] = Monitor_Conf::SUCCESS;
            }
        }else{
            $list_result = $this->objServiceDataAlertManage->listDistinctName($username);
            if (!$list_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['error_code'] = Monitor_Conf::SUCCESS;
                $arrResult['data'] = $list_result;
            }
        }
        return $arrResult;
    }
}
