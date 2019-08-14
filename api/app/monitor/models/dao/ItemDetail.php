<?php
/**
 * @name Dao_ItemDetail
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_ItemDetail {
    private $_db = null;
    public function __construct()
    {
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    /*
     * @comment 根据监控项id获取监控项详情
     * @param 
     * @return array
     */
    public function list_detail($id)
    {
        $query = "SELECT id, item_name, level, type, frequence, url, username, status, alert_mem, cer_mem, eff_status, product_line, time_out, criterion, last_runtime, post_content, callback_url, referer, user_agent, mail_count, message_count, start_time, end_time from monitor_item WHERE id = " . $id;
        $ret = $this->_db->query($query);
        if (!$ret || !$ret[0])
        {
            return false;
        }
        return $ret[0];
    }
}
