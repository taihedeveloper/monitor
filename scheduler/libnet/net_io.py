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

#IO线程类
class Ioer(threading.Thread):
    def __init__(self, index):
        #显示调用父类初始化
        threading.Thread.__init__(self)
        self._index = index

    def __del__(self):
        pass

    def run(self):
        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, 'begin to run io thread: %d' % (self._index))
        while True:
            net_struct.GlobalStore._io_task_sem[self._index].acquire()
            io_list = []
            #拷贝IO事件列表到临时list中
            net_struct.GlobalStore._io_task_lock[self._index].acquire()
            for io in net_struct.GlobalStore._io_task_map[self._index]:
                io_info = net_struct.IoTaskInfo()
                io_info._fd = io._fd
                io_info._conn = io._conn
                io_info._ip = io._ip
                io_info._port = io._port
                io_info._datas = io._datas
                io_list.append(io_info)
            net_struct.GlobalStore._io_task_map[self._index] = []
            net_struct.GlobalStore._io_task_lock[self._index].release()
            #解析IO事件数据
            head_len = net_util.get_nshead_len()
            for io in io_list:
                work_info = net_struct.WorkTaskInfo()
                work_info._fd = io._fd
                work_info._conn = io._conn
                work_info._ip = io._ip
                work_info._port = io._port
                datas = io._datas
                if len(datas) < head_len:
                    net_util.call_print_func(net_define.NET_LOGLEVEL_ERROR, os.path.basename(__file__), sys._getframe().f_lineno, 'datas length is too short')
                    continue
                while len(datas) >= head_len:
                    head_pack = datas[0:head_len]
                    datas_len = len(datas)
                    id, magic_num, body_len = net_util.unpack_nshead(head_pack)
                    net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, "thread:%d, pack head: id:%d, magic_num:%d, body_len:%d, datas_len:%s" % (self._index, id, magic_num, body_len, datas_len))
                    all_len = head_len + body_len
                    if datas_len < all_len:
                        break
                    body_pack = datas[head_len:all_len]
                    datas = datas[all_len:]
                    #添加到work任务队列
                    work_info._head_pack = head_pack
                    work_info._body_pack = body_pack
                    net_struct.GlobalStore._work_task_lock.acquire()
                    net_struct.GlobalStore._work_task_list.append(work_info)
                    net_struct.GlobalStore._work_task_lock.release()
                    net_struct.GlobalStore._work_task_sem.release()
