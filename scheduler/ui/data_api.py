#-*- coding=utf-8 -*-
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide data api check.
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

class data_api(object):
    """ class content:
    data api check class
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
        
    def data_api_main_func(self, data):
        """
            数据接口处理函数
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
        u_data_referer = data["referer"] #referer
        u_data_user_agent = data["user_agent"] #referer

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

        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "start data_api_main_func. task_id:" + str(u_data_item_id) + ". time:" + str(datetime.now()))

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
        item_info['item_type'] = Const.M_TYPE_DATA_API
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
                process_res, time_consume, exc = self.process_data(url, check_param)

                if process_res == 1 or process_res == 2:
                    if data_callback_url:
                        lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "data_api check fail, call url: " + str(data_callback_url))
                        self.__scheduler_http.setTimeout(5)
                        self.__scheduler_http.request(data_callback_url)
                item_info['item_url'] = url
                lib.common_method.add_process_record(process_res, time_consume, str(exc), item_info)
        else:
            for one in result:
                status = one["item_status"]
                apply_scope = one["apply_scope"]
                if int(status) <> 2 or (int(apply_scope) & 2) != 2:
                    continue
                str_ips = one['host_ip']
                host_ip_list = str_ips.split(',')
                for ip in host_ip_list:
                    if is_https:
                        url = "https://" + ip + uri
                    else:
                        url = "http://" + ip + uri
                    process_res, time_consume, exc = self.process_data(url, check_param)
                    if process_res == 1:
                        if data_callback_url:
                            lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "data_api check fail, call url: " + str(data_callback_url))
                            self.__scheduler_http.setTimeout(5)
                            self.__scheduler_http.request(data_callback_url)
                    item_info['item_url'] = url
                    lib.common_method.add_process_record(process_res, time_consume, str(exc), item_info)
        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "finish data_api_main_func. task_id:" + str(u_data_item_id) + ". time:" + str(datetime.now()))

    def process_data(self, url, check_param):
        """
            检测数据接口是否满足条件
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
                ret_status, ret_info = self.__scheduler_http.dataapi_post(url, data_post["Json"], _data_time_out)
            else:
                post_header = {"Content-Type":"application/x-www-form-urlencoded"}
                self.__scheduler_http.setHeaders(post_header)
                ret_status, ret_info = self.__scheduler_http.dataapi_post(url, data_post["urlEncode"], _data_time_out)
            if int(ret_status) == 3:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP POST EXCEPT:connect timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 2:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP POST EXCEPT:timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 1:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP POST EXCEPT:" + str(ret_info))
                return 1, ((datetime.now()-start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            else:
                request_ret = ret_info
                ret_code = request_ret.status_code
                if int(ret_code) >= 400:
                    return 4, ((datetime.now()-start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, "http code: " + str(ret_code)
                lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP POST status_code:" + str(ret_code))
        else:
            ret_status, ret_info = self.__scheduler_http.dataapi_get(url, _data_time_out)
            if int(ret_status) == 3:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP GET EXCEPT:connect timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 2:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno,"HTTP GET EXCEPT:timeout")
                return 2, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            elif int(ret_status) == 1:
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP GET EXCEPT:" + str(ret_info))
                return 1, ((datetime.now() - start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, ret_info
            else:
                request_ret = ret_info
                ret_code = request_ret.status_code
                if int(ret_code) >= 400:
                    return 4, ((datetime.now()-start_micro).seconds * 1e6 + (datetime.now() - start_micro).microseconds) / 1000.0, "http code: " + str(ret_code)
                lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "HTTP GET status_code:" + str(ret_code))
        raw_page_result = request_ret.text.encode("utf-8")
        if url.find("callback=") != -1:
            page_result = re.match(r'.*\((.*)\).*', raw_page_result).groups()[0]
        else:
            page_result = raw_page_result
        page_res = json.loads(page_result)
        for rule_one in ["equal", "notnull", "key_exist", "reg", "count"]:
            if not isinstance(data_criterion, dict):
                return 1, float(request_ret.elapsed.microseconds / 1000.0), "criterion is not right"
            if not data_criterion.has_key(rule_one):
                continue
            retcode, retmes = self.mod_process(data_criterion[rule_one], page_res, rule_one)
            if not retcode:
                return 1, float(request_ret.elapsed.microseconds / 1000.0), retmes
        return 0, float(request_ret.elapsed.microseconds / 1000.0), retmes

    def mod_process(self, rule_data, page_data, mod_name):
        """
            根据相应模式做匹配
        Args:
            rule_data: 匹配数据
            page_data: 页面数据
            mod_name: 匹配规则
        Returns:
        Raises:
        """
        
        for rule_one in rule_data:
            tmp_page_data = {}
            tmp_page_data = page_data
            field_list = rule_one["rule"].split("|")
            if mod_name == "equal":
                retcode, retmes = self.equal_check(tmp_page_data, field_list)
            elif mod_name == "notnull":
                retcode, retmes = self.notnull_check(tmp_page_data, field_list)
            elif mod_name == "key_exist":
                retcode, retmes = self.key_exist_check(tmp_page_data, field_list)
            elif mod_name == "reg":
                retcode, retmes = self.reg_check(tmp_page_data, field_list)
            elif mod_name == "count":
                retcode, retmes = self.count_check(tmp_page_data, field_list)
            else:
                lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "Mod Illegal. Mod Name:" + str(mod_name))
                continue
            if not retcode:
                return retcode, retmes
        return True, "success"

    def notnull_check(self, tmp_page_data, field_list):
        """
            非空判断
        Args:
            tmp_page_data: 
            field_list: 
        Returns:
        Raises:
        """
        try:
            for index in range(len(field_list)):
                if index == len(field_list) - 1:
                    mult_field = field_list[index].split("&")
                    for single_field in mult_field:
                        single_field = str(single_field)
                        if not tmp_page_data:
                            return False, "return data has no value"
                        if not tmp_page_data.has_key(single_field):
                            return False, single_field + " key is not existed!!!"
                        if tmp_page_data[single_field] == None or tmp_page_data[single_field] == "":
                            return False, single_field + " value is Empty!!!"
                    return True, "success"
                if field_list[index] == "_all_":
                    if isinstance(tmp_page_data, list):
                        for index1 in range(len(tmp_page_data)):
                            retcode, retmes = self.notnull_check(tmp_page_data[index1], field_list[index + 1:])
                            if not retcode:
                                return retcode, str(retmes)
                        return True, "success"
                    elif isinstance(tmp_page_data, dict):
                        for key in tmp_page_data:
                            retcode, retmes = self.notnull_check(tmp_page_data[key], field_list[index + 1:])
                            if not retcode:
                                return retcode, str(retmes)
                        return True, "success"
                if isinstance(tmp_page_data, list):
                    if len(tmp_page_data) >= int(field_list[index].encode("utf-8")) + 1:
                        tmp_page_data = tmp_page_data[int(field_list[index].encode("utf-8"))]
                    else:
                        return False, "Index " + str(int(field_list[index].encode("utf-8"))) + " is not existed"
                else:
                    temp_field = field_list[index]
                    if not tmp_page_data:
                        return False, "return data has no value"
                    if not tmp_page_data.has_key(temp_field):
                        return False, temp_field + " key is not existed!!!"
                    tmp_page_data = tmp_page_data[temp_field]
        except Exception, e:
            return False, str(e) 
        else:
            return True,"success"
        
        
    def equal_check(self, tmp_page_data, field_list):
        """
            相等判断
        Args:
            tmp_page_data: 
            field_list: 
        Returns:
        Raises:
        """
        try:
            for index in range(len(field_list)):
                if index == len(field_list) - 1:
                    expect_val_field = field_list[index].encode("utf-8")
                    matc = re.match(r'(.*)<!(.*)!>$', expect_val_field)
                    single_field = matc.group(1)
                    field_value = matc.group(2)
                    single_field = str(single_field)
                    temp_page_rep = {}
                    if type(tmp_page_data) == dict:
                        for key, val in tmp_page_data.items():
                            if type(key) == unicode:
                                temp_page_rep[key.encode("utf-8")] = val
                            else:
                                temp_page_rep[key] = val
                        tmp_page_data = temp_page_rep
                    if not tmp_page_data:
                        return False, "return data has no value"
                    if not tmp_page_data.has_key(single_field):
                        return False, single_field + " key is not existed!!!"
                    if isinstance(tmp_page_data[single_field], int):
                        tmp_page_data[single_field] = str(tmp_page_data[single_field])
                    else:
                        tmp_page_data[single_field] = tmp_page_data[single_field].encode("utf-8")
                    if tmp_page_data[single_field] == str(field_value):
                       return True, "success"
                    else:
                        return False, "%s: %s" % (single_field, str(tmp_page_data[single_field])) + " not equal " + str(field_value)
                if field_list[index] == "_all_":
                    if isinstance(tmp_page_data, list):
                        for index1 in range(len(tmp_page_data)):
                            retcode, retmes = self.equal_check(tmp_page_data[index1], field_list[index + 1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                    elif isinstance(tmp_page_data, dict):
                        for key in tmp_page_data:
                            retcode, retmes = self.equal_check(tmp_page_data[key], field_list[index + 1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                if isinstance(tmp_page_data, list):
                    if len(tmp_page_data) >= int(field_list[index].encode("utf-8")) + 1:
                        tmp_page_data = tmp_page_data[int(field_list[index].encode("utf-8"))]
                    else:
                        return False, "Index " + str(int(field_list[index].encode("utf-8"))) + " is not existed"
                else:
                    temp_field = field_list[index]
                    if not tmp_page_data:
                        return False, "return data has no value"
                    if not tmp_page_data.has_key(temp_field):
                        return False, temp_field + " key is not existed!!!"
                    tmp_page_data = tmp_page_data[temp_field]
        except Exception, e:
            return False, str(e) 
        else:
            return True, "success"

    def key_exist_check(self, tmp_page_data, field_list):
        """
            键是否存在判断
        Args:
            tmp_page_data: 
            field_list: 
        Returns:
        Raises:
        """
        try:
            for index in range(len(field_list)):
                if index == len(field_list) - 1:
                    mult_field = field_list[index].split("&")
                    for single_field in mult_field:
                        single_field = str(single_field)
                        if not tmp_page_data:
                            return False, "return data has no value"
                        if not tmp_page_data.has_key(single_field):
                            return False, single_field + " key is not existed!!!"
                    return True, "success"
                if field_list[index] == "_all_":
                    if isinstance(tmp_page_data, list):
                        for index1 in range(len(tmp_page_data)):
                            retcode, retmes = self.key_exist_check(tmp_page_data[index1], field_list[index+1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                    elif isinstance(tmp_page_data, dict):
                        for key in tmp_page_data:
                            retcode, retmes = self.key_exist_check(tmp_page_data[key], field_list[index+1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                if isinstance(tmp_page_data, list):
                    if len(tmp_page_data) >= int(field_list[index].encode("utf-8")) + 1:
                        tmp_page_data = tmp_page_data[int(field_list[index].encode("utf-8"))]
                    else:
                        return False, "Index " + str(int(field_list[index].encode("utf-8"))) + " is not existed"
                else:
                    temp_field = field_list[index]
                    if not tmp_page_data:
                        return False, "return data has no value"
                    if not tmp_page_data.has_key(temp_field):
                        return False, temp_field + " key is not existed!!!"
                    tmp_page_data = tmp_page_data[temp_field]
        except Exception, e:
            return False, str(e)
        else:
            return True, "success"

    def reg_check(self, tmp_page_data, field_list):
        """
            正则判断
        Args:
            tmp_page_data: 
            field_list: 
        Returns:
        Raises:
        """
        try:
            for index in range(len(field_list)):
                if index == len(field_list) - 1:
                    if type(field_list[index]) == unicode:
                        field_list[index] = field_list[index].encode("utf-8")
                    matc = re.match(r"(.*)<!#(.*)#!>$", field_list[index])
                    single_field = matc.group(1)
                    field_value = matc.group(2)
                    if isinstance(tmp_page_data, dict):
                        if not tmp_page_data:
                            return False, "return data has no value"
                        if not tmp_page_data.has_key(single_field):
                            return False, single_field + " key is not existed!!!"
                    elif isinstance(tmp_page_data, list):
                        if int(single_field) >= len(tmp_page_data) or int(single_field) < 0:
                            return False, single_field + " key is not existed!!!"
                        single_field = int(single_field)
                    else:
                        return False, single_field + " does not has value!!!"
                    tmp_page_str = tmp_page_data[single_field]
                    if type(field_value) == unicode:
                        field_value = field_value.encode('utf-8')
                    if type(tmp_page_str) == unicode:
                        tmp_page_str = tmp_page_str.encode('utf-8')
                    if re.match(field_value, str(tmp_page_str)) or re.search(field_value, str(tmp_page_str)):
                        return True, "success"
                    else:
                        return False, "field:%s %s no match %s" % (single_field, field_value, str(tmp_page_str))
                if field_list[index] == "_all_":
                    if isinstance(tmp_page_data, list):
                        for index1 in range(len(tmp_page_data)):
                            retcode, retmes = self.reg_check(tmp_page_data[index1], field_list[index+1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                    elif isinstance(tmp_page_data, dict):
                        for key in tmp_page_data:
                            retcode, retmes = self.reg_check(tmp_page_data[key], field_list[index+1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                if isinstance(tmp_page_data, list):
                    if len(tmp_page_data) >= int(field_list[index].encode("utf-8")) + 1:
                        tmp_page_data = tmp_page_data[int(field_list[index].encode("utf-8"))]
                    else:
                        return False, "Index " + str(int(field_list[index].encode("utf-8"))) + " is not existed"
                else:
                    temp_field = field_list[index]
                    if not tmp_page_data:
                        return False, "return data has no value"
                    if not tmp_page_data.has_key(temp_field):
                        return False, temp_field + " key is not existed!!!"
                    tmp_page_data = tmp_page_data[temp_field]
        except Exception, e:
            return False, str(e) 
        else:
            return True, "success"

    def count_check(self, tmp_page_data, field_list):
        """
            数量判断
        Args:
            tmp_page_data: 
            field_list: 
        Returns:
        Raises:
        """
        try:
            for index in range(len(field_list)):
                if index == len(field_list) - 1:
                    matc = re.match(r"(.*)<!(.*)!>$", field_list[index])
                    single_field = matc.group(1)
                    field_value = matc.group(2)
                    single_field = str(single_field)
                    count_min = int(field_value.split(",")[0].encode("utf-8"))
                    count_max = int(field_value.split(",")[1].encode("utf-8"))
                    if not tmp_page_data:
                        return False, "return data has no value"
                    if not tmp_page_data.has_key(single_field):
                        return False, single_field + " key is not existed!!!"
                    if count_min <= len(tmp_page_data[single_field]) and len(tmp_page_data[single_field]) <= count_max:
                        return True, "success"
                    else:
                        return False, "count:%s no in count_min:%s - count_max:%s" % (len(tmp_page_data[single_field]), count_min, count_max)
                if field_list[index] == "_all_":
                    if isinstance(tmp_page_data, list):
                        for index1 in range(len(tmp_page_data)):
                            retcode, retmes = self.count_check(tmp_page_data[index1], field_list[index + 1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                    elif isinstance(tmp_page_data, dict):
                        for key in tmp_page_data:
                            retcode, retmes = self.count_check(tmp_page_data[key], field_list[index + 1:])
                            if not retcode:
                                return retcode, retmes
                        return True, "success"
                if isinstance(tmp_page_data, list):
                    if len(tmp_page_data) >= int(field_list[index].encode("utf-8")) + 1:
                        tmp_page_data = tmp_page_data[int(field_list[index].encode("utf-8"))]
                    else:
                        return False, "Index " + str(int(field_list[index].encode("utf-8"))) + " is not existed"
                else:
                    temp_field = field_list[index]
                    if not tmp_page_data:
                        return False, "return data has no value"
                    if not tmp_page_data.has_key(temp_field):
                        return False, temp_field + " key is not existed!!!"
                    tmp_page_data = tmp_page_data[temp_field]
        except Exception, e:
            return False, str(e)
        else:
            return True, "success"

