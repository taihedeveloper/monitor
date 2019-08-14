<?php
/**
 * @name Action_CertificateManage
 * @desc
 * @author luohongcang@taihe.com
 */
class Action_CertificateManage extends Ap_Action_Abstract { 

    public function execute() {
        $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if (!$arrInput)
        {
            $arrInput = $arrRequest['post'];
        }
        $objServicePageCertificateManage = new Service_Page_CertificateManage();
        $arrPageInfo = $objServicePageCertificateManage->execute($arrInput);
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
