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
import net_struct
import net_util
import net_define
import net_coroutine

#epoll监听事件
class Listener(threading.Thread):
    def __init__(self, listen_fd, epoll_obj, is_coroutine, io_number, fd_timeout):
        #显示调用父类初始化
        threading.Thread.__init__(self)
        self._is_coroutine = is_coroutine
        self._io_number = io_number
        self._fd_timeout = fd_timeout
        self._listen_fd = listen_fd
        self._epoll_obj = epoll_obj

    def __del__(self):
        pass

    def run(self):
        connections = {}
        addresses = {}
        consumer = net_coroutine.co_consume()
        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, 'begin to run listen thread')
        while True:
            epoll_list = self._epoll_obj.poll()
            for fd, events in epoll_list:
                def inner_func():
                    if fd == self._listen_fd.fileno():
                        conn, addr = self._listen_fd.accept()
                        connections[conn.fileno()] = conn
                        addresses[conn.fileno()] = addr
                        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, "accept connection from %s:%d, conn_fd=%d, fd=%d" % (addr[0], addr[1], conn.fileno(), fd))
                        conn.setblocking(0)
                        self._epoll_obj.register(conn.fileno(), select.EPOLLIN | select.EPOLLET)
                    elif select.EPOLLIN & events:
                        datas = ''
                        while True:
                            try:
                                data = connections[fd].recv(10240)
                                if self._is_coroutine:
                                    consumer.next()
                                if not data and not datas:
                                    #客户端断开连接
                                    net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, "maybe client disconnect %s:%d, fd=%d" % (addresses[fd][0], addresses[fd][1], fd))
                                    self._epoll_obj.unregister(fd)
                                    connections[fd].close()
                                    del connections[fd]
                                    del addresses[fd]
                                    break
                                else:
                                    datas += data
                            except socket.error, e:
                                if e.errno == errno.EAGAIN or e.errno == errno.EWOULDBLOCK:
                                    io_info = net_struct.IoTaskInfo()
                                    io_info._fd = fd
                                    io_info._conn = connections[fd]
                                    io_info._ip = addresses[fd][0]
                                    io_info._port = addresses[fd][1]
                                    io_info._datas = datas
                                    net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, "get datas:%s" % (datas))
                                    if self._is_coroutine:
                                        net_struct.GlobalStore._io_coroutine_task_list.append(io_info)
                                        next(consumer)
                                    else:
                                        #使用多线程则将从缓冲区读到的数据写入到全局IO队列中
                                        index = net_util.get_thread_index_by_fd(fd, self._io_number)
                                        net_struct.GlobalStore._io_task_lock[index].acquire()
                                        net_struct.GlobalStore._io_task_map[index].append(io_info)
                                        net_struct.GlobalStore._io_task_lock[index].release()
                                        net_struct.GlobalStore._io_task_sem[index].release()
                                    break
                                else:
                                    self._epoll_obj.unregister(fd)
                                    connections[fd].close()
                                    del connections[fd]
                                    del addresses[fd]
                                    str_err = 'read socket error: ' + str(e)
                                    net_util.call_print_func(net_define.NET_LOGLEVEL_ERROR, os.path.basename(__file__), sys._getframe().f_lineno, str_err)
                                    break
                    elif select.EPOLLHUP & events:
                        self._epoll_obj.unregister(fd)
                        connections[fd].close()
                        del connections[fd]
                        del addresses[fd]
                        net_util.call_print_func(net_define.NET_LOGLEVEL_WARNING, os.path.basename(__file__), sys._getframe().f_lineno, 'epollhup...')
                    else:
                        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, 'other events...')
                inner_func()

def create_listen(ip, port):
    try:
        listen_fd = socket.socket(socket.AF_INET, socket.SOCK_STREAM, 0)
        listen_fd.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        listen_fd.bind((ip, port))
        listen_fd.listen(1000)
        epoll_obj = select.epoll()
        epoll_obj.register(listen_fd.fileno(), select.EPOLLIN)
        net_util.call_print_func(net_define.NET_LOGLEVEL_INFO, os.path.basename(__file__), sys._getframe().f_lineno, "begin listening, port:%d" % (port))
        return listen_fd, epoll_obj
    except (socket.error, Exception) as e:
        str_err = 'create tcp socket error: ' + str(e)
        net_util.call_print_func(net_define.NET_LOGLEVEL_ERROR, os.path.basename(__file__), sys._getframe().f_lineno, str_err)
        return None, None
