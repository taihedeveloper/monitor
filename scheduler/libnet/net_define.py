#encoding=utf-8

NET_VERSION = 1

#定义协议头数据结构
#typedef struct
#{
#   unsigned short id; 协议ID
#   unsigned short version; 版本号
#   unsigned int log_id;
#   unsigned int request_id;
#   char provider[12];
#   unsigned int magic_num;
#   unsigned int reserved;
#   unsigned int body_len;
#} t_nshead_t;
NS_HEAD_PACK_FORMAT = "2H2I12s3I"

NET_LOGLEVEL_CRITICAL = 1
NET_LOGLEVEL_ERROR = 2
NET_LOGLEVEL_WARNING = 3
NET_LOGLEVEL_INFO = 4
NET_LOGLEVEL_DEBUG = 5
