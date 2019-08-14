<?php
/**
 * @name Service_Page_ProductLine
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_ProductLine {
    private $objServiceDataProductLine;

    public function __construct(){
        $this->objServiceDataProductLine = new Service_Data_ProductLine();
    }

    public function execute($arrInput){
        $arrResult = array();
        $arrResult['error_code'] = Monitor_Conf::SUCCESS;
        $username = $arrInput['username'];
        $type = $arrInput['type'];
        //$id = $arrInput['id'];
        $product_id = $arrInput['product_id'];
        $product_name = $arrInput['product_name'];
        if (!$username)
        {
            return array('error_code' => Monitor_Conf::PARAM_ERROR);
        }

        switch ($type) {
            case 1:
                if (!$product_name)
                {
                    return array('error_code' => Monitor_Conf::PARAM_ERROR);
                }
                $add_result = $this->objServiceDataProductLine->addProductLine($username, $product_name);
                if (!$add_result)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                break;
            case 2:
                if (!$product_id)
                {
                    return array('error_code' => Monitor_Conf::PARAM_ERROR);
                }
                $delete_result = $this->objServiceDataProductLine->deleteProductLine($username, $product_id);
                if (!$delete_result)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                break;
            case 3:
                if (!$product_id)
                {
                    return array('error_code' => Monitor_Conf::PARAM_ERROR);
                }
                if (!$product_name)
                {
                    return array('error_code' => Monitor_Conf::PARAM_ERROR);
                }
                $update_result = $this->objServiceDataProductLine->updateProductLine($username, $product_id, $product_name);
                if (!$update_result)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                break;
            case 4:
                $list_result = $this->objServiceDataProductLine->listDistinctName();
                if (!$list_result)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['data'] = $list_result;
                }
                break;
            default:
                $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                break;
        }
        return $arrResult;
    }
}
