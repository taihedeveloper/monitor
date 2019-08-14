<?php
/**
 * @name Service_Page_CertificateMember
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_CertificateMember {
    private $objServiceDataCertificateMember;
    private $objServiceDataUserInfo;

    public function __construct(){
        $this->objServiceDataCertificateMember = new Service_Data_CertificateMember();
        $this->objServiceDataUserInfo = new Service_Data_UserInfo();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $type = $arrInput['type'];
        $class_id = $arrInput['class_id'];
        $member_name = $arrInput['member_name'];
        $is_admin = $arrInput['is_admin'];
        if (!$username || $type === NULL)
        {
            return array('error_code' => Monitor_Conf::PARAM_ERROR);
        }
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        if($type == 1){
            if ($is_admin === NULL)
            {
                $is_admin = 0;
            }
            if (!$member_name || $class_id === NULL)
            {
                return array('error_code' => Monitor_Conf::PARAM_ERROR);
            }
            $check_ret = $this->objServiceDataUserInfo->checkUser($member_name);
            if (!$check_ret)
            {
                $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
            }
            else
            {
                $add_result = $this->objServiceDataCertificateMember->addCertificateMember($username, $class_id, $member_name, $is_admin);
                if (!$add_result)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['data'] = $add_result;
                }
            }
        }else if ($type == 2){
            if ($class_id === NULL)
            {
                return array('error_code' => Monitor_Conf::PARAM_ERROR);
            }
            $delete_result = $this->objServiceDataCertificateMember->deleteCertificateMember($username, $class_id, $member_name);
            if (!$delete_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $delete_result;
            }
        }else if ($type == 3){
            if (!$member_name || $is_admin === NULL || $class_id === NULL)
            {
                return array('error_code' => Monitor_Conf::PARAM_ERROR);
            }
            $check_ret = $this->objServiceDataUserInfo->checkUser($member_name);
            if (!$check_ret)
            {
                $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
            }
            else
            {
                $update_result = $this->objServiceDataCertificateMember->updateCertificateMember($username, $class_id, $member_name, $is_admin);
                if (!$update_result)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['data'] = $update_result;
                }
            }
        }else if ($type == 4){
            $group_id = $arrInput['class_id'];
            $list_result = $this->objServiceDataCertificateMember->listDistinctName($username, $group_id);
            if (!$list_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $list_result;
            }
        }
        else
        {
            if (!$member_name)
            {
                return array('error_code' => Monitor_Conf::SUCCESS, 'data' => array());
            }
            $list_result = $this->objServiceDataCertificateMember->getUserCerInfo($username, $member_name);
            if ($list_result === false)
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
