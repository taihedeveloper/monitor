#coding=utf-8
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide http availability check.
Authors: chengbiao(chengbiao@taihe.com), luohongcang(luohongcang@taihe.com)
Date:    2017/02/24
"""
import os
import sys
import io
import json
import time
import re
import socket
import urllib
import urllib2
from datetime import datetime

sys.path.append('..')
import lib.scheduler_logger
import lib.common_method
from lib.scheduler_http import scheduler_http
from lib.const import Const

class http_availability(object):
    """ class content:
    http availability check class
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
        self.__scheduler_http.setAllowRedirect(False)

    def process_func(self, data):
        """
            http状态码处理函数
        Args:
            data: 处理函数所需参数列表
        Returns:
        Raises:
        """
        u_data_url = data["url"] #监控url
        u_data_post = data["post"] #post json数据
        u_data_user_name = data["monitoruser"] #机器人用户名
        u_data_user_passwd = data['userpasswd'] #机器人密码, 32位md5小写
        u_data_time_out = data["time_out"] #超时时间
        u_data_criterion = data["criterion"] #匹配规则
        u_data_monitor_arg = data["monitor_arg"] #是否添加monitor参数
        u_data_callback_url = data["callback_url"] #回调url
        u_data_referer = data["referer"] #referer值
        u_data_user_agent = data['user_agent'] #ua值 

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
            lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "luohongcang" + str(u_data_item_id))
            return

        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "start process_func. task_id:" + str(u_data_item_id) + ". time:" + str(datetime.now()))

        #把Unicode编码数据转成utf8编码数据
        data_item_id = u_data_item_id
        data_url = u_data_url
        data_post = u_data_post
        data_user_name = u_data_user_name
        data_passwd = u_data_user_passwd
        data_time_out = u_data_time_out
        data_criterion = json.loads(u_data_criterion)
        data_monitor_arg = u_data_monitor_arg
        data_callback_url = u_data_callback_url
        data_referer = u_data_referer
        data_user_agent = u_data_user_agent

        data_level = u_data_level 
        data_cer_mem = u_data_cer_mem 
        data_alert_mem = u_data_alert_mem 
        data_item_name = u_data_item_name
        data_type = u_data_type

        self.set_http_property(data_user_name, data_passwd, data_referer, data_user_agent)
        item_info = {}
        item_info['data_item_id'] = data_item_id
        item_info['data_item_name'] = data_item_name
        item_info['item_type'] = Const.M_TYPE_HTTP_STATUS
        item_info['data_cer_mem'] = data_cer_mem
        item_info['data_level'] = data_level
        item_info['data_alert_mem'] = data_alert_mem

        check_param = {}
        check_param['data_time_out'] = data_time_out
        check_param['data_post'] = data_post
        check_param['data_criterion'] = data_criterion
        check_param['data_monitor_arg'] = data_monitor_arg

        #判断url是否为https请求
        is_https = False
        if data_url.find("https:") != -1:
            is_https = True

        #多机房ip替换
        proto, rest = urllib.splittype(data_url)
        hostname, uri = urllib.splithost(rest)
        result = lib.common_method.get_multi_host(hostname)

        if not result:
            if hostname:
                url = ""
                if is_https:
                    url = "https://" + hostname + uri
                else:
                    url = "http://" + hostname + uri
                ch_res, time_consume, exc = self.check_httpcode(url, check_param)
                if ch_res == 1 or ch_res == 2:
                    if data_callback_url:
                        lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "http_code check fail, call url: " + str(data_callback_url))
                        self.__scheduler_http.setTimeout(5)
                        self.__scheduler_http.request(data_callback_url)
                item_info['item_url'] = url
                lib.common_method.add_process_record(ch_res, time_consume, str(exc), item_info)
        else:
            for one in result:
                status = one["item_status"]
                apply_scope = one["apply_scope"]
                if int(status) <> 2 or (int(apply_scope) & 1) != 1:
                    continue
                str_ips = one['host_ip']
                host_ip_list = str_ips.split(',')
                for ip in host_ip_list:
                    if is_https:
                        url = "https://" + ip + uri
                    else:
                        url = "http://" + ip + uri
                    ch_res, time_consume, exc = self.check_httpcode(url, check_param)
                    if ch_res == 1:
                        if data_callback_url:
                            lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "http_code check fail, call url %s" % (data_callback_url))
                            self.__scheduler_http.setTimeout(5)
                            self.__scheduler_http.request(data_callback_url)
                    item_info['item_url'] = url
                    lib.common_method.add_process_record(ch_res, time_consume, str(exc), item_info)
        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "finish process_func. task_id:" + str(u_data_item_id) + ". time:" + str(datetime.now()))

    def check_httpcode(self, url, check_param):
        """
            检测http状态码是否满足条件
        Args:
            url: 待检测url
            check_param: 相关处理参数
        Returns:
        Raises:
        """
        data_time_out = check_param['data_time_out']
        data_post = check_param['data_post']
        data_criterion = check_param['data_criterion']
        data_monitor_arg = check_param['data_monitor_arg']
        _data_time_out = float(data_time_out) / 1000.0
        if data_post and not isinstance(data_post, dict):
            data_post = json.loads(data_post)
        if data_monitor_arg and int(data_monitor_arg) == 1:
            if url.find("?") == -1:
                url = url + "?_plat_=monitor"
            else:
                url = url + "&_plat_=monitor"
        start_micro = datetime.now()

        if data_post and isinstance(data_post, dict) and ((data_post.has_key('Json') and data_post['Json']) or (data_post.has_key('urlEncode') and data_post['urlEncode'])):
            if data_post.has_key('Json'):
                ret_status, ret_info = self.__scheduler_http.httpcode_post(url, data_post["Json"], _data_time_out)
            else:
                post_header = {"Content-Type":"application/x-www-form-urlencoded"}
                self.__scheduler_http.setHeaders(post_header)
                ret_status, ret_info = self.__scheduler_http.httpcode_post(url, data_post["urlEncode"], _data_time_out)
            if int(ret_status) == 3:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP POST EXCEPT:connect timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 2:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP POST EXCEPT:timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 1:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP POST EXCEPT:" + str(ret_info))
                return 1, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            else:
                request_ret = ret_info
                ret_code = request_ret.status_code
        else:
            ret_status, ret_info = self.__scheduler_http.httpcode_get(url, _data_time_out)
            if int(ret_status) == 3:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP GET EXCEPT:connect timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 2:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP GET EXCEPT:timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 1:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP GET EXCEPT:" + str(ret_info))
                return 1, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            else:
                request_ret = ret_info
                ret_code = request_ret.status_code

        try:
            status_code_list = data_criterion['http_code'].split(',')
            ret_code = str(ret_code)
            if ret_code in status_code_list:
                return 0, float(request_ret.elapsed.microseconds / 1000.0), "success"
            else:
                return 1, float(request_ret.elapsed.microseconds / 1000.0), "http status_code is: " + str(ret_code) + ", not in chosen status code"
        except Exception as e:
            lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "Data in database not in rules")
            return 1, float(request_ret.elapsed.microseconds / 1000.0), "not in chosen status code"

