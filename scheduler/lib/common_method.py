#encoding=utf-8
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
一些公共使用方法，持续添加中
Authors: luohongcang(luohongcang@taihe.com)
Date:    2017/02/23
"""

import os
import sys
import time
import urllib
import urllib2

from lib.scheduler_db import scheduler_db
import lib.scheduler_logger

def add_run_record(task_id, item_name, run_status, item_type, cer_mem, time_consume, data_url):
    """
        添加运行记录
    Args:
    Returns:
    Raises:
    """
    p_data = {}
    p_data['task_id'] = task_id
    p_data['item_name'] = item_name
    p_data['run_status'] = run_status
    p_data['run_time'] = time.time()
    p_data['item_type'] = item_type
    p_data['cer_mem'] = cer_mem
    p_data['time_consume'] = time_consume
    p_data['run_url'] = data_url
    try:
        _scheduler_db = scheduler_db()
        _scheduler_db.add('run_record', p_data)
        _scheduler_db.commit()
        _scheduler_db.close()
    except Exception, e:
        lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "table run_record insert fail. task_id:" + str(task_id) + " error:" + str(e))
        return False
    return True

def add_alert_record(process_status, item_name, item_level, item_type, alert_detail, task_id, alert_mem, cer_mem, data_url):
    """
        添加报警记录
    Args:
    Returns:
    Raises:
    """
    p_data = {}
    p_data['process_status'] = process_status
    p_data['item_name'] = item_name
    p_data['item_level'] = item_level
    p_data['item_type'] = item_type
    p_data['alert_time'] = time.time()
    p_data['alert_detail'] = alert_detail
    p_data['task_id'] = task_id
    p_data['alert_mem'] = alert_mem
    p_data['cer_mem'] = cer_mem
    p_data['item_url'] = data_url
    p_data['alert_message'] = alert_detail
    try:
        _scheduler_db = scheduler_db()
        _scheduler_db.add('alert_record', p_data)
        _scheduler_db.commit()
        _scheduler_db.close()
    except Exception, e:
        lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "table alert_record insert fail. task_id:" + str(task_id) + " error:" + str(e))
        return False
    return True

def update_task_exectime(task_id):
    """
        更新任务最后执行时间
    Args:
    Returns:
    Raises:
    """
    try:
        _scheduler_db = scheduler_db()
        now_timestamp = int(time.time())
        update_sql = "UPDATE monitor_item SET last_runtime = " + str(now_timestamp) + " WHERE id = " + str(task_id)
        _scheduler_db.query(update_sql)
        _scheduler_db.commit()
        _scheduler_db.close()
    except Exception, e:
        lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "table update task last runtime fail. task_id:" + str(task_id) + " error:" + str(e))
        return False
    return True

def add_process_record(run_status, _time_consume, _exc, item_info):
    """
        添加处理结束后的各项记录
        item_info中需要包含的信息
        data_item_id: 监控项id
        data_item_name: 监控项名称
        item_type: 监控项类型 0:http可用性 1:数据接口 2:页面元素
        data_cer_mem: 监控项权限组
        item_level: 监控项级别
        data_alert_mem: 监控项报警组
    Args:
        run_status: 运行状态
        _time_consume: 消耗时间
        _item_info: 监控项信息
    Returns:
    Raises:
    """
    data_item_id = item_info['data_item_id']
    data_item_name = item_info['data_item_name']
    item_type = item_info['item_type']
    data_cer_mem = item_info['data_cer_mem']
    data_url = item_info['item_url']
    add_run_ret = add_run_record(data_item_id, data_item_name, run_status, item_type, data_cer_mem, _time_consume, data_url)
    if not add_run_ret:
        return False

    update_lastruntime_ret = update_task_exectime(data_item_id)
    if not update_lastruntime_ret:
        return False

    #非正常情况填写报警记录
    if int(run_status) != 0:
        data_level = item_info['data_level']
        data_alert_mem = item_info['data_alert_mem']
        add_alert_ret = add_alert_record(0, data_item_name, data_level, item_type, _exc, data_item_id, data_alert_mem, data_cer_mem, data_url)
        if not add_alert_ret:
            return False
    return True

def get_multi_host(hostname):
    """
        查询多机房ip
    Args:
    Returns:
    Raises:
    """
    result = {}
    try:
        _scheduler_db = scheduler_db()
        query = "select item_status, apply_scope, item_name, host_name, host_ip from multi_hosts WHERE host_name = '" + str(hostname) + "'"
        result = _scheduler_db.findAll(query)
        _scheduler_db.close()
    except Exception as e:
        lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "get data from multi_hosts fail:" + str(e))
    return result

