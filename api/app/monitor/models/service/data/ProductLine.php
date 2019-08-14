<?php
/**
 * @name Service_Data_ProductLine
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_ProductLine {
    protected $_daoProductLine = null;

    public function __construct() {
        $this->_daoProductLine = new Dao_ProductLine();
    }

    /*
     * @comment 获取用户所属权限组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoProductLine->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 获取所有产品线
     * @return array
     */
    public function listDistinctName()
    {
        $list_ret = $this->_daoProductLine->listShow();
        return $list_ret;
    }
    
     /*
     * @comment 添加新的产品线
     * @param product_id 新的产品线id
     * @param product_name 新的产品线名
     * @return array
     */
    public function addProductLine($username, $product_name)
    {
        // 从idalloc获取相应id，作为唯一标示
        $ch = curl_init();
        $header = array('Content-Type: application/json');
        $curlOptions = array(
            CURLOPT_URL             =>  Monitor_Conf::idalloc_host . "?pid=monitor_proline_id",
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

        $product_id = $response['result_data'];

        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoProductLine->addProductLine($product_id, $product_name);
        return $list_ret;
    }

    /*
     * @comment 删除产品线
     * @param username 用户名
     * @param id 所要删除的id
     * @return array
     */
    public function deleteProductLine($username, $product_id)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoProductLine->deleteProductLine($product_id);
        return $list_ret;
    }

    /*
     * @comment 更新产品线
     * @param username 用户名
     * @param id 所要更新的id
     * @param product_id 所要更新的产品线id
     * @param product_name 所要更新的产品线名
     * @return array
     */
    public function updateProductLine($username, $product_id, $product_name)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoProductLine->updateProductLine($product_id, $product_name);
        return $list_ret;
    }
}
