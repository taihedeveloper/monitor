#encoding=utf-8

import sys
import os
import struct
import net_define
import net_struct

def get_thread_index_by_fd(fd, mod_num):
    index = fd % mod_num
    return index

def get_nshead_len():
    return struct.calcsize(net_define.NS_HEAD_PACK_FORMAT)

def unpack_nshead(head_pack):
    id, version, log_id, request_id, provider, magic_num, reserved, body_len = struct.unpack(net_define.NS_HEAD_PACK_FORMAT, head_pack)
    return id, magic_num, body_len

def call_print_func(log_level, filename, lineno, log_str):
    if net_struct.g_print_func != None:
        net_struct.g_print_func(log_level, filename, lineno, log_str)
