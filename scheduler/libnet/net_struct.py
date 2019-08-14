#encoding=utf-8

import threading

class IoTaskInfo(object):
    def __init__(self):
        #套接字句柄
        self._fd = None
        #连接对象
        self._conn = None
        #连接IP
        self._ip = None
        #连接端口
        self._port = None
        #接收到的数据
        self._datas = ''
    def __del__(self):
        pass

class WorkTaskInfo(object):
    def __init__(self):
        #套接字句柄
        self._fd = None
        #连接对象
        self._conn = None
        #连接IP
        self._ip = None
        #连接端口
        self._port = None
        #数据包头
        self._head_pack = None
        #数据包体
        self._body_pack = None
    def __del__(self):
        pass

#全局对象，在多个模块中被使用
class GlobalStore(object):
    #io事件，根据fd打散到对应的线程
    _io_task_map = {}
    #io锁，每个io线程一个锁
    _io_task_lock = []
    #io信号量
    _io_task_sem = []
    #work任务队列
    _work_task_list = []
    #可重入锁，用来锁work任务队列
    _work_task_lock = None
    #work任务信号量
    _work_task_sem = None
    #io事件队列，协程使用，故不用使用锁
    _io_coroutine_task_list = []

#回调显示函数，提供给外面输出日志
g_print_func = None
