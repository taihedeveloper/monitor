#encoding=utf-8

import sys
import os
import threading
import net_struct
import net_listen
import net_io
import net_work
import net_util
import net_define

#设置服务监听地址和端口
def set_server(ip, port):
    global _server_ip
    global _server_port
    _server_ip = ip
    _server_port = port

#设置是否使用协程，不使用协程则使用的io线程数
def set_io(is_coroutine, number):
    global _is_coroutine
    global _io_number
    _is_coroutine = is_coroutine
    _io_number = number

#设置工作线程数
def set_work_number(number):
    global _work_number
    _work_number = number

#设置套接字超时时间，单位：秒
def set_fd_timeout(secs):
    global _fd_timeout
    _fd_timeout = secs

#设置work回调
def set_work_callback(func):
    global _work_func
    _work_func = func

#设置打印输出回调
def set_print_callback(func):
    global _print_func
    _print_func = func

#运行
def run():
    global _server_ip
    global _server_port
    global _is_coroutine
    global _io_number
    global _work_number
    global _fd_timeout
    global _work_func
    global _print_func

    #初始化全局数据变量
    if _is_coroutine:
        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, 'use coroutine, so do not need io lock.')
        net_struct.GlobalStore._io_coroutine_task_list = []
    else:
        for i in range(0, _io_number):
            net_struct.GlobalStore._io_task_lock.append(threading.RLock())
            net_struct.GlobalStore._io_task_map[i] = []
            net_struct.GlobalStore._io_task_sem.append(threading.Semaphore(0))
    net_struct.GlobalStore._work_task_list = []
    net_struct.GlobalStore._work_task_lock = threading.RLock()
    net_struct.GlobalStore._work_task_sem = threading.Semaphore(0)
    net_struct.g_print_func = _print_func

    #创建并启动监听
    listen_fd, epoll_obj = net_listen.create_listen(_server_ip, _server_port)
    listener = net_listen.Listener(listen_fd, epoll_obj, _is_coroutine, _io_number, _fd_timeout)
    listener.start()
    if _is_coroutine:
        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, 'use coroutine, so combine listen and io.')
    else:
        #启动IO处理线程组
        io_threads = []
        for i in range(0, _io_number):
            t = net_io.Ioer(i)
            io_threads.append(t)
        for t in io_threads:
            t.start()

    #启动work线程组
    work_threads = []
    for i in range(0, _work_number):
        t = net_work.Worker(i, _work_func)
        work_threads.append(t)
    for t in work_threads:
        t.start()
