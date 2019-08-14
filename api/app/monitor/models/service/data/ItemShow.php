<?php
/**
 * @name Service_Data_ItemShow
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_ItemShow {
    protected $_daoItemShow = null;

    public function __construct() {
        $this->_daoItemShow = new Dao_ItemShow();
    }

    /*
     * @comment 获取用户所属权限组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoItemShow->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 根据相应条件查询监控项列表
     * @param type 监控项类型
     * @param level 监控项级别
     * @param item_name 监控项名称
     * @param id 监控项id
     * @param status 监控项状态
     * @param username 用户姓名
     * @return array
     */
    public function ListShow($type, $level, $item_name, $id, $status, $username, $start, $limit, $product_line)
    {
        $group_arr = $this->GetGroup($username);
        if (!$group_arr)
        {
            return array();
        }
        $list_ret = $this->_daoItemShow->list_show($type, $level, $item_name, $id, $status, $group_arr, $start, $limit, $product_line);
        $count_ret = $this->_daoItemShow->get_count($type, $level, $item_name, $id, $status, $group_arr, $start, $limit, $product_line);
        return array("listdata" => $list_ret, "listcount" => $count_ret);
    }
}
