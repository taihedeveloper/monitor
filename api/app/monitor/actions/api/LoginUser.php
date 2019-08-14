<?php
/**
 * @name Action_LoginUser
 * @desc
 * @author luohongcang@taihe.com
 */
class Action_LoginUser extends Ap_Action_Abstract {

	public function execute() {
	    $arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
        if (!$arrInput)
        {
            $arrInput = $arrRequest['post'];
        }
		$objServicePageLoginUser = new Service_Page_LoginUser();
		$arrPageInfo = $objServicePageLoginUser->execute($arrInput);
		$arrOutput = $arrPageInfo;
        if(isset($arrInput['callback']) && !empty($arrInput['callback']))
        {
		    echo $arrInput['callback']."(" .json_encode($arrOutput) .")";
        }
        else
        {
            echo json_encode($arrOutput);
        }
	}
}
