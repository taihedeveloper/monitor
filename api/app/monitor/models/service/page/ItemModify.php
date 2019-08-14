<?php
/**
 * @name Service_Page_ItemModify
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_ItemModify {
    private $objServiceDataItemModify;

    public function __construct(){
        $this->objServiceDataItemModify = new Service_Data_ItemModify();
    }

    public function execute($arrInput){
        $oper = $arrInput['oper']; // 监控项更新类型
        $arrResult = array();

        switch($oper)
        {
            case 1:
                $item_name = $arrInput['item_name'];
                $level = $arrInput['level'];
                $type = $arrInput['type'];
                $frequence = $arrInput['frequence'];
                $url = $arrInput['url'];
                $monitoruser = $arrInput['monitoruser'];
                $alert_mem = $arrInput['alert_mem'];
                $cer_mem = $arrInput['cer_mem'];
                $eff_status = $arrInput['eff_status'];
                $product_line = $arrInput['product_line'];
                $time_out = $arrInput['time_out'];
                $criterion = $arrInput['criterion']; // 可能需要根据不同的type类型自己组装
                $callback_url = $arrInput['callback_url'];
                $monitor_args = $arrInput['monitor_args'];
                $post_content = $arrInput['post_content'];
                $mail_count = $arrInput['mail_count'];
                $message_count = $arrInput['message_count'];
                $referer = $arrInput['referer'];
                $user_agent = $arrInput['user_agent'];
                $username = $arrInput['username'];
                $start_time = $arrInput['start_time'];
                $end_time = $arrInput['end_time'];

                if (!$username)
                {
                    $arrResult['error_code'] = Monitor_Conf::UNLOGIN;
                    break;
                }
                if ($item_name === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项名不能为空";
                    break;
                }
                if ($level === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项等级不能为空";
                    break;
                }
                if ($type === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项类型不能为空";
                    break;
                }
                if ($frequence === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项执行频率不能为空";
                    break;
                }
                if ($url === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项url不能为空";
                    break;
                }
                if ($alert_mem === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "报警分组不能为空";
                    break;
                }
                if ($cer_mem === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "权限分组不能为空";
                    break;
                }
                if ($eff_status === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控时效性不能为空";
                    break;
                }
                if ($product_line === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "产品线名称不能为空";
                    break;
                }
                if ($time_out === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项超时时间不能为空";
                    break;
                }
                if ($criterion === NULL)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项匹配规则不能为空";
                    break;
                }

                if ($monitoruser === NULL)
                {
                    $monitoruser = "";
                }
                if ($callback_url === NULL)
                {
                    $callback_url = "";
                }
                if ($monitor_args === NULL)
                {
                    $monitor_args = "";
                }
                if ($post_content === NULL)
                {
                    $post_content = "";
                }
                if ($mail_count === NULL)
                {
                    $mail_count = 1;
                }
                if ($message_count === NULL)
                {
                    $message_count = 1;
                }
                if ($referer === NULL)
                {
                    $referer = "";
                }
                if ($user_agent === NULL)
                {
                    $user_agent = "";
                }
                if ($type == 0 || $type == 1)
                {
                    $criterion = json_encode($criterion);
                    if (is_array($post_content))
                    {
                        $post_content = json_encode($post_content);
                    }
                }
                if ($start_time === NULL || strtotime($start_time) === FALSE || strtotime($start_time) === -1)
                {
                    $start_time = "00:00";
                }
                if ($end_time === NULL || strtotime($end_time) === FALSE || strtotime($end_time) === -1)
                {
                    $end_time = "23:59";
                }
                if ($start_time >= $end_time)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项起始时间大于终止时间";
                    break;
                }

                $ModifyResult = $this->objServiceDataItemModify->AddItem($item_name, $level, $type, $frequence, $url, $monitoruser, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $username, $start_time, $end_time);

                if (!$ModifyResult)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    if ($ModifyResult[0]['id'])
                    {
                        $arrResult['error_code'] = Monitor_Conf::SUCCESS;
                        $arrResult['data']['id'] = $ModifyResult[0]['id'];
                    }
                    else
                    {
                        $arrResult['error_code'] = Monitor_Conf::FAILED;
                    }
                }
                break;
            case 2:
                $pro_id = $arrInput['taskid'];
                $status = $this->objServiceDataItemModify->GetStatus($pro_id);
                if ($status != 1)
                {
                    $arrResult['error_code'] = Monitor_Conf::OPERATEPERMISSION;
                    break;
                }
                $ModifyResult = $this->objServiceDataItemModify->RemoveItem($pro_id);
                if (!$ModifyResult)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['error_code'] = Monitor_Conf::SUCCESS;
                }
                break;
            case 3:
                $pro_id = $arrInput['taskid'];
                $status = $this->objServiceDataItemModify->GetStatus($pro_id);
                if ($status != 2)
                {
                    $arrResult['error_code'] = Monitor_Conf::OPERATEPERMISSION;
                    break;
                }
                $ModifyResult = $this->objServiceDataItemModify->DownItem($pro_id);
                if (!$ModifyResult)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['error_code'] = Monitor_Conf::SUCCESS;
                }
                break;
            case 4:
                $pro_id = $arrInput['taskid'];
                $status = $this->objServiceDataItemModify->GetStatus($pro_id);
                if ($status != 1)
                {
                    $arrResult['error_code'] = Monitor_Conf::OPERATEPERMISSION;
                    break;
                }
                $ModifyResult = $this->objServiceDataItemModify->UpItem($pro_id);
                if (!$ModifyResult)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['error_code'] = Monitor_Conf::SUCCESS;
                }
                break;
            case 5:
                $pro_id = $arrInput['taskid'];
                $status = $this->objServiceDataItemModify->GetStatus($pro_id);
                if ($status != 1)
                {
                    $arrResult['error_code'] = Monitor_Conf::OPERATEPERMISSION;
                    break;
                }

                $item_name = $arrInput['item_name'];
                $level = $arrInput['level'];
                $type = $arrInput['type'];
                $frequence = $arrInput['frequence'];
                $url = $arrInput['url'];
                $monitoruser = $arrInput['monitoruser'];
                $alert_mem = $arrInput['alert_mem'];
                $cer_mem = $arrInput['cer_mem'];
                $eff_status = $arrInput['eff_status'];
                $product_line = $arrInput['product_line'];
                $time_out = $arrInput['time_out'];
                $criterion = $arrInput['criterion'];
                $callback_url = $arrInput['callback_url'];
                $monitor_args = $arrInput['monitor_args'];
                $post_content = $arrInput['post_content'];
                $mail_count = $arrInput['mail_count'];
                $message_count = $arrInput['message_count'];
                $referer = $arrInput['referer'];
                $user_agent = $arrInput['user_agent'];
                $start_time = $arrInput['start_time'];
                $end_time = $arrInput['end_time'];
                if ($start_time === NULL || strtotime($start_time) === FALSE || strtotime($start_time) === -1)
                {
                    $start_time = "00:00";
                }
                if ($end_time === NULL || strtotime($end_time) === FALSE || strtotime($end_time) === -1)
                {
                    $end_time = "23:59";
                }
                if ($start_time >= $end_time)
                {
                    $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                    $arrResult['error_msg'] = "监控项起始时间大于终止时间";
                    break;
                }

                $ModifyResult = $this->objServiceDataItemModify->UpdateItem($pro_id, $item_name, $level, $type, $frequence, $url, $monitoruser, $alert_mem, $cer_mem, $eff_status, $product_line, $time_out, $criterion, $callback_url, $monitor_args, $post_content, $mail_count, $message_count, $referer, $user_agent, $start_time, $end_time);
                if (!$ModifyResult)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['error_code'] = Monitor_Conf::SUCCESS;
                }
                break;
            case 6:
                $url = $arrInput['url'];
                $referer = $arrInput['referer'];
                $user_agent = $arrInput['user_agent'];
                $criterion = $arrInput['criterion'];
                $post_content = $arrInput['post_content'];
                $monitor_args = $arrInput['monitor_args'];
                $monitoruser = $arrInput['monitoruser'];
                $time_out = $arrInput['time_out'];
                $type = $arrInput['type'];

                $ModifyResult = $this->objServiceDataItemModify->checkCriterion($url, $referer, $user_agent, $criterion, $post_content, $monitor_args, $monitoruser, $time_out, $type);
                if (!$ModifyResult)
                {
                    $arrResult['error_code'] = Monitor_Conf::FAILED;
                }
                else
                {
                    $arrResult['error_code'] = Monitor_Conf::SUCCESS;
                    $arrResult['data'] = $ModifyResult;
                }
                break;
            default:
                $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
                $arrResult['error_msg'] = "操作类型不存在";
        }

        return $arrResult;
    }
}
