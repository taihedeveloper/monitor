<?php
/**
 * @name Action_AlertRecordDetail
 * @desc
 * @author chengbiao@taihe.com
 */
class Action_AlertRecordDetail extends Ap_Action_Abstract { 

    public function execute() {
        $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if (!$arrInput)
        {
            $arrInput = $arrRequest['post'];
        }
        $objServicePageAlertRecordDetail = new Service_Page_AlertRecordDetail();
        $arrPageInfo = $objServicePageAlertRecordDetail->execute($arrInput);
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
