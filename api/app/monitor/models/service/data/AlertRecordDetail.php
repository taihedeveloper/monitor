<?php
/**
 * @name Service_Data_AlertRecordDetail
 * @desc
 * @author chengbiao@taihe.com
 */

class Service_Data_AlertRecordDetail {
    protected $_daoAlertRecordDetail = null;

    public function __construct() {
        $this->_daoAlertRecordDetail = new Dao_AlertRecordDetail();
    }

    /*
     * @comment 获取用户所属权限组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoAlertRecordDetail->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 根据相应条件查询报警记录列表
     * @param username 发出请求的用户名
     * @param alert_id 报警记录id
     * @param item_name 监控项名称
     * @param process_status 处理状态
     * @param start_time 运行记录开始时间
     * @param end_time 运行记录结束时间
     * @return array
     */
	
    /*
	 * @comment 根据相应条件查询报警记录列表
     * @param alert_id 报警记录id
     * @param alert_reason 报警原因
     * @param severity_level 严重等级
     * @param feedback_detail 反馈详情
     * @param loss_describe 损失描述
	 */
	public function updateRecord($username, $alert_id, $alert_reason, $severity_level, $feedback_detail, $loss_describe, $feedbacktime){
		$group_arr = $this->GetGroup($username);
        if (!$group_arr)
        {
            return array();
        }
		$update_ret = $this->_daoAlertRecordDetail->updateRecord($alert_id, $alert_reason, $severity_level, $feedback_detail, $loss_describe, $feedbacktime, $group_arr);
		return $update_ret;
	}

}
