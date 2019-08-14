<?php
/**
 * @name Service_Page_AlertRecordDetail
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_AlertRecordDetail {
    private $objServiceDataAlertRecordDetail;

    public function __construct(){
        $this->objServiceDataAlertRecordDetail = new Service_Data_AlertRecordDetail();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $alert_id = $arrInput['alert_id'];
        $alert_reason = $arrInput['alert_reason'];
        $severity_level = $arrInput['severity_level'];
        $feedback_detail = $arrInput['feedback_detail'];
        $loss_describe = $arrInput['loss_describe'];
        $feedbacktime = $arrInput['feedbacktime'];
		$arr_result = array('error_code' => Monitor_Conf::SUCCESS);
		$update_result = $this -> objServiceDataAlertRecordDetail -> updateRecord($username, $alert_id, $alert_reason, $severity_level, $feedback_detail, $loss_describe, $feedbacktime);
		if(!$update_result){
			$arr_result = array('error_code' => Monitor_Conf::FAILED);
		}
		$arr_result['data'] = $update_result;
		return $arr_result;
    }
}
