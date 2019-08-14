<?php
/**
 * @name Action_RunRecord
 * @desc
 * @author luohongcang@taihe.com
 */
class Action_RunRecord extends Ap_Action_Abstract { 

    public function execute() {
        $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if (!$arrInput)
        {
            $arrInput = $arrRequest['post'];
        }
        $objServicePageRunRecord = new Service_Page_RunRecord();
        $arrPageInfo = $objServicePageRunRecord->execute($arrInput);
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
