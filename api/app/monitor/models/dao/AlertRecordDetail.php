<?php
/**
 * @name Dao_AlertRecordDetail
 * @desc
 * @author chengbiao@taihe.com
 */

class Dao_AlertRecordDetail {
    private $_db = null;
    public function __construct()
    {
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    /*
     * @comment 获取用户所属权限组
     * @param 
     * @return array
     */
    public function get_group($username)
    {
        $group_query = "SELECT class_id from certificate_member WHERE username = '" . $username . "'";
        $group_ret = $this->_db->query($group_query);
        if ($group_ret == null || !$group_ret[0])
        {
            return array();
        }
		$ret = array();
		foreach($group_ret as $key => $val){
			$ret[] = $val['class_id'];
		}
        return $ret;
    }

    /*
     * @comment 根据相关条件展示监控项列表
     * @param 
     * @return array
     */

	public function updateRecord($alert_id, $alert_reason, $severity_level, $feedback_detail, $loss_describe, $feedbacktime, $group_arr){
		$alert_id_arr = explode(",", $alert_id);
		foreach($alert_id_arr as $key => $val){
			if(in_array(0, $group_arr)){
				$query = "UPDATE alert_record SET alert_reason = '$alert_reason', severity_level = '$severity_level', feedback_detail = '$feedback_detail', loss_describe = '$loss_describe', feedbacktime = '$feedbacktime' WHERE id = '$val'"; 
			}else{
				$query = "UPDATE alert_record SET alert_reason = '$alert_reason', severity_level = '$severity_level', feedback_detail = '$feedback_detail', loss_describe = '$loss_describe', feedbacktime = '$feedbacktime' WHERE id = '" . $val . "' AND cer_mem in ('" . implode("','", $group_arr) . "')";
			}
			$ret = $this->_db->query($query);
			if(empty($ret)){
				return false;
			}
		}
		return true;
	}
}
