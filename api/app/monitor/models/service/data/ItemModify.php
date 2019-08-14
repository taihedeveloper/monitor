<?php
/**
 * @name Service_Data_ItemModify
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_ItemModify {
    protected $_daoItemModify = null;

    public function __construct() {
        $this->_daoItemModify = new Dao_ItemModify();
    }

    /*
     * @comment 添加一条新的监控
     * @param item_name 监控项名称
     * @param level 监控项等级
     * @param type 监控项类型
     * @param frequence 监控项频率
     * @param url 监控项url
     * @param username 监控url所带用户名参数
     * @param alert_mem 报警组id
     * @param cer_mem 权限组id
     * @param eff_status 是否永久生效
     * @param product_line 产品线id
     * @param time_out 超时时间
     * @param criterion 匹配规则
     * @return boolean
     */
    public function AddItem($item_name, $level, $type, $frequence, $url, $username, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $editor, $start_time, $end_time)
    {
        // 从idalloc获取相应id，作为唯一标示
        $ch = curl_init();
        $header = array('Content-Type: application/json');
        $curlOptions = array(
            CURLOPT_URL             =>  Monitor_Conf::idalloc_host . "?pid=monitor_id",
            CURLOPT_RETURNTRANSFER	=>	true,
            CURLOPT_HEADER			=>	false,
            CURLOPT_FOLLOWLOCATION	=>	true,
            CURLOPT_HTTPHEADER      =>  $header,
        );
        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        if (0 != $errno)
        {
            return false;
        }
        curl_close($ch);
        $response = json_decode($response, 1);

        if ($response['error_no'] != 10000)
        {
            return false;
        }

        $idalloc_id = $response['result_data'];

        // 在数据库中添加一条新纪录
        $add_result = $this->_daoItemModify->add_item($item_name, $level, $idalloc_id, $type, $frequence, $url, $username, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $editor, $start_time, $end_time);
        if (!$add_result)
        {
            return false;
        }

        // 获取该任务的实际数据库id，返回给前端，可能会立刻推送给后端使用
        $id_result = $this->_daoItemModify->get_id($idalloc_id);
        if (!$id_result)
        {
            return false;
        }
        
        if ($id_result[0]['id'])
        {
            $add_count = $this->_daoItemModify->add_alert_count($id_result[0]['id']);
            if (!$add_count)
            {
                return false;
            }
        }
        else
        {
            return false;
        }
        return $id_result;
    }

    /*
     * @comment 获取监控项状态
     * @param pro_id 监控项id
     * @return boolean
     */
    public function GetStatus($pro_id)
    {
        $ret = $this->_daoItemModify->get_status($pro_id);
        if (!$ret)
        {
            return false;
        }
        return $ret[0]['status'];
    }

    /*
     * @comment 删除一条监控
     * @param pro_id 监控项id
     * @return boolean
     */
    public function RemoveItem($pro_id)
    {
        // 在数据库中更新监控项状态
        $del_result = $this->_daoItemModify->del_item($pro_id);
        if (!$del_result)
        {
            return false;
        }
        return true;
    }

    /*
     * @comment 下线一条监控
     * @param type 监控项类型
     * @param pro_id 监控项id
     * @return boolean
     */
    public function DownItem($pro_id)
    {
        // 在数据库中更新监控项状态
        $down_ret = $this->_daoItemModify->down_item($pro_id);
        if (!$down_ret)
        {
            return false;
        }

        // 获取最后上线时间，作为唯一标示
        $last_online_time_ret = $this->_daoItemModify->get_last_online_time($pro_id);
        if (!$last_online_time_ret)
        {
            return false;
        }
        $last_online_time = $last_online_time_ret[0]['last_online_time'];

        // 向后端实时推送这一纪录
        $push_arr = array();
        $push_arr['oper'] = 3;
        $push_arr['pro_id'] = $pro_id;
        $push_arr['last_online_time'] = $last_online_time;
        $push_ret = $this->_daoItemModify->postModifyInfo($push_arr);
        if (!$push_ret || $push_ret['error_no'] != 0)
        {
            $this->_daoItemModify->downback_item($pro_id); // 回滚任务状态
            return false;
        }
        return true;
    }

    /*
     * @comment 上线一条监控
     * @param type 监控项类型
     * @param pro_id 监控项id
     * @return boolean
     */
    public function UpItem($pro_id)
    {
        // 在数据库中更新监控项状态
        $up_ret = $this->_daoItemModify->up_item($pro_id);
        if (!$up_ret)
        {
            return false;
        }

        // 获取最后上线时间，作为唯一标示
        $last_online_time_ret = $this->_daoItemModify->get_last_online_time($pro_id);
        if (!$last_online_time_ret)
        {
            return false;
        }
        $last_online_time = $last_online_time_ret[0]['last_online_time'];

        // 向后端实时推送这一纪录
        $push_arr = array();
        $push_arr['oper'] = 4;
        $push_arr['pro_id'] = $pro_id;
        $push_arr['last_online_time'] = $last_online_time;
        $push_ret = $this->_daoItemModify->postModifyInfo($push_arr);
        if (!$push_ret || $push_ret['error_no'] != 0)
        {
            $this->_daoItemModify->upback_item($pro_id);
            return false;
        }
        return true;
    }

    /*
     * @comment 更新一条监控
     * @param item_name 监控项名称
     * @param level 监控项等级
     * @param type 监控项类型
     * @param frequence 监控项频率
     * @param url 监控项url
     * @param username 监控url所带用户名参数
     * @param alert_mem 报警组id
     * @param cer_mem 权限组id
     * @param eff_status 是否永久生效
     * @param product_line 产品线id
     * @param time_out 超时时间
     * @param criterion 匹配规则
     * @return boolean
     */
    public function UpdateItem($pro_id, $item_name, $level, $type, $frequence, $url, $username, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $start_time, $end_time)
    {
        // 在数据库中更新一条纪录
        $update_ret = $this->_daoItemModify->UpdateItem($pro_id, $item_name, $level, $type, $frequence, $url, $username, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $start_time, $end_time);
        if (!$update_ret)
        {
            return false;
        }
        return true;
    }

    /*
     * @comment 检测一条监控判断是否满足条件
     * @param url 监控项url
     * @param criterion 匹配规则
     * @param user_agent ua
     * @param post_content post参数
     * @param monitor_args monitor参数
     * @param monitoruser 机器人手机号
     * @param time_out 超时时间
     * @param type 监控项类型
     * @return boolean
     */
    public function checkCriterion($url, $referer, $user_agent, $criterion, $post_content, $monitor_args, $monitoruser, $time_out, $type)
    {
        $push_arr = array();
        $push_arr['oper'] = 6;
        if ($url === NULL)
        {
            $push_arr['url'] = "";
        }
        else
        {
            $push_arr['url'] = $url;
        }
        if ($type === NULL)
        {
            $push_arr['item_type'] = "";
        }
        else
        {
            $push_arr['item_type'] = intval($type);
        }
        if ($monitor_args === NULL)
        {
            $push_arr['monitor_arg'] = 0;
        }
        else
        {
            $push_arr['monitor_arg'] = $monitor_args;
        }
        if ($criterion === NULL)
        {
            $push_arr['criterion'] = "";
        }
        else
        {
            $push_arr['criterion'] = $criterion;
        }
        if ($post_content === NULL)
        {
            $push_arr['post_content'] = "";
        }
        else
        {
            $push_arr['post_content'] = $post_content;
        }
        if ($time_out === NULL)
        {
            $push_arr['time_out'] = 0;
        }
        else
        {
            $push_arr['time_out'] = $time_out;
        }
        if ($monitoruser === NULL)
        {
            $push_arr['username'] = "";
        }
        else
        {
            $push_arr['username'] = $monitoruser;
        }
        if ($referer === NULL)
        {
            $push_arr['referer'] = "";
        }
        else
        {
            $push_arr['referer'] = $referer;
        }
        if ($user_agent === NULL)
        {
            $push_arr['user_agent'] = "";
        }
        else
        {
            $push_arr['user_agent'] = $user_agent;
        }
        $push_ret = $this->_daoItemModify->postModifyInfo($push_arr);

        if (!$push_ret || $push_ret['error_no'] != 0)
        {
            return false;
        }
        $ret_arr = array();
        if ($push_ret['check_status'] == 0)
        {
            $ret_arr['check_status'] = 0;
            $ret_arr['msg'] = 'Success';
        }
        else
        {
            $ret_arr['check_status'] = 1;
            $ret_arr['msg'] = $push_ret['check_msg'];
        }

        return $ret_arr;
    }
}
