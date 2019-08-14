#-*- coding: utf-8 -*-
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide http related function.
Authors: zhangjunxing(zhangjunxing@taihe.com) luohongcang(luohongcang@taihe.com)
Date:    2017/02/14
"""

import json
import os
import sys
import requests

import scheduler_logger
from const import Const

class scheduler_http(object):
    """ class content:
    http related function class
    """

    def __init__(self):
        """
            init函数
        Args:
        Returns:
        Raises:
        """
        self.__headers = {'user-agent': 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36'}
        self.__cookies = dict = {"" : ""}
        self.__params = {}
        self.__timeout = 4
        self.__allow_redirects = True
        self.__proxies = {}

    def setCookies(self, cookies):
        """
            设置cookie
        Args:
            cookies: cookies
        Returns:
        Raises:
        """
        self.__cookies = cookies
        return self

    def setHeaders(self, headers):
        """
            设置头信息
        Args:
            headers: headers
        Returns:
        Raises:
        """
        self.__headers = headers
        return self

    def setParams(self, params = {}):
        """
            设置参数
        Args:
            params: 参数列表
        Returns:
        Raises:
        """
        self.__params = params
        return self

    def setTimeout(self, timeout = 1):
        """
            设置超时时间
        Args:
            timeout: 超时时间
        Returns:
        Raises:
        """
        self.__timeout = timeout
        return self

    def setAllowRedirect(self, allow_redirects = True):
        """
            是否允许302跳转
        Args:
            allow_redirects: 是否允许字段
        Returns:
        Raises:
        """
        self.__allow_redirects = allow_redirects
        return self

    def setProxies(self, proxies):
        """
            设置访问代理
        Args:
            proxies: 代理地址
        Returns:
        Raises:
        """
        if Const.RUNTIME != "develop":
            self.__proxies = proxies
        return self
    
    def getHeader(self):
        """
            获取http头
        Args:
        Returns:
        Raises:
        """
        return self.__headers

    def request(self, url):
        """
            发送get请求
        Args:
            url: url链接
        Returns:
        Raises:
        """
        try:
            self.__proxies = {}
            r = requests.get(url = url, headers = self.__headers, cookies = self.__cookies, timeout = self.__timeout, allow_redirects = self.__allow_redirects, proxies = self.__proxies)
            return r.content
        except BaseException, error:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
        return False

    def webpage_request(self, url, time_out):
        """
            数据接口get方法
        Args:
            url: url链接
            time_out: 超时时间
        Returns:
        Raises:
        """
        try:
            self.__proxies = {}
            request_ret = requests.get(url = url, headers = self.__headers, cookies = self.__cookies, timeout = time_out, allow_redirects = self.__allow_redirects, proxies = self.__proxies)
        except requests.exceptions.ConnectTimeout as e:
            return 3, "Http Request ConnectTimeout("+str(time_out)+"): Url " + url
        except requests.exceptions.Timeout as e:
            return 2, "Http Request Timeout("+str(time_out)+"): Url " + url
        except Exception as e:
            return 1, "Http Request Error occurred: Url " + url
        else:
            return 0, request_ret

    def post(self, url, post_content):
        """
            发送post请求
        Args:
            url: url链接
            post_content: post参数
        Returns:
        Raises:
        """
        try:
            self.__proxies = {}
            r = requests.post(url = url, data = post_content, headers = self.__headers, cookies = self.__cookies, timeout = self.__timeout, allow_redirects =self.__allow_redirects, proxies = self.__proxies)
            return r.content
        except BaseException, error:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
        return False

    def getStatus(self, url):
        """
            获得http状态码
        Args:
            url: url链接
        Returns:
        Raises:
        """
        try:
            r = requests.head(url = url, allow_redirects = self.__allow_redirects, proxies = self.__proxies)
            return r.status_code
        except BaseException, error:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
        return False

    def getJson(self, url):
        """
            获得json内容
        Args:
            url: url链接
        Returns:
        Raises:
        """
        try:
            r = requests.get(url, proxies = self.__proxies)
            return r.json()
        except BaseException, error:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
        return False

    def toJson(self, content):
        """
            转为json对象
        Args:
            content: 转换内容
        Returns:
        Raises:
        """
        s = {}
        try:
            s = json.loads(content)
        except ValueError, error:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
        return s
    
    def getToken(self, username, passwd):
        """
            获取机器人用户token
        Args:
            username: 用户名
            passwd: 密码(md5 32位小写)
        Returns:
        Raises:
        """
        cookies = {}
        cookies['device_id'] = 'monitordevice'
        cookies['device_type'] = '1'
        cookies['tpl'] = 'baidu_music'
        _session_client = requests.Session()
        url = 'https://passport.qianqian.com/v2/api/login?'
        url = url + 'login_id=' + str(username) + '&password=' + str(passwd) + '&login_type=1'
        token = ''
        try:
            ret = _session_client.get(url, cookies = cookies, proxies = self.__proxies, timeout = 1)
            cookies_ret = ret.cookies
            token = cookies_ret['token_']
        except BaseException, error:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(error))
        return token

    def httpcode_get(self, url, data_time_out):
        """
            http状态码get方法
        Args:
            url: url链接
            data_time_out: 超时时间
        Returns:
        Raises:
        """
        try:
            self.__proxies = {}
            request_ret = requests.get(url, headers = self.__headers, cookies = self.__cookies, timeout = data_time_out, allow_redirects =self.__allow_redirects, proxies = self.__proxies)
        except requests.exceptions.ConnectTimeout as e:
            return 3, url + " ConnectTimeout."
        except requests.exceptions.Timeout as e:
            return 2, url + ' get data timeout.'
        except Exception as e:
            return 1, e
        else:
            return 0, request_ret
    
    def httpcode_post(self, url, post_data, data_time_out):
        """
            http状态码post方法
        Args:
            url: url链接
            post_data: post参数
            data_time_out: 超时时间
        Returns:
        Raises:
        """
        try:
            self.__proxies = {}
            request_ret = requests.post(url, data = post_data, headers = self.__headers, cookies = self.__cookies, timeout = data_time_out, allow_redirects =self.__allow_redirects, proxies = self.__proxies)
        except requests.exceptions.ConnectTimeout as e:
            return 3, url + " ConnectTimeout."
        except requests.exceptions.Timeout as e:
            return 2, url + ' get data timeout.'
        except Exception as e:
            return 1, e
        else:
            return 0, request_ret

    def dataapi_get(self, url, data_time_out):
        """
            数据接口get方法
        Args:
            url: url链接
            data_time_out: 超时时间
        Returns:
        Raises:
        """
        try:
            self.__proxies = {}
            request_ret = requests.get(url, headers = self.__headers, cookies = self.__cookies, timeout = data_time_out, allow_redirects =self.__allow_redirects, proxies = self.__proxies)
        except requests.exceptions.ConnectTimeout as e:
            return 3, url + " ConnectTimeout."
        except requests.exceptions.Timeout as e:
            return 2, url + ' get data timeout.'
        except Exception as e:
            return 1, e
        else:
            return 0, request_ret

    def dataapi_post(self, url, post_data, data_time_out):
        """
            数据接口post方法
        Args:
            url: url链接
            post_data: post参数
            data_time_out: 超时时间
        Returns:
        Raises:
        """
        try:
            self.__proxies = {}
            request_ret = requests.post(url, data = post_data, headers = self.__headers, cookies = self.__cookies, timeout = data_time_out, allow_redirects =self.__allow_redirects, proxies = self.__proxies)
        except requests.exceptions.ConnectTimeout as e:
            return 3, url + " ConnectTimeout."
        except requests.exceptions.Timeout as e:
            return 2, url + ' get data timeout.'
        except Exception as e:
            return 1, e
        else:
            return 0, request_ret

