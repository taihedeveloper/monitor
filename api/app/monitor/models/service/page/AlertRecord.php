<?php
/**
 * @name Service_Page_AlertRecord
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_AlertRecord {
    private $objServiceDataAlertRecord;

    public function __construct(){
        $this->objServiceDataAlertRecord = new Service_Data_AlertRecord();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $item_type = $arrInput['item_type'];
   		$item_level = $arrInput['item_level'];
        $item_name = $arrInput['item_name'];
        $alert_id = $arrInput['alert_id'];
        $process_status = $arrInput['process_status'];
        $start_time = $arrInput['start_time'];
        $end_time = $arrInput['end_time'];
        $start = $arrInput['start'];
        $limit = $arrInput['limit'];
		$update = $arrInput['update'];
		if($update){
        	$arr_result = array('error_code' => Monitor_Conf::SUCCESS);
			$update_result = $this -> objServiceDataAlertRecord -> updateStatus($username, $alert_id, $update);
			if(!$update_result){
        		$arr_result = array('error_code' => Monitor_Conf::FAILED);
			}
			$arr_result['data'] = $update_result;
			return $arr_result;
		}

        if ($start === NULL)
        {
            $start = 0;
        }
        if ($limit === NULL)
        {
            $limit = 10;
        }
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        $ListResult = $this->objServiceDataAlertRecord->ListShow($username, $alert_id, $item_type, $item_level, $item_name, $process_status, $start, $limit, $start_time, $end_time);
        if (!$ListResult['listdata'])
        {
            $arrResult['data'] = array();
            $arrResult['count'] = 0;
        }
        else
        {
            $arrResult['data'] = $ListResult['listdata'];
            $arrResult['count'] = $ListResult['listcount'];
        }

        return $arrResult;
    }
}
