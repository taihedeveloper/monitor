#encoding=utf-8
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide config function.
Authors: luohongcang(luohongcang@taihe.com)
Date:    2017/02/15
"""

import os
import string
import ConfigParser

class SchedulerConfig(object):
    """ class content:
    config function class
    """
    def __init__(self):
        """
            init函数
        Args:
        Returns:
        Raises:
        """
        try:
            self._config = ConfigParser.ConfigParser()
            path = os.path.split(os.path.realpath(__file__))[0] + '/../conf/scheduler.conf'
            self._config.read(path)
        except Exception, e:
            str_err = 'Read config file error: ' + str(e)
            print str_err

    def __del__(self):
        """
            del函数
        Args:
        Returns:
        Raises:
        """
        pass

    def get_config_str(self, section, key):
        """
            获取字符串配置
        Args:
        Returns:
        Raises:
        """
        return self._config.get(section, key)

    def get_config_int(self, section, key):
        """
            获取整数配置
        Args:
        Returns:
        Raises:
        """
        return self._config.getint(section, key)

    def get_config_boolean(self, section, key):
        """
            获取boolean型配置
        Args:
        Returns:
        Raises:
        """
        return self._config.getboolean(section, key)

def init():
    """
        init函数
    Args:
    Returns:
    Raises:
    """
    global _scheduler_config
    _scheduler_config = SchedulerConfig()

def get_config_str(section, key):
    """
        获取字符串配置
    Args:
    Returns:
    Raises:
    """
    global _scheduler_config
    return _scheduler_config.get_config_str(section, key)

def get_config_int(section, key):
    """
        获取整数配置
    Args:
    Returns:
    Raises:
    """
    global _scheduler_config
    return _scheduler_config.get_config_int(section, key)

def get_config_boolean(section, key):
    """
        获取boolean型配置
    Args:
    Returns:
    Raises:
    """
    global _scheduler_config
    return _scheduler_config.get_config_boolean(section, key)
