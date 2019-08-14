<?php
/**
 * @name Service_Page_UserInfo
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_UserInfo {
    private $objServiceDataUserInfo;
    public function __construct(){
        $this->objServiceDataUserInfo = new Service_Data_UserInfo();
    }

    public function execute($arrInput){
        $arrResult = array();
        $arrResult['error_code'] = Monitor_Conf::SUCCESS;
        $type = $arrInput['type'];
        $username = $arrInput['username'];
        $member_name = $arrInput['member_name'];
        if (!$username)
        {
            return array('error_code' => Monitor_Conf::PARAM_ERROR);
        }

        switch ($type)
        {
            case 1:
                if (!$member_name)
                {
                    return array('error_code' => Monitor_Conf::PARAM_ERROR);
                }
                $email = $arrInput['email'];
                $tel_no = $arrInput['tel_no'];
                if (!$email || !$tel_no)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    break;
                }
                $add_ret = $this->objServiceDataUserInfo->addUser($username, $member_name, $email, $tel_no);
                if (!$add_ret)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                break;
            case 2:
                if (!$member_name)
                {
                    return array('error_code' => Monitor_Conf::PARAM_ERROR);
                }
                $del_ret = $this->objServiceDataUserInfo->deleteUser($username, $member_name);
                if (!$del_ret)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                break;
            case 3:
                if (!$member_name)
                {
                    return array('error_code' => Monitor_Conf::PARAM_ERROR);
                }
                $email = $arrInput['email'];
                $tel_no = $arrInput['tel_no'];
                if (!$email && !$tel_no)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    break;
                }
                $update_ret = $this->objServiceDataUserInfo->updateUser($username, $member_name, $email, $tel_no);
                if (!$update_ret)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                break;
            case 4:
                $list_ret = $this->objServiceDataUserInfo->getUserList($member_name);
                if ($list_ret !== false)
                {
                    $arrResult['data'] = $list_ret;
                }
                else
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                break;
            default:
                $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
        }
		return $arrResult;
    }
}
