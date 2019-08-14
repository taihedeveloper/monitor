#encoding=utf-8

import sys
import os
import string
import logging
import scheduler_config

#日志操作
#日志级别大小关系：CRITICAL > ERROR > WARNING > INFO > DEBUG > NOTSET
class SchedulerLogger(object):
    def __init__(self, log_dir, warn_file, info_file):
        if os.path.exists(log_dir) and os.path.isdir(log_dir):
            #print log_dir, ' directory exists'
            pass
        else:
            os.mkdir(log_dir)

        formatter = logging.Formatter('%(asctime)s %(levelname)s %(message)s')

        warn_handle = logging.FileHandler(log_dir + "/" + warn_file)
        warn_handle.setLevel(logging.WARNING)
        warn_handle.setFormatter(formatter)
        self._warn_logger = logging.getLogger('server_warn')
        self._warn_logger.setLevel(logging.WARNING)
        self._warn_logger.addHandler(warn_handle)

        info_handle = logging.FileHandler(log_dir + "/" + info_file)
        info_handle.setLevel(logging.DEBUG)
        info_handle.setFormatter(formatter)
        self._info_logger = logging.getLogger('server_info')
        self._info_logger.setLevel(logging.DEBUG)
        self._info_logger.addHandler(info_handle)

    def __del__(self):
        pass

    def debug(self, msg):
        self._info_logger.debug(msg)

    def info(self, msg):
        self._info_logger.info(msg)

    def warning(self, msg):
        self._warn_logger.warning(msg)

    def error(self, msg):
        self._warn_logger.error(msg)

    def critical(self, msg):
        self._warn_logger.critical(msg)

def init(log_dir, warn_file, info_file):
    global _scheduler_logger
    _scheduler_logger = SchedulerLogger(log_dir, warn_file, info_file)

def debug(f, l, msg):
    global _scheduler_logger
    _scheduler_logger.debug("%s line:%d >> %s" % (f, l, msg))

def info(f, l, msg):
    global _scheduler_logger
    _scheduler_logger.info("%s line:%d >> %s" % (f, l, msg))

def warning(f, l, msg):
    global _scheduler_logger
    _scheduler_logger.warning("%s line:%d >> %s" % (f, l, msg))

def error(f, l, msg):
    global _scheduler_logger
    _scheduler_logger.error("%s line:%d >> %s" % (f, l, msg))

def critical(f, l, msg):
    global _scheduler_logger
    _scheduler_logger.critical("%s line:%d >> %s" % (f, l, msg))
