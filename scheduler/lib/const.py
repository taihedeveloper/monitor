#!/usr/bin/env python
#-*- coding: utf-8 -*-
#公共函数库
class Const(object):
    REQUEST_OK = 0
    REQUEST_TIMEOUT = 1
    REQUEST_BAD = 2
    REQUEST_ERROR = 3

    #运行记录状态
    RUN_RECORD_OK = 0       #通过
    RUN_RECORD_ERROR = 1    #失败
    RUN_RECORD_TIMEOUT = 2  #超时

    #监控项类型
    M_TYPE_HTTP_STATUS = 0  #http状态码
    M_TYPE_DATA_API = 1     #数据接口
    M_TYPE_WEBPAGE = 2      #页面元素

    #监控报警处理状态
    ALERT_STATUS_UN = 0     #未处理  
    ALERT_STATUS_IN = 1     #处理中
    ALERT_STATUS_DONE = 2   #已处理
    
    #运行环境
    RUNTIME = 'develop'     #运行环境：develop 开发环境 qa 测试环境 release 线上环境