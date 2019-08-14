<?php
/**
 * @name Action_CertificateMember
 * @desc
 * @author luohongcang@taihe.com
 */
class Action_CertificateMember extends Ap_Action_Abstract { 

    public function execute() {
        $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if (!$arrInput)
        {
            $arrInput = $arrRequest['post'];
        }
        $objServicePageCertificateMember = new Service_Page_CertificateMember();
        $arrPageInfo = $objServicePageCertificateMember->execute($arrInput);
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
