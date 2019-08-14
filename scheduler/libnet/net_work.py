#encoding=utf-8

import sys
import os
import socket
import select
import errno
#import threadpool需要额外安装包
import threading
import string
import time
import struct
import net_struct
import net_util
import net_define

#工作线程类
class Worker(threading.Thread):
    def __init__(self, index, work_func):
        #显示调用父类初始化
        threading.Thread.__init__(self)
        self._index = index
        self._work_func = work_func

    def __del__(self):
        pass

    def run(self):
        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, 'begin to run work thread: %d' % (self._index))
        server_path = os.path.split(os.path.realpath(__file__))[0]
        while True:
            net_struct.GlobalStore._work_task_sem.acquire()
            work_list = []
            #拷贝工作任务列表到临时list中
            net_struct.GlobalStore._work_task_lock.acquire()
            for work in net_struct.GlobalStore._work_task_list:
                work_info = net_struct.WorkTaskInfo()
                work_info._fd = work._fd
                work_info._conn = work._conn
                work_info._ip = work._ip
                work_info._port = work._port
                work_info._head_pack = work._head_pack
                work_info._body_pack = work._body_pack
                work_list.append(work_info)
            net_struct.GlobalStore._work_task_list = []
            net_struct.GlobalStore._work_task_lock.release()
            #遍历处理各个工作任务
            for work in work_list:
                if self._work_func != None:
                    self._work_func(self._index, work._conn, work._head_pack, work._body_pack)
