<?php
/**
 * @name Dao_ItemShow
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_ItemShow {
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
        return $group_ret[0];
    }

    /*
     * @comment 根据相关条件展示监控项列表
     * @param 
     * @return array
     */
    public function list_show($type, $level, $item_name, $id, $status, $group_arr, $start, $limit, $product_line)
    {
        $query = "SELECT id, item_name, url, level, frequence, last_runtime, status FROM monitor_item WHERE ";
        $condition = array();
        $condition[] = "1 = 1";
        if ($type !== NULL)
        {
            $condition[] = "type = '" . $type . "'";
        }
        if ($level !== NULL)
        {
            $condition[] = "level = '" . $level . "'";
        }
        if ($item_name !== NULL)
        {
            $condition[] = "item_name = '" . $item_name . "'";
        }
        if ($id !== NULL)
        {
            $condition[] = "id = '" . $id . "'";
        }
        if ($status !== NULL)
        {
            $condition[] = "status = '" . $status . "'";
        }
        else
        {
            $condition[] = "status <> '0'";
        }
        if (!in_array(0, $group_arr))
        {
            $condition[] = "cer_mem IN (" . implode(',', $group_arr) . ")";
        }
        if ($product_line !== NULL)
        {
            $condition[] = "product_line = '" . $product_line . "'";
        }
        $query = $query . implode(' AND ', $condition) . " ORDER BY id desc LIMIT " . $start . ", " . $limit;
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 根据相关条件获取满足条件的数量
     * @param 
     * @return array
     */
    public function get_count($type, $level, $item_name, $id, $status, $group_arr, $start, $limit, $product_line)
    {
        $query = "SELECT count(*) as num FROM monitor_item WHERE ";
        $condition = array();
        $condition[] = "1 = 1";
        if ($type !== NULL)
        {
            $condition[] = "type = '" . $type . "'";
        }
        if ($level !== NULL)
        {
            $condition[] = "level = '" . $level . "'";
        }
        if ($item_name !== NULL)
        {
            $condition[] = "item_name = '" . $item_name . "'";
        }
        if ($id !== NULL)
        {
            $condition[] = "id = '" . $id . "'";
        }
        if ($status !== NULL)
        {
            $condition[] = "status = '" . $status . "'";
        }
        else
        {
            $condition[] = "status <> '0'";
        }
        if (!in_array(0, $group_arr))
        {
            $condition[] = "cer_mem IN (" . implode(',', $group_arr) . ")";
        }
        if ($product_line !== NULL)
        {
            $condition[] = "product_line = '" . $product_line . "'";
        }
        $query = $query . implode(' AND ', $condition);
        $ret = $this->_db->query($query);

        if ($ret[0]['num'])
        {
            return $ret[0]['num'];
        }
        else
        {
            return "0";
        }
    }
}
