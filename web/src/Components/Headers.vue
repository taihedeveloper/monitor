<template>
	<!--header start-->
	<header class="header header-noborder">
	    <div class="header-container clearfix">
	    	<router-link to="/manageitems" class="logo"></router-link>
	        <!-- <a href="javascript:;" class="logo"></a> -->
	        <!--用户信息 start-->
	        <div class="user-info mr">
	                <div class="username mr-s" style="position: relative;">
	                    <span>{{username}}</span>
	                    <ul class="sub-nav t_right" style="right: 0;">
	                        <!-- <li id="user-li"><a >个人中心</a></li> -->
	                        <li><a href="http://monitor.taihenw.com/logout.php">安全退出</a></li>
	                    </ul>
	                </div>
	                <!-- <a target="_blank">帮助</a> -->
	        </div>
	        <!--用户信息 end-->

	        <!--网站导航 start-->
	        <ul class="nav-list">
	            <!-- <li id="index-li" class="">
	            	<router-link to="/index">首页</router-link>
	            </li> -->
	            <li id="manage-li" class="nav-ul-selected">
	            	<router-link to="/manageitems">监控项管理</router-link>
	            </li>
	            <li id="alarm-li" class="">
	                <a>监控报警</a>
	                <ul class="sub-nav">
	                    <li id="process-li" class=""><router-link to="/alarm">报警处理</router-link></li>
	                    <li id="run-log-li" class=""><router-link to="/runlog">运行记录</router-link></li>
	                </ul>
	            </li>
				<span>|</span>
	            <li id="product-li" class="nav-ul-selected">
	            	<router-link to="/productline">产品线管理</router-link>
	            </li>
	            <li id="user-li" class="nav-ul-selected"><router-link to="/manageusers">用户管理</router-link></li>
	            <!-- <li id="alarm-li" class="">
	                <a>新增配置</a>
	                <ul class="sub-nav">
	                    <li id="process-li" class=""><a>产品线配置</a></li>
	                    <li id="run-log-li" class=""><a>权限配置</a></li>
	                    <li id="run-log-li" class=""><a>报警配置</a></li>
	                </ul>
	            </li> -->
	        </ul>
	        <!--网站导航 end-->
	    </div>
		<!-- <router-view></router-view> -->

	</header>
	<!--header end-->
</template>
<script>
import vueGetData from "../Js/vueGetData.js"
	export default {
	  name: 'Header',
	  data () {
	    return {
	      msg: 'header',
	      username:""
	    }
	  },
	  created(){
	  	this.username = vueGetData.username();
    //     vueGetData.getData("loginuser",{"username":this.username},function(jsondata){
	   //      if(jsondata.body.error_code === 22009){
    //     		vueGetData.creatTips("没有权限 请连续管理员");
    //     		return false;
				// // document.getElementsByClassName("dialog")[0].style.display = "block";
    //     	}	
    //     }.bind(this),function(){

    //     }.bind(this));

	  }
	}
</script>
<style>
.header {
	height: 68px;
	/*background: -moz-linear-gradient(left, #3181fe 0, #0e4fb5 100%);
	background: -webkit-linear-gradient(left, #3181fe 0, #0e4fb5 100%);
	background: -o-linear-gradient(left, #3181fe 0, #0e4fb5 100%);
	background: -ms-linear-gradient(left, #3181fe 0, #0e4fb5 100%);
	background: linear-gradient(left, #3181fe 0, #0e4fb5 100%);*/
	background: #0e4fb5;
	width: 100%;
	z-index: 200;
	position: relative;
	opacity: .95
}

.header.header-noborder {
	box-shadow: 0 0 0 #e5e5e5;
	border-bottom: 0
}

.header .logo {
	display: block;
	width: 247px;
	height: 68px;
	float: left;
	background-image: url(/dist/Image/logo.png);
	background-repeat: no-repeat;
	background-size: contain;
	background-position-y: 10px
}

.header-container {
	width: 97%;
	margin: 0 auto
}

.user-info {
	font-size: 13px;
	position: relative;
	float: right;
	min-width: 100px
}

.user-info .username {
	display: inline-block;
	vertical-align: middle
}

.user-info .username>span {
	width: 100px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	display: inline-block;
	line-height: 68px;
	color: #ccc;
	cursor: pointer;
	vertical-align: middle;
	padding-right: 20px;
	background: url(/dist/Image/down_triangle.png) no-repeat;
	background-position: right center;
	font-size: 14px;
}

.user-info .username:hover>span {
	background: url(/dist/Image/up_triangle.png) no-repeat;
	background-position: right center
}

.user-info a {
	vertical-align: middle;
	color: #ccc
}

.nav-list {
	float: right;
	display: inline-block;
	margin-right: 40px
}

.nav-list>li {
	float: left;
	line-height: 68px;
	width: 100px;
	text-align: center
}

.nav-list>li:hover {
	background: rgba(0, 0, 0, .05)
}

.nav-list li a {
	color: #fff;
	display: block;
	font-size: 15px
}
.nav-list li a.router-link-active{
	background: rgba(0, 0, 0, .1)
}

.nav-list>span {
	float: left;
	line-height: 65px;
	width: 5px;
	text-align: center;
	color:#FFF;
	font-size: 18px;
	font-weight: bold;
}

.sub-nav {
	position: absolute;
	z-index: 10;
	padding: 10px 0;
	border: 1px solid #ccc;
	display: none;
	border-top: 0;
	box-sizing: border-box;
	min-width: 100px;
	background: #fff
}

.sub-nav li>a {
	display: block;
	line-height: 30px;
	text-align: left;
	padding: 0 20px;
	color: #333;
	font-size: 13px
}

.sub-nav li>a:hover {
	background: #ecf3fe;
	color: #4085f4
}

.username:hover .sub-nav,
.nav-list>li:hover .sub-nav {
	display: block
}
</style>