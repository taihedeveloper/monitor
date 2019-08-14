<?php
/**
 * @name Service_Data_RobotAcount
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_RobotAcount {
    protected $_daoRobotAcount = null;

    public function __construct() {
        $this->_daoRobotAcount = new Dao_RobotAcount();
    }

    /*
     * @comment 获取用户所属权限组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoRobotAcount->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 根据相应条件查询监控项列表
     * @return array
     */
    public function ListShow()
    {
        $list_ret = $this->_daoRobotAcount->list_show();
        return $list_ret;
    }
}
