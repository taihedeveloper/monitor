<?php
/**
 * @name Dao_ProductLine
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_ProductLine {
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
        $group_query = "SELECT id, class_id from certificate_member WHERE username = '" . $username . "'";
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
    public function listShow()
    {
        $query = "SELECT id, product_id, product_name FROM product_lines";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

     /*
     * @comment 添加新的产品线
     * @param product_id 产品线id
     * @param product_name 产品线名字
     * @return array
     */
     public function addProductLine($product_id, $product_name)
    {
        $query = "INSERT INTO product_lines (product_id, product_name) VALUES ('$product_id', '$product_name')";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }
    
    /*
     * @comment 删除产品线
     * @param product_id 产品线id
     * @return array
     */
    public function deleteProductLine($product_id)
    {
        $query = "DELETE FROM product_lines WHERE product_id = '$product_id'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 更新产品线记录
     * @param product_id 产品线id
     * @param product_name 产品线名
     * @return array
     */
    public function updateProductLine($product_id, $product_name)
    {
        $query = "UPDATE product_lines SET product_name = '" . $product_name . "' WHERE product_id = '$product_id'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }
    
}
