<?php
/**
 * @name Service_Page_CertificateManage
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_CertificateManage {
    private $objServiceDataCertificateManage;

    public function __construct(){
        $this->objServiceDataCertificateManage = new Service_Data_CertificateManage();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $type = $arrInput['type'];
        $id = $arrInput['id'];
        $class_id = $arrInput['class_id'];
        $class_name = $arrInput['class_name'];
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        if($type == 1){
            $add_result = $this->objServiceDataCertificateManage->addCertificateManage($username, $class_name);
            if (!$add_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $add_result;
            }
        }else if ($type == 2){
            $delete_result = $this->objServiceDataCertificateManage->deleteCertificateManage($username, $class_id);
            if (!$delete_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $delete_result;
            }
        }else if ($type == 3){
            $update_result = $this->objServiceDataCertificateManage->updateCertificateManage($username, $class_id, $class_name);
            if (!$update_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $update_result;
            }
        }else{
            $list_result = $this->objServiceDataCertificateManage->listDistinctName($username);
            if (!$list_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $list_result;
            }
        }
        return $arrResult;
    }
}
