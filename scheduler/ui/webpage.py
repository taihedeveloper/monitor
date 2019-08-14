#-*- coding: utf-8 -*-
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide webpage check.
Authors: zhangjunxing(zhangjunxing@taihe.com) luohongcang(luohongcang@taihe.com)
Date:    2017/02/14
"""

import sys
import os
import urllib
import time
from datetime import datetime

from lxml import etree

sys.path.append("..")
import lib.scheduler_config
import lib.scheduler_logger
from lib.scheduler_http import scheduler_http
from lib.scheduler_db import scheduler_db
import lib.scheduler_db
from lib.const import Const
import lib.common_method

class webpage(object):
    """ class content:
    webpage check class
    """
    def __init__(self, proxies):
        """
            init函数
        Args:
            proxies: 外网代理ip
        Returns:
        Raises:
        """
        self.__scheduler_http = scheduler_http()
        self.__scheduler_http.setProxies(proxies)

    def setData(self, data):
        """
            设置参数
        Args:
            data: 参数
        Returns:
        Raises:
        """
        self.data = data
        return self

    def command(self, s, command):
        """
            访问解析函数
        Args:
            url: 页面url
            command: 判断规则
        Returns:
        Raises:
        """
        try:
            command = unicode(command, "UTF-8")
            res = s.xpath(command)
            if not res:
                return False, 'not found'
            else:
                return res, 'found'
        except Exception, error:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "command error:" + str(error))
            return False, str(error)
    
    def set_http_property(self, data_user_name, data_passwd, data_referer, data_user_agent):
        """
            设置http属性
        Args:
            data_user_name: 机器人用户名
            data_passwd: 机器人密码
            data_referer: referer
            data_user_agent: ua
        Returns:
        Raises:
        """
        token_ = ""
        if data_user_name:
            token_ = self.__scheduler_http.getToken(data_user_name, data_passwd)
        if token_:
            cookies = {'token_': token_}
            self.__scheduler_http.setCookies(cookies)

        header = self.__scheduler_http.getHeader()
        if data_referer:
            header['referer'] = data_referer
        if data_user_agent:
            header['user-agent'] = data_user_agent
        self.__scheduler_http.setHeaders(header)

    def execute(self, data):
        """
            页面元素处理函数
        Args:
            data: 处理函数所需参数列表
        Returns:
        Raises:
        """
        u_url = data['url']
        u_criterion = data['criterion']

        u_data_user_name = data["monitoruser"] #机器人用户名
        u_data_user_passwd = data['userpasswd'] #机器人密码, 32位md5小写
        u_data_time_out = data["time_out"] #超时时间
        u_data_callback_url = data["callback_url"] #回调url
        u_data_referer = data["referer"] #referer
        u_data_user_agent = data["user_agent"] #ua

        u_data_item_id = data["item_id"] #监控项id
        u_data_level = data["level"] #监控分级
        u_data_cer_mem = data["cer_mem"] #权限组
        u_data_alert_mem = data["alert_mem"] #报警组
        u_data_item_name = data["item_name"] #监控项名称
        u_data_type = data["type"] #监控项类型

        start_time = data['start_time']
        end_time = data['end_time']
        now_time = time.strftime("%H:%M")
        if not (now_time >= start_time and now_time <= end_time):
            return

        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "start webpage_check_func. task_id:" + str(u_data_item_id) + ". time:" + str(datetime.now()))

        #把Unicode编码数据转成utf8编码数据
        url = u_url
        criterion = u_criterion
        data_user_name = u_data_user_name
        data_passwd = u_data_user_passwd
        data_time_out = u_data_time_out
        data_callback_url = u_data_callback_url
        data_referer = u_data_referer
        data_user_agent = u_data_user_agent

        data_item_id = u_data_item_id
        data_level = u_data_level
        data_cer_mem = u_data_cer_mem
        data_alert_mem = u_data_alert_mem 
        data_item_name = u_data_item_name
        data_type = u_data_type

        self.set_http_property(data_user_name, data_passwd, data_referer, data_user_agent)

        item_info = {}
        item_info['data_item_id'] = data_item_id
        item_info['data_item_name'] = data_item_name
        item_info['item_type'] = Const.M_TYPE_WEBPAGE
        item_info['data_cer_mem'] = data_cer_mem
        item_info['data_level'] = data_level
        item_info['data_alert_mem'] = data_alert_mem
        item_info['item_url'] = url

        check_param = {}
        check_param['data_criterion'] = criterion
        check_param['data_time_out'] = data_time_out
        process_res, time_consume, exc = self.check_webpage(url, check_param)

        if process_res == 1:
            lib.common_method.add_process_record(Const.RUN_RECORD_ERROR, time_consume, str(exc), item_info)
            if data_callback_url:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "webpage check fail, call url: " + str(data_callback_url))
                self.__scheduler_http.setTimeout(5)
                self.__scheduler_http.request(data_callback_url)
        elif process_res == 0:
            lib.common_method.add_process_record(Const.RUN_RECORD_OK, time_consume, str(exc), item_info)
        
        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "finish webpage_check_func. task_id:" + str(u_data_item_id) + ". time:" + str(datetime.now()))

    def check_webpage(self, url, check_param):
        criterion = check_param['data_criterion']
        time_out = check_param['data_time_out']

        start_micro = datetime.now()
        commands = criterion.split("\n")
        execMessage = url + "\n"
        total = len(commands)
        
        if int(time_out) != 0:
            time_out = int(time_out) / 1000
  
        ret_status, ret_reqinofo = self.__scheduler_http.webpage_request(url, time_out)
        if int(ret_status) != 0:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, ret_reqinofo)
            return 1, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_reqinofo
        else:
            req_content = ret_reqinofo.content
            req_code = ret_reqinofo.status_code
            if req_code != 200:
                lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP GET status_code:" + str(req_code))
                return 1, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, "HTTP GET status_code:" + str(req_code)
          
        try:
            xpaths = etree.HTML(req_content)
        except Exception, error:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "request url:" + url + " etree error:" + str(error))
            return 1, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, str(error)

        counter = 0
        for command in commands:
            if not command.strip():
                continue
            if command.find(".length") != -1:
                b, message = self.count(xpaths, command)
                ispass = 'Not Pass'
                if b == True:
                    counter += 1
                    ispass = 'Pass'
                execMessage += command + "\n" + message + "\n" + ispass + "\n"
            elif command.find(".text()") != -1:
                b, message = self.equals(xpaths, command)
                ispass = 'Not Pass'
                if b == True:
                    counter += 1
                    ispass = 'Pass'
                execMessage += command + "\n" + message + "\n" + ispass + "\n"
            else:
                b, message = self.command(xpaths, command)
                ispass = 'Not Pass'
                if b != False:
                    counter += 1
                    ispass = 'Pass'
                execMessage += command + "\n" + message + "\n" + ispass + "\n"
        
        if total != counter:
            return 1, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, execMessage
        else:
            return 0, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, execMessage

    def equals(self, s, command):
        """
            相等判断函数
        Args:
            url: 页面url
            command: 判断规则
        Returns:
        Raises:
        """
        execCommand = ""
        try:
            commandIndex = command.find(".text()")
            if commandIndex != -1:
                execCommand = '%stext()' % command[0:commandIndex]
                condition = command[commandIndex+7:]
                r = s.xpath(execCommand)
                mathText = r[0].encode('utf8')
                execCommand =  "'%s'%s" % (mathText, condition)
                b = eval(execCommand)
                return b, execCommand
        except Exception, error:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "check criterion error:" + str(error))
            return False, str(error)

    def count(self, s, command):
        """
            数量判断函数
        Args:
            url: 页面url
            command: 判断规则
        Returns:
        Raises:
        """
        execCommand = ""
        try:
            commandIndex = command.find(".length")
            if commandIndex != -1:
                execCommand = command[0:commandIndex]
                condition = command[commandIndex+7:]
                r = s.xpath(execCommand)
                c = len(r)
                execCommand =  '%s%s' % (c, condition)
                b = eval(execCommand)
                return b, execCommand
        except Exception, error:
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, "check criterion error:" + str(error))
            return False, str(error)

