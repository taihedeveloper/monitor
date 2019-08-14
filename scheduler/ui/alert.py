#encoding=utf-8
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide alert function.
Authors: luohongcang(luohongcang@taihe.com)
Date:    2017/02/20
"""
import time
import sys
import os
import json
import re

from urllib import quote

sys.path.append("..")
from lib.scheduler_db import scheduler_db
from lib.scheduler_http import scheduler_http
import lib.scheduler_logger

class alert(object):
    """ class content:
    alert functions(message && mail)
    """

    def pack_mail_content(self, send_mail_data):
        """
            邮件样式填充方法
        Args:
            send_mail_data: 邮件填充内容字典
        Returns:
        Raises:
        """
        item_id = send_mail_data['item_id']
        item_name = send_mail_data['item_name']
        item_url = send_mail_data['url']
        item_level = send_mail_data['level']
        item_type = send_mail_data['type']
        item_alert_content = send_mail_data['alert_content']
        item_alert_member = send_mail_data['alert_member_arr']
        item_alert_id = send_mail_data['alert_id']

        content_str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head lang="en"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>太合音乐业务监控平台邮件</title><meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"></head><body style="background:#e4e4e4; word-wrap:break-word;padding: 10px 10px 30px;margin:0;word-break: break-all;table-layout: fixed;"><style>td{border:1px solid #07a396;vertical-align:middle;height:40px;line-height:40px;padding:5px 0;background:#f3fbfd;} a:hover{color: #000;}</style><table cellpadding=0 cellspacing=0 width="1200px" border="1" style="vertical-align:middle;line-height:180%;border-collapse:collapse;border:1px solid #07a396;font-weight: normal;font-family:Microsoft YaHei;background:#f3fbfd,font-size: 14px;margin:0 auto;color:#474847;table-layout: fixed;background:#f3fbfd"><col style="width: 110px;" /><col style="width: 1080px;" /><thead><tr><th colspan="2" style="border:1px solid #07a396;border-bottom:none;background:#09cebe;font-size:18px;color:#fff;padding:5px 0;text-align:center;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;vertical-align:middle;height:50px;line-height:50px;">'

        if int(item_type) == 0:
            content_str = content_str + '[http可用性报警]: ' + str(item_name)
        elif int(item_type) == 1:
            content_str = content_str + '[数据接口报警]: ' + str(item_name)
        elif int(item_type) == 2:
            content_str = content_str + '[页面元素报警]: ' + str(item_name)
        content_str = content_str + '</th></tr></thead><tr><td style="width:110px;padding-left:5px;">监控项名称</td><td style="width:1080px;word-break: break-all;padding-left:5px;"><a href="http://monitor.taihenw.com/#/manageitemsname/itemname=' + quote(str(item_name)) + '&type=' + str(item_type) + '">' + str(item_name) + '</a></td></tr><tr><td style="width:110px;padding-left:5px;">监控类别</td><td style="padding-left:5px;">'
        if int(item_type) == 0:
            content_str = content_str + 'http可用性'
        elif int(item_type) == 1:
            content_str = content_str + '数据接口'
        elif int(item_type) == 2:
            content_str = content_str + '页面元素'
        content_str = content_str + '</td></tr><tr><td style="width:110px;padding-left:5px;">监控级别</td><td style="width:1080px;word-break: break-all;padding-left:5px;">' + str(item_level) + '级</td></tr><tr><td style="width:110px;padding-left:5px;">监控URL</td><td style="width:1080px;word-break: break-all;padding-left:5px;"><a href="' + str(item_url) + '">' + str(item_url) + '</a></td></tr><tr><td style="width:110px;padding-left:5px;">报警接收人</td><td style="width:1080px;word-break: break-all;padding-left:5px;">'
        for alert_mem in item_alert_member:
            content_str = content_str + '<a href="mailto:' + str(alert_mem) + '">' + str(alert_mem) + ','
        if item_alert_member:
            content_str = content_str[:-1]
        content_str = content_str + '</a></td></tr><tr><td style="width:110px;padding-left:5px;">报警时间</td><td style="width:1080px;word-break: break-all;padding-left:5px;">' + str(time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))) + '</td></tr><tr><td style="width:110px;padding-left:5px;">报警详情</td><td style="width:1080px;word-break: break-all;padding-left:5px;"><pre style="font-weight: normal;font-family:Microsoft YaHei;font-size: 14px;white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap;white-space: -o-pre-wrap; word-wrap: break-word;"><s:property value="work_expr" default="无" />'
        content_str = content_str + str(item_alert_content) + '</pre></td></tr><tr><td style="width:110px;padding-left:5px;">报警处理</td><td style="width:1080px;word-break: break-all;padding-left:5px;"><a href="http://monitor.taihenw.com/#/alarm/' + str(item_alert_id) +  '">报警列表页</a></td></tr></table></body></html>'

        return content_str

    def sendmail(self, mail_url, mail_user, mail_data):
        """
            发送邮件报警
        Args:
            mail_url : 邮件服务url
            mail_user: 发送邮件用户
            mail_data: 发送邮件内容
        Returns:
        Raises:
        """
        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "send alert mail. content: " + str(mail_data))
        mail_arr = {}
        product_line = mail_data['product_line']
        type = int(mail_data['type'])
        type_str = ""
        if type == 0:
            type_str = "http可用性"
        elif type == 1:
            type_str = "数据接口"
        elif type == 2:
            type_str = "页面元素"

        item_name = mail_data['item_name']
        level = mail_data['level']
        count = mail_data['count']
        mail_arr['toemail'] = mail_user
        mail_arr['title'] = "[太合Monitor监控报警]产品线:" + str(product_line) + "-类别:" + str(type_str) + "-监控项名称:" + str(item_name) + "-监控级别:" + str(level) + "级 失败" + str(count) + "次"
        mail_arr['content'] = mail_data['content']
        mail_arr['type'] = 2

        _scheduler_http = scheduler_http()
        mail_ret = _scheduler_http.post(mail_url, mail_arr)
        mail_ret = json.loads(mail_ret)
        if not mail_ret or not mail_ret['error_code'] or int(mail_ret['error_code']) != 22000:
            err_msg = "send alert mail fail. " + str(mail_ret)
            lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, err_msg)

    def sendmessage(self, message_url, message_user, message_data):
        """
            发送短信报警
        Args:
            message_url : 短信服务url
            message_user: 发送短信用户
            message_data: 发送短信内容
        Returns:
        Raises:
        """
        lib.scheduler_logger.info(os.path.basename(__file__), sys._getframe().f_lineno, "send alert message. content: " + str(message_data))
        product_line = message_data['product_line']
        type = int(message_data['type'])
        type_str = ""
        if type == 0:
            type_str = "http可用性"
        elif type == 1:
            type_str = "数据接口"
        elif type == 2:
            type_str = "页面元素"
        item_name = message_data['item_name']
        level = message_data['level']
        count = message_data['count']
        item_name = item_name.decode('utf8')
        item_name = re.sub("[+——！，。？、~@#￥%……&*（）【】]+".decode("utf8"), "".decode("utf8"), item_name)
        item_name = item_name.encode('utf8')
        send_mess_url = message_url + "&contentVar=product|" + str(product_line) + ",type|" + str(type_str) +",name|" + str(item_name) + ",level|" + str(level) + "级,number|" + str(count)
        
        _scheduler_http = scheduler_http()
        for user in message_user:
            send_mess_url_single = send_mess_url + "&receiver=" + str(user)
            mess_ret = _scheduler_http.request(send_mess_url_single)
            mess_ret = json.loads(mess_ret)
            if not mess_ret or not mess_ret['error_code'] or int(mess_ret['error_code']) != 22000:
                err_msg = "send alert message fail. " + str(mess_ret)
                lib.scheduler_logger.warning(os.path.basename(__file__), sys._getframe().f_lineno, err_msg)

    def process(self, data):
        """
            报警处理逻辑
        Args:
            data : 监控项相关数据
        Returns:
        Raises:
        """
        task_id = data['item_id']
        mail_count = int(data['mail_count'])
        message_count = int(data['message_count'])
        frequence = int(data['frequence'])
        type = data['type']
        product_line = data['product_line']
        item_name = data['item_name']
        level = data['level']
        task_url = data['url']
        mail_url = data['mail_url']
        message_url = data['message_url']
        now_time = int(time.time())

        _scheduler_db = scheduler_db()
        find_sql = "SELECT * FROM alert_record WHERE task_id = " + str(task_id) + " AND alert_time >= " + str(now_time - frequence * 60) + " AND alert_time < " + str(now_time)
        find_ret = _scheduler_db.findAll(find_sql)

        update_sql = ""
        mail_content = {}
        message_content = {}
        mail_user = ""
        message_user = []
        alert_content = ""
        alert_url = ""
        mail_user_arr = []
        alert_message = ""
        if not find_ret:
            update_sql = "UPDATE alert_item_count SET alert_count = 0, last_mail_count = 0, last_message_count = 0 WHERE task_id = " + str(task_id)
            update_ret = _scheduler_db.query(update_sql)
            _scheduler_db.commit()
        else:
            update_sql = "UPDATE alert_item_count SET alert_count = alert_count + 1 WHERE task_id = " + str(task_id)
            update_ret = _scheduler_db.query(update_sql)
            _scheduler_db.commit()
            if not update_ret:
                err_msg = "update alert count fail. task_id = " + str(task_id)
                lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, err_msg)
            #邮件内容填充
            alarm_user_group = find_ret[0]['alert_mem']
            alert_content = find_ret[0]['alert_detail']
            alert_message = find_ret[0]['alert_message']
            alert_url = find_ret[0]['item_url']
            alert_id = find_ret[0]['id']
            alert_user_sql = "SELECT * FROM alert_member WHERE class_id = " + str(alarm_user_group);
            alert_user = _scheduler_db.findAll(alert_user_sql)
            for user in alert_user:
                mail_user = mail_user + str(user['email']) + ","
                mail_user_arr.append(user['email'])
                message_user.append(user['telno'])
            if mail_user:
                mail_user = mail_user[:-1]

        check_sql = "SELECT alert_count, last_mail_count, last_message_count FROM alert_item_count WHERE task_id = " + str(task_id)
        check_ret = _scheduler_db.findAll(check_sql)

        if not check_ret:
            err_msg = "task_id-" + str(task_id) + " has no alert_count record"
            lib.scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, err_msg)
        else:
            check_count = int(check_ret[0]['alert_count'])
            last_mail_count = int(check_ret[0]['last_mail_count'])
            last_message_count = int(check_ret[0]['last_message_count'])
            if check_count % mail_count == 0 and check_count > last_mail_count:
                mail_data = {}
                #发送邮件报警
                mail_data['product_line'] = product_line
                mail_data['type'] = type
                mail_data['item_name'] = item_name
                mail_data['level'] = level
                mail_data['count'] = check_count

                send_mail_data = {}
                send_mail_data['item_id'] = task_id
                send_mail_data['item_name'] = item_name
                send_mail_data['url'] = alert_url
                send_mail_data['level'] = level
                send_mail_data['type'] = type
                send_mail_data['alert_content'] = alert_content
                send_mail_data['alert_member_arr'] = mail_user_arr
                send_mail_data['alert_id'] = alert_id
                mail_data['content'] = self.pack_mail_content(send_mail_data)
                if mail_user:
                    self.sendmail(mail_url, mail_user, mail_data)
                update_last_mail_sql = "UPDATE alert_item_count SET last_mail_count = " + str(check_count) + " WHERE task_id = " + str(task_id)
                update_last_mail_ret = _scheduler_db.query(update_last_mail_sql)
                _scheduler_db.commit()

            if (message_count > 2 and check_count == 2) or (check_count % message_count == 0 and check_count > last_message_count):
                message_data = {}
                #发送短信报警
                message_data['product_line'] = product_line
                message_data['type'] = type
                message_data['item_name'] = item_name
                message_data['level'] = level
                message_data['count'] = check_count
                message_data['alert_content'] = alert_message
                if message_user:
                    self.sendmessage(message_url, message_user, message_data)
                update_last_message_sql = "UPDATE alert_item_count SET last_message_count = " + str(check_count) + " WHERE task_id = " + str(task_id)
                update_last_message_ret = _scheduler_db.query(update_last_message_sql)
                _scheduler_db.commit()
        _scheduler_db.close()
