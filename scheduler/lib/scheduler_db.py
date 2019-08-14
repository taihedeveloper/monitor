#-*- coding: utf-8 -*-
################################################################################
#
# Copyright (c) 2017 TAIHE. All Rights Reserved
#
################################################################################
"""
This module provide db related function.
Authors: zhangjunxing(zhangjunxing@taihe.com)
Date:    2017/02/14
"""

import MySQLdb
import os
import sys

import scheduler_logger

global db_host
global db_port
global db_user
global db_passwd
global db_db

class scheduler_db():
    """ class content:
    db related function class
    """

    def __init__(self):
        """
            init函数
        Args:
        Returns:
        Raises:
        """
        charset = "utf8"
        try:
            self.conn = MySQLdb.connect(host = db_host, port = db_port, user = db_user, passwd = db_passwd, db = db_db)
            self.conn.set_character_set(charset)
            self.cur = self.conn.cursor()
        except MySQLdb.Error as e:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(e.args[0]) + str(e.args[1]))

    def query(self, sql):
        """
            sql请求
        Args:
            sql: sql语句
        Returns:
        Raises:
        """
        try:
            n = self.cur.execute(sql)
            return n
        except MySQLdb.Error as e:
            scheduler_logger.error(os.path.basename(__file__), sys._getframe().f_lineno, str(e.args[0]) + str(e.args[1]))
        return False

    def find(self, sql):
        """
            获取一项满足条件的数据
        Args:
            sql: sql语句
        Returns:
        Raises:
        """
        self.query(sql)
        result = self.cur.fetchone()
        desc =self.cur.description
        d = {}
        for i in range(0,len(result)):
            d[desc[i][0]] = str(result[i])
        return d

    def findAll(self, sql):
        """
            获取所有满足条件的数据
        Args:
            sql: sql语句
        Returns:
        Raises:
        """
        self.query(sql)
        result = self.cur.fetchall()
        desc = self.cur.description
        d = []
        for inv in result:
            _d = {}
            for i in range(0,len(inv)):
                _d[desc[i][0]] = str(inv[i])
            d.append(_d)
        return d

    def escape(self, cont):
        """
            转义数据
        Args:
            cont: 转义内容
        Returns: string
        Raises:
        """
        return MySQLdb.escape_string(str(cont))

    def add(self, p_table_name, p_data):
        """
            在表中新增一些数据
        Args:
            sql: sql语句
        Returns:
        Raises:
        """
        for key in p_data:
            p_data[key] = "'"+self.escape(p_data[key])+"'"
        key = ',' . join(p_data.keys())
        value = ',' . join(p_data.values())
        real_sql = "INSERT INTO " + p_table_name + " (" + key + ") VALUES (" + value + ")"
        return self.query(real_sql)

    def getId(self):
        """
            获取当前最大id
        Args:
        Returns:
        Raises:
        """
        return self.cur.lastrowid

    def rowcount(self):
        """
            获取行数
        Args:
        Returns:
        Raises:
        """
        return self.cur.rowcount

    def commit(self):
        """
            提交操作
        Args:
        Returns:
        Raises:
        """
        self.conn.commit()

    def rollback(self):
        """
            回滚操作
        Args:
        Returns:
        Raises:
        """
        self.conn.rollback()

    def close(self):
        """
            关闭数据库
        Args:
        Returns:
        Raises:
        """
        self.cur.close()
        self.conn.close()

def set_param(host, port, user, passwd, db):
    """
        设置全局参数项
    Args:
        host: 主机名
        port: 端口号
        user: 用户名
        passwd: 密码
        db: 数据库名
    Returns:
    Raises:
    """
    global db_host
    global db_port
    global db_user
    global db_passwd
    global db_db
    db_host = host
    db_port = port
    db_user = user
    db_passwd = passwd
    db_db = db

