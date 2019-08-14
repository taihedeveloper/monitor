CREATE TABLE alert_group (
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    class_id int NOT NULL default 0 COMMENT '分组id',
    class_name varchar(100) NOT NULL default "" COMMENT '报警组名称',
    UNIQUE KEY `class_name` (`class_name`),
    UNIQUE KEY `class_id_2` (`class_id`),
    KEY `class_id` (`class_id`)
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "报警组表";

CREATE TABLE alert_item_count
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    task_id bigint(20) NOT NULL default 0 COMMENT '监控项id',
    alert_count int NOT NULL default 0 COMMENT '监控项报警次数',
    INDEX(task_id)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "报警次数记录表";

CREATE TABLE alert_member
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    class_id int NOT NULL DEFAULT 0 COMMENT '分组id',
    username varchar(100) NOT NULL default "" COMMENT '用户名',
    email varchar(100) NOT NULL default "" COMMENT '邮箱名',
    telno varchar(100) NOT NULL default "" COMMENT '手机号',
    INDEX(class_id)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "报警管理组";

CREATE TABLE alert_record
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    process_status tinyint(4) NOT NULL DEFAULT '0' COMMENT '处理状态 0未处理 1处理中 2已处理',
    item_name varchar(255) NOT NULL DEFAULT '' COMMENT '监控项名称',
    item_url varchar(800) NOT NULL DEFAULT '' COMMENT '监控url',
    item_level tinyint(4) NOT NULL DEFAULT '0' COMMENT '监控项级别（0，1，2）',
    item_type tinyint(4) NOT NULL DEFAULT '0' COMMENT '监控项类别,0http状态码,1url,2页面元素',
    alert_time int(11) NOT NULL DEFAULT '0' COMMENT '报警时间',
    alert_detail text NOT NULL default "" COMMENT '报警详情',
    task_id bigint(20) NOT NULL default 0 COMMENT '监控项id',
    alert_mem int(11) NOT NULL default 0 COMMENT '报警组',
    cer_mem int(11) NOT NULL default 0 COMMENT '权限组',
    product_id int(11) NOT NULL DEFAULT '0' COMMENT '产品线id',
    alert_reason varchar(255) NOT NULL DEFAULT '' COMMENT '报警原因',
    severity_level varchar(255) NOT NULL DEFAULT '' COMMENT '严重等级',
    feedback_detail varchar(255) NOT NULL DEFAULT '' COMMENT '反馈详情',
    loss_describe varchar(255) NOT NULL DEFAULT '' COMMENT '实际损失描述'
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "报警记录表";

CREATE TABLE certificate_group
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    class_id int(11) NOT NULL default 0 COMMENT '分组id',
    class_name varchar(100) NOT NULL default "" COMMENT '权限组名称',
    UNIQUE KEY `class_id_2` (`class_id`),
    UNIQUE KEY `class_name` (`class_name`),
    KEY `class_id` (`class_id`)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "权限组表";

CREATE TABLE certificate_member
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    class_id int(11) NOT NULL DEFAULT 0 COMMENT '分组id',
    username varchar(100) NOT NULL DEFAULT 0 COMMENT '用户名',
    is_admin tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是组管理员',
    INDEX(username)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "权限组成员表";

CREATE TABLE monitor_item
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    item_name varchar(100) NOT NULL DEFAULT '' COMMENT '监控项名称',
    level tinyint(4) NOT NULL DEFAULT '0' COMMENT '监控分级,0,1,2',
    type varchar(100) NOT NULL DEFAULT '0' COMMENT '监控项类型,0http状态码,1url,2页面元素',
    frequence varchar(100) NOT NULL COMMENT '监控频率,单位min',
    url varchar(800) NOT NULL DEFAULT '' COMMENT '监控url',
    username varchar(100) NOT NULL DEFAULT '' COMMENT '用户参数信息',
    status tinyint(4) NOT NULL DEFAULT '1' COMMENT '监控项状态,0删除,1下线,2上线',
    alert_mem int(11) DEFAULT NULL COMMENT '监控项报警组',
    cer_mem int(11) DEFAULT NULL COMMENT '监控项权限组',
    eff_status tinyint(4) NOT NULL DEFAULT '0' COMMENT '监控项有效标示,0永久生效,1一次性生效',
    product_line varchar(100) NOT NULL DEFAULT '' COMMENT '产品线名称',
    time_out int(11) NOT NULL DEFAULT '10000' COMMENT '超时时间,单位us',
    criterion mediumtext NOT NULL COMMENT '匹配规则',
    multi_host tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否分机房,0不分,1分',
    last_runtime int(11) NOT NULL DEFAULT '0' COMMENT '最后一次监控项执行时间',
    idalloc_id bigint(20) NOT NULL DEFAULT '0' COMMENT 'idalloc分配id',
    post_content mediumtext NOT NULL DEFAULT '' COMMENT 'post参数内容',
    callback_url varchar(800) NOT NULL DEFAULT '' COMMENT '回调执行方法',
    monitor_arg tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否添加monitor参数,0不需要 1需要',
    last_online_time int(11) NOT NULL DEFAULT 0 COMMENT '最后一次上线时间',
    mail_count int(11) NOT NULL DEFAULT 1 COMMENT '邮件报警条件',
    message_count int(11) NOT NULL DEFAULT 1 COMMENT '短信报警条件',
    referer varchar(500) NOT NULL DEFAULT "" COMMENT 'referer值',
    user_agent varchar(500) NOT NULL DEFAULT "" COMMENT 'ua值',
    editor varchar(100) NOT NULL DEFAULT "" COMMENT '创建用户名',
    INDEX(product_line),
    INDEX(status),
    INDEX(cer_mem),
    INDEX(type),
    INDEX(item_name),
    INDEX(level)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "监控项列表";

CREATE TABLE multi_hosts
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    item_name varchar(100) NOT NULL DEFAULT '' COMMENT '监控项名称',
    item_status tinyint(4) NOT NULL DEFAULT 1 COMMENT '监控项状态,0删除,1下线,2上线',
    access_mode tinyint(4) NOT NULL DEFAULT 0 COMMENT '访问方式',
    host_name varchar(255) NOT NULL DEFAULT '' COMMENT '域名',
    host_ip varchar(255) NOT NULL DEFAULT '' COMMENT '机房ip',
    apply_scope tinyint(4) NOT NULL DEFAULT 0 COMMENT '应用范围',
    comments varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
    operator varchar(255) NOT NULL DEFAULT '' COMMENT '创建人',
    create_time int NOT NULL DEFAULT 0 COMMENT '创建时间',
    INDEX(host_name)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "多机房列表";

CREATE TABLE product_lines (
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    product_id int(11) NOT NULL DEFAULT '0' COMMENT '产品线id',
    product_name varchar(255) NOT NULL DEFAULT '' COMMENT '产品线名称',
    INDEX(product_id),
    INDEX(product_name)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "产品线表";

CREATE TABLE robot_acount
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    user_id varchar(255) NOT NULL DEFAULT '' COMMENT '用户id',
    tel_no varchar(255) NOT NULL DEFAULT '' COMMENT '手机号码',
    password varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
    type tinyint(4) NOT NULL DEFAULT '0' COMMENT '账号类型（0是token）',
    acount_name varchar(255) NOT NULL DEFAULT '' DEFAULT '' COMMENT '账号名',
    INDEX(tel_no)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "机器人列表";

CREATE TABLE run_record
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    item_name varchar(255) NOT NULL DEFAULT '' COMMENT '监控项名称',
    item_type tinyint(4) NOT NULL DEFAULT '0' COMMENT '监控项类型,0http状态码,1url,2页面元素',
    run_status tinyint(4) NOT NULL DEFAULT '0' COMMENT '运行记录状态 0通过1失败2超时',
    run_time bigint(20) NOT NULL DEFAULT '0' COMMENT '记录运行时间',
    time_consume int(11) NOT NULL DEFAULT '0' COMMENT '耗时时间 ms',
    task_id bigint(20) NOT NULL DEFAULT '0' COMMENT '监控项id',
    cer_mem int(11) NOT NULL DEFAULT '0' COMMENT '监控项权限组',
    product_id int(11) NOT NULL DEFAULT '0' COMMENT '产品线id',
    INDEX(item_name),
    INDEX(product_id)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "运行记录表";

CREATE TABLE user_info
(
    id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '唯一id',
    username varchar(100) NOT NULL DEFAULT "" COMMENT '用户名',
    email varchar(100) NOT NULL DEFAULT "" COMMENT '用户邮箱',
    tel_no varchar(100) NOT NULL DEFAULT "" COMMENT '用户手机号',
    INDEX(username)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB COMMENT "用户信息表";

alter table alert_record add feedbacktime int NOT NULL default 0 COMMENT '反馈时间';
alter table alert_item_count add last_mail_count int NOT NULL default 0 COMMENT '发邮件时报警次数';
alter table alert_item_count add last_message_count int NOT NULL default 0 COMMENT '发短信时报警次数';
alter table alert_record add alert_message varchar(500) NOT NULL default "" COMMENT '短信报警详情';
alter table run_record add run_url varchar(800) NOT NULL default "" COMMENT '运行url';