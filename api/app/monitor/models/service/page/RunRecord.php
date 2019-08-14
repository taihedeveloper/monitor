<?php
/**
 * @name Service_Page_RunRecord
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_RunRecord {
    private $objServiceDataRunRecord;

    public function __construct(){
        $this->objServiceDataRunRecord = new Service_Data_RunRecord();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $item_type = $arrInput['item_type'];
        $item_name = $arrInput['item_name'];
        $run_id = $arrInput['run_id'];
        $task_id = $arrInput['task_id'];
        $run_status = $arrInput['run_status'];
        $start_time = $arrInput['start_time'];
        $end_time = $arrInput['end_time'];
        $start = $arrInput['start'];
        $limit = $arrInput['limit'];
        if ($start === NULL)
        {
            $start = 0;
        }
        if ($limit === NULL)
        {
            $limit = 10;
        }
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        $ListResult = $this->objServiceDataRunRecord->ListShow($username, $run_id, $task_id, $item_type, $item_name, $run_status, $start, $limit, $start_time, $end_time);
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
