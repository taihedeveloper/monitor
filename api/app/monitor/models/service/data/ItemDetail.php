<?php
/**
 * @name Service_Data_ItemDetail
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_ItemDetail {
    protected $_daoItemDetail = null;

    public function __construct() {
        $this->_daoItemDetail = new Dao_ItemDetail();
    }

    /*
     * @comment ���ݼ����id��ȡ���������
     * @param id �����id
     * @return array
     */
    public function ListDetail($id)
    {
        $detail_ret = $this->_daoItemDetail->list_detail($id);
        if (!$detail_ret)
        {
            return false;
        }
        $detail_ret['monitoruser'] = $detail_ret['username'];
        unset($detail_ret['username']);
        return $detail_ret;
    }
}
