#encoding=utf-8
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide main function.
Authors: luohongcang(luohongcang@taihe.com)
Date:    2017/02/24
"""

import sys
import os
import signal
import time

from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.executors.pool import ThreadPoolExecutor, ProcessPoolExecutor

import libnet.net_lib
import libnet.net_util
import libnet.net_define
import lib.scheduler_logger
import lib.scheduler_config
from lib.scheduler_db import scheduler_db
import lib.scheduler_db
import common.mcpack
import common.nshead
from ui.http_availability import http_availability
from ui.data_api import data_api
from ui.alert import alert
from ui.webpage import webpage

#全局任务分配变量
global scheduler

#发送邮件和短信的url
global mail_url
global message_url

#http和https外网代理
global proxies

def httpcode(data, proxies):
    """
        http状态码处理函数
    Args:
        data: 监控项相关信息
        proxies: 外网代理ip
    Returns:
    Raises:
    """
    _http_availability = http_availability(proxies)
    _http_availability.process_func(data)

def dataapi(data, proxies):
    """
        数据接口处理函数
    Args:
        data: 监控项相关信息
        proxies: 外网代理ip
    Returns:
    Raises:
    """
    _data_api = data_api(proxies)
    _data_api.data_api_main_func(data)

def webpage_check(data, proxies):
    """
        页面元素处理函数
    Args:
        data: 监控项相关信息
        proxies: 外网代理ip
    Returns:
    Raises:
    """
    _webpage = webpage(proxies)
    _webpage.execute(data)

def alarm(data):
    """
        报警逻辑处理函数
    Args:
        data: 监控项相关信息
        proxies: 外网代理ip
    Returns:
    Raises:
    """
    time.sleep(30) #sleep30s保证当前报警检测到本次执行结果
    _alert = alert()
    _alert.process(data)

def pack_data(sel_ret, mail_url, message_url):
    """
        监控项信息数据打包方法
    Args:
        sel_ret: 原始数据
        mail_url: 邮件服务url
        message_url: 短信服务url
    Returns:
    Raises:
    """
    data = {}
    taskid = int(sel_ret['id'])
    task_type = int(sel_ret['type'])
    criterion = str(sel_ret['criterion'])
    time_out = str(sel_ret['time_out'])
    user_name = str(sel_ret['username'])
    url = str(sel_ret['url'])
    level = str(sel_ret['level'])
    item_name = str(sel_ret['item_name'])
    post_content = str(sel_ret['post_content'])
    callback_url = str(sel_ret['callback_url'])
    monitor_arg = str(sel_ret['monitor_arg'])
    cer_mem = str(sel_ret['cer_mem'])
    alert_mem = str(sel_ret['alert_mem'])
    mail_count = str(sel_ret['mail_count'])
    message_count = str(sel_ret['message_count'])
    frequence = str(sel_ret['frequence'])
    product_line = str(sel_ret['product_line'])
    referer = str(sel_ret['referer'])
    user_agent = str(sel_ret['user_agent'])
    start_time = str(sel_ret['start_time'])
    end_time = str(sel_ret['end_time'])
    user_passwd = ""
    if user_name:
        try:
            user_sql = "SELECT password FROM robot_acount WHERE tel_no = " + str(user_name)
            _scheduler_db = scheduler_db()
            user_ret = _scheduler_db.findAll(user_sql)
            _scheduler_db.close()
            user_passwd = user_ret[0]['password']
        except Exception, error:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "get robot_user passwd fail. " + str(error))

    product_name = ""
    if product_line:
        try:
            product_name_sql = "SELECT product_name from product_lines where product_id = " + str(product_line)
            _scheduler_db = scheduler_db()
            product_name_ret = _scheduler_db.findAll(product_name_sql)
            _scheduler_db.close()
            product_name = product_name_ret[0]['product_name']
        except Exception, error:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "get product_name fail. " + str(error))

    data['item_id'] = taskid
    data['type'] = task_type
    data['criterion'] = criterion
    data['time_out'] = time_out
    data['monitoruser'] = user_name
    data['userpasswd'] = user_passwd
    data['url'] = url
    data['level'] = level
    data['item_name'] = item_name
    data['post'] = post_content
    data['callback_url'] = callback_url
    data['monitor_arg'] = monitor_arg
    data['cer_mem'] = cer_mem
    data['alert_mem'] = alert_mem
    data['mail_count'] = mail_count
    data['message_count'] = message_count
    data['frequence'] = frequence
    data['product_line'] = product_name
    data['mail_url'] = mail_url
    data['message_url'] = message_url
    data['referer'] = referer
    data['user_agent'] = user_agent
    data['start_time'] = start_time
    data['end_time'] = end_time
    return data

def handler(signum, frame):
    """
        进程信号函数
    Args:
        signum: 信号值
        frame: 
    Returns:
    Raises:
    """
    global is_exit
    is_exit = True
    lib.scheduler_logger.critical(os.path.basename(__file__), sys._getframe().f_lineno, "receive a signal %d, is_exit = %d"%(signum, is_exit))

def print_func(log_level, filename, lineno, log_str):
    """
        日志打印方法
    Args:
        log_level: 日志级别
        filename: 文件名
        lineno: 行号
        log_str: 日志目录
    Returns:
    Raises:
    """
    if log_level == libnet.net_define.NET_LOGLEVEL_CRITICAL:
        lib.scheduler_logger.info(filename, lineno, log_str)
    elif log_level == libnet.net_define.NET_LOGLEVEL_ERROR:
        lib.scheduler_logger.error(filename, lineno, log_str)
    elif log_level == libnet.net_define.NET_LOGLEVEL_WARNING:
        lib.scheduler_logger.warning(filename, lineno, log_str)
    elif log_level == libnet.net_define.NET_LOGLEVEL_DEBUG:
        lib.scheduler_logger.debug(filename, lineno, log_str)
    else:
        lib.scheduler_logger.info(filename, lineno, log_str)

def work_func(index, conn, head_pack, body_pack):
    """
        ODP API请求处理方法
        根据不同的监控项类型上线/下线监控项
        若为测试逻辑，走测试逻辑直接处理进行返回
    Args:
        index: 进程号
        conn: 进程连接
        head_pack: 消息头信息包
        body_pack: 消息体信息包
    Returns:
    Raises:
    """
    param_dict = common.mcpack.loads(body_pack)
    oper = int(param_dict['oper'])
    ret_dict = {}
    if oper == 3:
        try:
            taskid = int(param_dict['pro_id'])
            last_online_time = str(param_dict['last_online_time'])
            task_str = "task_" + str(taskid) + "_" + last_online_time
            alarm_str = "alarm_" + str(taskid) + "_" + last_online_time
            scheduler.remove_job(task_str)
            scheduler.remove_job(alarm_str)
            ret_dict = {'error_no': 0}
        except Exception, error:
            ret_dict = {'error_no': -1}
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "up item fail. error_msg: " + str(error))
    if oper == 4:
        try:
            taskid = int(param_dict['pro_id'])
            last_online_time = str(param_dict['last_online_time'])
            task_str = "task_" + str(taskid) + "_" + last_online_time
            alarm_str = "alarm_" + str(taskid) + "_" + last_online_time
            sql = "SELECT * FROM monitor_item WHERE id = " + str(taskid)
            _scheduler_db = scheduler_db()
            sel_ret = _scheduler_db.findAll(sql)
            _scheduler_db.close()
            task_type = int(sel_ret[0]['type'])
            frequence = int(sel_ret[0]['frequence'])
            data = pack_data(sel_ret[0], mail_url, message_url)
            if task_type == 0:
                scheduler.add_job(func = httpcode, args = (data, proxies,), trigger = 'interval', seconds = frequence * 60, id = task_str)
                scheduler.add_job(func = alarm, args = (data,), trigger = 'interval', seconds = frequence * 60, id = alarm_str)
            elif task_type == 1:
                scheduler.add_job(func = dataapi, args = (data, proxies,), trigger = 'interval', seconds = frequence * 60, id = task_str)
                scheduler.add_job(func = alarm, args = (data,), trigger = 'interval', seconds = frequence * 60, id = alarm_str)
            elif task_type == 2:
                scheduler.add_job(func = webpage_check, args = (data, proxies,), trigger = 'interval', seconds = frequence * 60, id = task_str)
                scheduler.add_job(func = alarm, args = (data,), trigger = 'interval', seconds = frequence * 60, id = alarm_str)
            ret_dict = {'error_no': 0}
        except Exception, error:
            ret_dict = {'error_no': -1}
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "down item fail. error_msg: " + str(error))
    if oper == 6:
        try:
            url = param_dict['url']
            item_type = int(param_dict['item_type'])
            monitor_arg = param_dict['monitor_arg']
            criterion = param_dict['criterion']
            post_content = param_dict['post_content']
            time_out = param_dict['time_out']
            
            user_name = param_dict['username']
            referer = param_dict['referer']
            user_agent = param_dict['user_agent']
            user_passwd = ""
            if user_name:
                try:
                    user_sql = "SELECT password FROM robot_acount WHERE tel_no = " + str(user_name)
                    _scheduler_db = scheduler_db()
                    user_ret = _scheduler_db.findAll(user_sql)
                    _scheduler_db.close()
                    user_passwd = user_ret[0]['password']
                except Exception, error:
                    lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "get robot_user passwd fail. " + str(error))

            check_param = {}
            check_param['data_time_out'] = time_out
            check_param['data_post'] = post_content
            check_param['data_criterion'] = criterion
            check_param['data_monitor_arg'] = monitor_arg

            if item_type == 0:
                _http_availability = http_availability(proxies)
                _http_availability.set_http_property(user_name, user_passwd, referer, user_agent)
                ch_res, time_consume, exc = _http_availability.check_httpcode(url, check_param)
                ret_dict = {'error_no': 0, 'check_status': ch_res, 'check_msg': str(exc)}
            elif item_type == 1:
                _data_api = data_api(proxies)
                _data_api.set_http_property(user_name, user_passwd, referer, user_agent)
                ch_res, time_consume, exc = _data_api.process_data(url, check_param)
                ret_dict = {'error_no': 0, 'check_status': ch_res, 'check_msg': str(exc)}
            elif item_type == 2:
                _webpage = webpage(proxies)
                _webpage.set_http_property(user_name, user_passwd, referer, user_agent)
                ch_res, time_consume, exc = _webpage.check_webpage(url, check_param)
                if exc == "":
                    exc = "criterion is not right"
                ret_dict = {'error_no': 0, 'check_status': ch_res, 'check_msg': str(exc)}
        except Exception, error:
            ret_dict = {'error_no': -1}
            lib.scheduler_logger.critical(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
    ret_pack = common.mcpack.dumps(ret_dict)
    common.nshead.nshead_write(conn, ret_pack)

if __name__ == '__main__':
    """
        main入口函数
        初始化各项配置，加载上线状态监控项
    Args:
    Returns:
    Raises:
    """
    global is_exit
    is_exit = False
    signal.signal(signal.SIGINT, handler)
    signal.signal(signal.SIGTERM, handler)
    signal.signal(signal.SIGQUIT, handler)

    #读取配置
    lib.scheduler_config.init()
    server_port = lib.scheduler_config.get_config_int('network', 'port')
    log_dir = lib.scheduler_config.get_config_str('log', 'path')
    warn_file = lib.scheduler_config.get_config_str('log', 'warnlog')
    info_file = lib.scheduler_config.get_config_str('log', 'infolog')
    io_number = lib.scheduler_config.get_config_int('async', 'io_number')
    work_number = lib.scheduler_config.get_config_int('async', 'work_number')
    is_coroutine = lib.scheduler_config.get_config_boolean('async', 'coroutine')

    #scheduler配置参数
    thread_num = lib.scheduler_config.get_config_int('scheduler', 'thread_num')
    process_num = lib.scheduler_config.get_config_int('scheduler', 'process_num')

    #初始化全局唯一的任务分配对象
    global scheduler
    executors = {
        'default': ThreadPoolExecutor(thread_num),
        'processpool': ProcessPoolExecutor(process_num)
    }
    scheduler = BackgroundScheduler(executors = executors)
    scheduler.start()

    #数据库配置
    db_host = lib.scheduler_config.get_config_str('database', 'host')
    db_port = lib.scheduler_config.get_config_int('database', 'port')
    db_user = lib.scheduler_config.get_config_str('database', 'username')
    db_passwd = lib.scheduler_config.get_config_str('database', 'password')
    db_db = lib.scheduler_config.get_config_str('database', 'db')
    lib.scheduler_db.set_param(db_host, db_port, db_user, db_passwd, db_db)

    #发送邮件和短信url
    global mail_url
    global message_url
    mail_url = lib.scheduler_config.get_config_str('alert', 'mailurl')
    message_url = lib.scheduler_config.get_config_str('alert', 'messageurl')

    #代理url
    global proxies
    http_proxy = lib.scheduler_config.get_config_str('proxy', 'http')
    https_proxy = lib.scheduler_config.get_config_str('proxy', 'https')
    proxies = {'http':http_proxy, 'https':https_proxy,}

    #初始化日志模块
    lib.scheduler_logger.init(log_dir, warn_file, info_file)
    lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, 'begin to run monitor server...')

    all_ret = {}
    #初始化mysql中的冷备数据
    try:
        all_sql = "SELECT * FROM monitor_item WHERE status = 2"
        _scheduler_db = scheduler_db()
        all_ret = _scheduler_db.findAll(all_sql)
        _scheduler_db.close()
    except Exception, error:
        lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))

    for online_item in all_ret:
        item_type = int(online_item['type'])
        taskid = int(online_item['id'])
        last_online_time = str(online_item['last_online_time'])
        task_str = "task_" + str(taskid) + "_" + last_online_time
        alarm_str = "alarm_" + str(taskid) + "_" + last_online_time
        frequence = int(online_item['frequence'])
        data = pack_data(online_item, mail_url, message_url)
        try:
            if item_type == 0:
                scheduler.add_job(func = httpcode, args = (data, proxies,), trigger = 'interval', seconds = frequence * 60, id = task_str)
                scheduler.add_job(func = alarm, args = (data,), trigger = 'interval', seconds = frequence * 60, id = alarm_str)
            elif item_type == 1:
                scheduler.add_job(func = dataapi, args = (data, proxies,), trigger = 'interval', seconds = frequence * 60, id = task_str)
                scheduler.add_job(func = alarm, args = (data,), trigger = 'interval', seconds = frequence * 60, id = alarm_str)
            elif item_type == 2:
                scheduler.add_job(func = webpage_check, args = (data, proxies,), trigger = 'interval', seconds = frequence * 60, id = task_str)
                scheduler.add_job(func = alarm, args = (data,), trigger = 'interval', seconds = frequence * 60, id = alarm_str)
        except Exception, error:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
        time.sleep(0.1)

    #启动网络服务，用于实时推送增删改的监控项
    libnet.net_lib.set_server('0.0.0.0', server_port)
    libnet.net_lib.set_io(is_coroutine, io_number)
    libnet.net_lib.set_work_number(work_number)
    libnet.net_lib.set_fd_timeout(360000000)
    libnet.net_lib.set_work_callback(work_func)
    libnet.net_lib.set_print_callback(print_func)
    libnet.net_lib.run()

    #监听退出信号
    while True:
        if is_exit:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, 'exiting process...')
            os._exit(0)
        time.sleep(0.01)

