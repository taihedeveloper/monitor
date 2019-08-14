# monitor
## 概述：
本项目主要完成一个完整url类型的监控系统搭建，包括监控http状态码、数据接口返回值以及页面元素
主要分为三个部分：web、api、scheduler
欢迎大家批评指正
## web：
使用vue.js开发，实现前端添加删除监控项，查看报警运行记录等功能
## api：
使用php开发，完成数据备份至数据库，与前端+scheduler后端交互等功能
## scheduler
使用python开发，基于apscheduler完成任务的定时执行与是否满足配置条件的扫描

## 环境部署：
由于有跨域问题存在，web与api的部分需布在同一个nginx的server下。<br>
scheduler为独立部署，独立开出一个端口供api进行访问，scheduler所使用的python需安装MysqlDB和ApScheduler的扩展，scheduler的配置文件如下：<br>
[network]<br>
#服务监听端口<br>
port=xxxx<br>
[log]<br>
#日志输出路径，可以指定绝对路径，未指定绝对路径则以当前路径作为相对路径<br>
path=logs<br>
#访问日志<br>
infolog=scheduler.log<br>
#错误和警告日志<br>
warnlog=scheduler.log.wf<br>
[async]<br>
#是否使用协程处理IO，若是则忽略下面对IO线程数的设置<br>
coroutine=true<br>
#允许的IO线程数<br>
io_number=16<br>
#允许的工作线程个数<br>
work_number=32<br>
[scheduler]<br>
#线程个数<br>
thread_num=xxxx<br>
#进程个数<br>
process_num=xxxx<br>
[database]<br>
#数据库主机地址<br>
host=xxxxx<br>
#访问端口<br>
port=xxxx<br>
#用户名<br>
username=xxxx<br>
#密码<br>
password=xxxxx<br>
#数据库名<br>
db=xxxxx<br>
#操作字符集<br>
charset=utf8<br>
[alert]<br>
#这一部分为公司内部发送邮件和短信的通用服务的url，若没有类似服务需实现短信和邮件发送的功能<br>
#邮件url<br>
mailurl=http://xxxxxxxxxxxx<br>
#短信url<br>
messageurl=http://xxxxxxxxxxx<br>
[proxy]<br>
#公司内部访问外网所用的代理<br>
#http代理<br>
http=http://xxxxxxxxx<br>
#https代理<br>
https=http://xxxxxxxxx<br>
启动方式为：nohup /home/work/python/bin/python scheduler_start.py > /dev/null &<br>

## copyright<br>
taihedeveloper全体成员<br>
