<?php
/**
 * @name Action_RobotAcount
 * @desc
 * @author luohongcang@taihe.com
 */
class Action_RobotAcount extends Ap_Action_Abstract { 

    public function execute() {
        $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if (!$arrInput)
        {
            $arrInput = $arrRequest['post'];
        }
        $objServicePageRobotAcount = new Service_Page_RobotAcount();
        $arrPageInfo = $objServicePageRobotAcount->execute($arrInput);
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
