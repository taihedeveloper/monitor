<?php
/**
 * @name Action_AlertManage
 * @desc
 * @author luohongcang@taihe.com
 */
class Action_AlertManage extends Ap_Action_Abstract { 

    public function execute() {
        $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if (!$arrInput)
        {
            $arrInput = $arrRequest['post'];
        }
        $objServicePageAlertManage = new Service_Page_AlertManage();
        $arrPageInfo = $objServicePageAlertManage->execute($arrInput);
        $arrOutput = $arrPageInfo;
        if(isset($arrInput['callback']) && !empty($arrInput['callback']))
        {
		    echo $arrInput['callback'] . "(" . json_encode($arrOutput) .")";
        }
        else
        {
            echo json_encode($arrOutput);
        }
    }
}
