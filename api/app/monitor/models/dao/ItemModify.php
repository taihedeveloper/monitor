<?php
/**
 * @name Dao_ItemModify
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_ItemModify {
    private $_db = null;
    public function __construct()
    {
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    /*
     * @comment 将监控项的更新信息通知给后端进行调整
     * @param input 调整的信息数组
     * @return array 
     */
    public function postModifyInfo($input)
    {
        $nshead = array(
            'id' => 0,
            'version' => 1,
            'log_id'  => 0,
            'provider'=> '',
            'magic_num' => 0xfb709394,
            'reserved'=> 0
        );

        $ret = ral("scheduler", "post", $input, rand(), $nshead);
        if (false == $ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 监控项添加入数据库
     * @param 
     * @return boolean
     */
    public function add_item($item_name, $level, $idalloc_id, $type, $frequence, $url, $username, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $editor, $start_time, $end_time)
    {
        $criterion = addslashes($criterion);
        $query = "INSERT INTO monitor_item (idalloc_id, item_name, level, type, frequence, url, username, status, alert_mem, cer_mem, eff_status, product_line, time_out, criterion, callback_url, monitor_arg, post_content, mail_count, message_count, referer, user_agent, editor, start_time, end_time) VALUES ('" . $idalloc_id . "', '" . $item_name . "', '" . $level . "', '" . $type . "', '" . $frequence . "', '" . $url . "', '" . $username . "', 1, '" . $alert_mem . "', '" . $cer_mem . "', '" . $eff_status . "', '" . $product_line . "', '" . $time_out . "', '" . $criterion . "', '" . $callback_url . "', '" . $monitor_args .  "', '" . $post_content . "', '" . $mail_count . "', '" . $message_count . "', '" . $referer . "', '" . $user_agent . "', '" . $editor . "', '" . $start_time . "', '" . $end_time . "')";
        $ret = $this->_db->query($query);
        return $ret;
    }

    /*
     * @comment 添加监控项报警数初始化配置
     * @param 
     * @return boolean
     */
    public function add_alert_count($pro_id)
    {
        $query = "INSERT INTO alert_item_count (task_id, alert_count) VALUES (" . $pro_id . ", 0)";

        $ret = $this->_db->query($query);
        return $ret;
    }

    /*
     * @comment 通过idalloc_id获取监控项id
     * @param 
     * @return boolean
     */
    public function get_id($idalloc_id)
    {
        $query = "SELECT id from monitor_item WHERE idalloc_id = " . $idalloc_id;
        $ret = $this->_db->query($query);
        return $ret;
    }

    /*
     * @comment 获取监控项状态值
     * @param 
     * @return boolean
     */
    public function get_status($pro_id)
    {
        $query = "SELECT status from monitor_item WHERE id = " . $pro_id;
        $ret = $this->_db->query($query);
        return $ret;
    }

    /*
     * @comment 删除数据库中记录（逻辑删除）
     * @param 
     * @return boolean
     */
    public function del_item($pro_id)
    {
        $del_query = "UPDATE monitor_item set status = 0 WHERE id = " . $pro_id;
        $del_ret = $this->_db->query($del_query);
        return $del_ret;
    }

    /*
     * @comment 下线数据库中的一个记录
     * @param 
     * @return boolean
     */
    public function down_item($pro_id)
    {
        $down_query = "UPDATE monitor_item set status = 1 WHERE id = " . $pro_id;
        $down_query = $this->_db->query($down_query);
        return $down_query;
    }

    /*
     * @comment 下线数据库中的一个记录回滚
     * @param 
     * @return boolean
     */
    public function downback_item($pro_id)
    {
        $down_query = "UPDATE monitor_item set status = 2 WHERE id = " . $pro_id;
        $down_query = $this->_db->query($down_query);
        return $down_query;
    }

    /*
     * @comment 上线数据库中的一个记录
     * @param 
     * @return boolean
     */
    public function up_item($pro_id)
    {
        $now_time = time();
        $up_query = "UPDATE monitor_item set status = 2, last_online_time = " . $now_time . " WHERE id = " . $pro_id;
        $up_ret = $this->_db->query($up_query);
        return $up_ret;
    }

    /*
     * @comment 获取最后上线时间
     * @param 
     * @return boolean
     */
    public function get_last_online_time($pro_id)
    {
        $sel_query = "SELECT last_online_time FROM monitor_item WHERE id = " . $pro_id;
        $sel_query = $this->_db->query($sel_query);
        return $sel_query;
    }

    /*
     * @comment 上线数据库中的一个记录回滚
     * @param 
     * @return boolean
     */
    public function upback_item($pro_id)
    {
        $up_query = "UPDATE monitor_item set status = 1 WHERE id = " . $pro_id;
        $up_ret = $this->_db->query($up_query);
        return $up_ret;
    }

    /*
     * @comment 更新数据库中的一个记录
     * @param 
     * @return boolean
     */
    public function UpdateItem($pro_id, $item_name, $level, $type, $frequence, $url, $username, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $start_time, $end_time)
    {
        if ($item_name === NULL && $level === NULL && $type === NULL && $frequence === NULL && $url === NULL && $username === NULL && $alert_mem === NULL && $cer_mem === NULL && $eff_status == NULL && ($product_line === NULL || $product_line === '') && $time_out === NULL && $criterion === NULL && $callback_url === NULL && $monitor_args === NULL && $post_content === NULL && $mail_count === NULL && $message_count === NULL && $referer === NULL && $user_agent === NULL && $start_time === NULL && $end_time === NULL)
        {
            return true;
        }
        $update_query = "UPDATE monitor_item SET ";
        $update_arr = array();
        if ($item_name !== NULL)
        {
            $update_arr[] = "item_name = '" . $item_name . "'";
        }
        if ($level !== NULL)
        {
            $update_arr[] = "level = '" . $level . "'";
        }
        if ($type !== NULL)
        {
            $update_arr[] = "type = '" . $type . "'";
        }
        if ($frequence !== NULL)
        {
            $update_arr[] = "frequence = '" . $frequence . "'";
        }
        if ($url !== NULL)
        {
            $update_arr[] = "url = '" . $url . "'";
        }
        if ($username !== NULL)
        {
            $update_arr[] = "username = '" . $username . "'";
        }
        if ($alert_mem !== NULL)
        {
            $update_arr[] = "alert_mem = '" . $alert_mem . "'";
        }
        if ($cer_mem !== NULL)
        {
            $update_arr[] = "cer_mem = '" . $cer_mem . "'";
        }
        if ($eff_status !== NULL)
        {
            $update_arr[] = "eff_status = '" . $eff_status . "'";
        }
        if ($product_line !== NULL && $product_line !== '')
        {
            $update_arr[] = "product_line = '" . $product_line . "'";
        }
        if ($time_out !== NULL)
        {
            $update_arr[] = "time_out = '" . $time_out . "'";
        }
        if ($criterion !== NULL)
        {
            if ($type == 0 || $type == 1)
            {
                $criterion = json_encode($criterion);
            }
            $criterion = addslashes($criterion);
            $update_arr[] = "criterion = '" . $criterion . "'";
        }
        if ($callback_url !== NULL)
        {
            $update_arr[] = "callback_url = '" . $callback_url . "'";
        }
        if ($monitor_args !== NULL)
        {
            $update_arr[] = "monitor_arg = '" . $monitor_args . "'";
        }
        if ($post_content !== NULL)
        {
            if (is_array($post_content))
            {
                $post_content = json_encode($post_content);
            }
            $update_arr[] = "post_content = '" . $post_content . "'";
        }
        if ($mail_count !== NULL)
        {
            $update_arr[] = "mail_count = '" . $mail_count . "'";
        }
        if ($message_count !== NULL)
        {
            $update_arr[] = "message_count = '" . $message_count . "'";
        }
        if ($referer !== NULL)
        {
            $update_arr[] = "referer = '" . $referer . "'";
        }
        if ($user_agent !== NULL)
        {
            $update_arr[] = "user_agent = '" . $user_agent . "'";
        }
        if ($start_time !== NULL)
        {
            $update_arr[] = "start_time = '" . $start_time . "'";
        }
        if ($end_time !== NULL)
        {
            $update_arr[] = "end_time = '" . $end_time . "'";
        }
        $update_query = $update_query . implode(",", $update_arr) . " WHERE id = " . $pro_id;
        $update_ret = $this->_db->query($update_query);
        return $update_ret;
    }
}
