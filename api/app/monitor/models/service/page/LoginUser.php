<?php
/**
 * @name Service_Page_LoginUser
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_LoginUser {
    private $objServiceDataLoginUser;
    public function __construct(){
        $this->objServiceDataLoginUser = new Service_Data_LoginUser();
    }

    public function execute($arrInput){
        $arrResult = array();
		$arrResult['error_code'] = Monitor_Conf::SUCCESS;
        $username = $arrInput['username'];
        if (!$username)
        {
            $arrResult['error_code'] = Monitor_Conf::UNLOGIN;
        }
        elseif ($this->objServiceDataLoginUser->checkUser($username))
        {
            $arrResult['error_code'] = Monitor_Conf::SUCCESS;
        }
        else
        {
            $arrResult['error_code'] = Monitor_Conf::NOPERMISSION;
        }
		return $arrResult;
    }
}
