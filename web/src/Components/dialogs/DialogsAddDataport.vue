<template>
	<div class="dialog">
		<div class="dialogOverlay"></div>
		<div class="dialogContent">
			<div class="dialogClose" @click="hideDialog"></div>
			<div class="dialogDetail">
				<div class="dialogTitle">{{dataportformData.dialogTitle}}</div>
				<div class="dialogDiv">
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控项名称</div>
						<div class="subDivContent"><input type="text" class="dataport-dialog-name" placeholder="" v-model="dataportformData.itemName"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控分级</div>
						<div class="subDivContent">
							<label><input type="radio" name="level" value="0" v-model="dataportformData.levelRadio" id="level0"><span for="level0">0级</span></label>
							<label><input type="radio" name="level" value="1" v-model="dataportformData.levelRadio" id="level1"><span for="level1">1级</span></label>
							<label><input type="radio" name="level" value="2" v-model="dataportformData.levelRadio" id="level2"><span for="level2">2级</span></label>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">服务url</div>
						<div class="subDivContent"><input type="text" class="dataport-dialog-name" placeholder="" v-model="dataportformData.inputServerUrl"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">referer</div>
						<div class="subDivContent"><input type="text" class="dataport-dialog-referer" placeholder="" v-model="dataportformData.referer"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">userAgent</div>
						<div class="subDivContent"><input type="text" class="dataport-dialog-useragent" placeholder="" v-model="dataportformData.userAgent"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">请求方式</div>
						<div class="subDivContent">
							<label><input type="radio" value="0" v-model="dataportformData.isAndPost">get</label>
							<label><input type="radio" value="1" v-model="dataportformData.isAndPost">post</label>

								<div class="subDivCard" v-if="dataportformData.isAndPost == 1">
				 					<div class="dialogSubDiv">
										<div class="subDivTitle">postData格式</div>
										<div class="subDivContent" style="display: inline-block;width: 400px;">
											<select name="format" class="long-select" v-model="dataportformData.formatSelect">
												<option v-for="(value,index) in formats" :value="index">{{value}}</option>
											</select>
										</div>
									</div>
									<textarea style="width: 100%; height: 100px;" placeholder='post数据格式为Json或urlEncode,例如：{"key":"value"}' id="andPostCon" v-model="dataportformData.andPostCon"></textarea>
								</div>
								<div class="subDivCard" v-if="dataportformData.isAndPost == 0">
				 					<div class="dialogSubDiv">
										<div class="subDivTitle" style="color:#ccc">postData格式</div>
										<div class="subDivContent" style="display: inline-block;width: 400px;">
											<select name="format" class="long-select" v-model="dataportformData.formatSelect" disabled="disabled">
												<option v-for="(value,index) in formats" :value="index">{{value}}</option>
											</select>
										</div>
									</div>
									<textarea style="width: 100%; height: 100px;" placeholder='post数据格式为Json或urlEncode,例如：{"key":"value"}' id="andPostCon" v-model="dataportformData.andPostCon" disabled="disabled"></textarea>
								</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">url请求超时</div>
						<div class="subDivContent">
							<label><input type="number" min="1" class="alarm-config-dialog-timeout sm-size" placeholder="" v-model="dataportformData.timeOut" /></label><span class="showfont">ms(默认为10秒)</span>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">账号选择</div>
						<div class="subDivContent">
							<div class="subDivCard">
								<label><input type="radio" name="isNeedAccount" value="0" v-model="dataportformData.isNeedAccount">不需要用户账号</label>
								<label><input type="radio" name="isNeedAccount" value="1" v-model="dataportformData.isNeedAccount">需要用户账号</label>
								<div class="">
									<select name="" class="long-select" disabled="disabled"  v-if="dataportformData.isNeedAccount == 0">
										<option></option>
									</select>
									<select name="" class="long-select" v-else="dataportformData.isNeedAccount == 1" v-model="dataportformData.userSelected">
										<option v-for="value in userAccounts" :value="value.acount_name">{{value.acount_name}}</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="dialogSubDiv">
						<div class="subDivTitle" style="line-height: 30px;">获取页面数据</div>
						<div class="subDivContent"><button class="blue-button" @click="testRules">获取数据</button></div>
					</div>
 					<div class="dialogSubDiv">
						<div class="subDivTitle">页面数据表现</div>
						<div class="subDivContent">
							<textarea placeholder="" id="eleshow" v-model="dataportformData.eleshow" class="long-textarea"></textarea>
						</div>
					</div>
 					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控规则</div>
						<div style="display:inline-block;width:600px;">
							<template v-for="(item,i) in dataportformData.ruleselects">
								<div class="subDivContent" style="margin-bottom:10px;">
									<div class="subDivCard">										
										<div class="box">
											<label class="name">规则类别</label>
											<select name="" v-model="item.ruletype">
												<option v-for="(value,index) in ruletypes" :value="index">{{value}}</option>
											</select>
										</div>
										<div class="box">
											<label class="name">规则内容(非空)</label>
											<input type="text" class="dataport-dialog-rulename" placeholder="" v-model="item.rulecont">
										</div>
										<div class="box">
											<label class="name">条件操作</label>
											<div class="subbox">
												<template v-if="item.rulecont">
													<button class="blue-button" @click="addTestManageRules">添加</button>
													<button class="blue-button" v-show="dataportformData.ruleselects.length >1" @click="deleteTestManageRules(i)">删除</button>
													<button class="blue-button" @click="testManageRules(i)">测试规则</button>
												</template>
												<template v-else>
													<button class="gray-button" disabled="disabled">添加</button>
													<button class="blue-button" v-show="dataportformData.ruleselects.length >1" @click="deleteTestManageRules(i)">删除</button>
													<button class="gray-button" disabled="disabled">测试规则</button>
												</template>
											</div>
										</div>

									</div>
								</div>
							</template>
							
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle" style="line-height:20px;">自定义报警回调API</div>
						<div class="subDivContent"><input type="text" class="dataport-dialog-name" placeholder="" v-model="dataportformData.callbackApi"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">执行方案</div>
						<div class="subDivContent">
							<label><input type="radio" name="alarm-level" value="1" v-model="dataportformData.isForever">永久</label>
							<label><input type="radio" name="alarm-level" value="0" v-model="dataportformData.isForever">一次性</label>
							<div class="subDivCard" v-if="dataportformData.isForever == 1" style="min-height:50px;">
								<label class="info-label">监控频率<input type="number" class="foreverFrequence sm-size" placeholder="" v-model="dataportformData.foreverFrequence" min=1>分钟(m)</label>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控权限组</div>
						<div class="subDivContent">
							<select name="" class="long-select" v-model="dataportformData.permissionSelected">
								<option v-for="(value,index) in permissionList" :value="index">{{value.class_name}}</option>
							</select>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">报警配置</div>
						<div class="subDivContent">
							<div class="subDivCard">
								<div class="">
									<label class="info-label">监控报警组</label>
									<select name="" class="long-select" v-model="dataportformData.alarmSelected">
										<option v-for="(value,index) in alarmList" :value="index">{{value.class_name}}</option>
									</select>
								</div>
								<span class="cardContent">邮件报警频率：连续<input type="number" min="0" class="dialog-short-input email-alarm-rates" v-model="dataportformData.mailCount">次失败后触发报警</span>
								<span class="cardContent">短信报警频率：连续<input type="number" min="0" class="dialog-short-input message-alarm-rates" v-model="dataportformData.messageCount">次失败后触发报警</span>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">有效监控时间</div>
						<div class="subDivContent">
							<input class="validtime" type="text" v-model="dataportformData.startTime"> 至 <input class="validtime" type="text" v-model="dataportformData.endTime">
						</div>
					</div>

					<div class="dialogButtonDiv">
						<a href="javascript:;" class="dialog-blue-button"  v-show="this.dataportformData.isShowSavebtn" @click="addHttp">保存</a>
						<a href="javascript:;" class="dialog-gray-button" @click="hideDialog">取消</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import vueGetData from "../../Js/vueGetData.js"

export default {
	name: "Dialogdataport",
	data () {
		return {
			dataportformData: {
				dialogTitle: '新建数据接口监控',
				itemName: '',
				portTypeSelected: 0,
				levelRadio: "0",
				inputServerUrl: '',
				referer: "",
				timeOut:20000,
				formatSelect: 0,
				post: "",
				userAgent:"",
				eleshow: "",
				ruleselects:[{ruletype:"0",rulecont:""}],
				ruledetail:"",
				isNeedAccount: 0,
				userSelected: "",
				callbackApi: "",
				callbackApiTimeout: 60,
				foreverFrequence: 1,
				isForever: 1,
				permissionSelected: 0,
				alarmSelected: 0,
				mailCount: 1,
				messageCount:3,
				isAndPost: "0",
				andPostCon:"",
				startTime: "00:00",
				endTime:"23:59",
				//规则
				criterion: {},
				isShowSavebtn: true,	//当查看时不需要保存按钮  true为要展示保存按钮
				rulePostJson:{"reg":[],"count":[],"notnull":[],"key_exist":[],"equal":[]}
			},
			portTypes: ["json"],
			formats: ["Json","urlEncode"],
			ruletypes: ["reg-正则","count-数量","notnull-非空","key_exist-键值存在判断","equal-全等"],
			type: ["数据接口"],
			childForHttpTable:[],
			childHttptabledata:{},
			productline:"",
			copyDataportformData:{}

		}
	},
	props: ["editinitformdata"],
	computed:mapGetters({
		productId: "productId",
		permissionList:"certificatemanagelist",
		permissioclassid:"permissioclassid",
		alarmList:"alertmanagelist",
		alertclassid:"alertclassid",
		userAccounts:"robotacountlist",
		robotacountid:"robotacountid",
		dialogfetchdata:"managequerydata"
	}),
	watch: {
		permissioclassid: function() {
			let id = this.permissioclassid;
			for(let i=0;i<this.permissionList.length;i++){
				let json = this.permissionList[i];
				if(id == json.class_id){
					this.dataportformData.permissionSelected = i;
					return false;
				}
				
			}
		},
		alertclassid: function() {
			let id = this.alertclassid;
			for(let i=0;i<this.alarmList.length;i++){
				let json = this.alarmList[i];
				if(id == json.class_id){
					this.dataportformData.alarmSelected = i;
					return false;
				}
				
			}
		},
		robotacountid: function() {
			this.dataportformData.userSelected = this.robotacountid;
		},
		editinitformdata:function(){
			console.log("editinitformdata");
			let json = this.editinitformdata;
			this.dataportformData.isShowSavebtn = json["edit"];
			this.dataportformData.itemName = json["item_name"];
			this.dataportformData.typeSelected = json["type"];
			this.dataportformData.levelRadio = json["level"];
			this.dataportformData.inputServerUrl = json["url"];
			this.dataportformData.productline = json["product_line"];
			this.dataportformData.callbackApi = json["callback_url"];
			this.dataportformData.mailCount = json["mail_count"];
			this.dataportformData.messageCount = json["message_count"];
			this.dataportformData.timeOut = json["time_out"];
			this.dataportformData.referer = json["referer"];
			this.dataportformData.userAgent = json["user_agent"];
			this.dataportformData.startTime = json["start_time"];
			this.dataportformData.endTime = json["end_time"];

			// this.dataportformData.permissionSelected = json["cer_mem"];
			// this.dataportformData.alarmSelected = json["alert_mem"];
			let cermemId = json["cer_mem"];
			for(let i=0;i<this.permissionList.length;i++){
				let json = this.permissionList[i];
				if(cermemId == json.class_id){
					this.dataportformData.permissionSelected = i;
				}
			}
			let alertmemId = json["alert_mem"];
			for(let i=0;i<this.alarmList.length;i++){
				let json = this.alarmList[i];
				if(alertmemId == json.class_id){
					this.dataportformData.alarmSelected = i;
				}
				
			}

			if(json["frequence"]){
				this.dataportformData.foreverFrequence = json["frequence"];
				this.dataportformData.isForever = 1;
			}else{
				this.dataportformData.isForever = 0;
			}
			if(json["post_content"]){
				this.dataportformData.isAndPost = "1";
				let str = json["post_content"];
				str.replace('\"','"');
				console.log(str);

				str = str.substring(1,str.length-1); //去掉前后的花括号

				let arr = str.split(':')
				if(arr[0].indexOf("urlEncode") > -1){
					this.dataportformData.formatSelect = 1;

					let newstr = '"urlEncode":'
					let newarr = str.split(newstr);
					if(newarr.length > 1){
						let content = newarr[1];
						content = content.substring(1,content.length -1); //去掉前后的双引号
						this.dataportformData.andPostCon = content;
					}else{
						this.dataportformData.andPostCon = "";
					}

				}
				if(arr[0].indexOf("Json") > -1){
					this.dataportformData.formatSelect = 0

					let newstr = '"Json":'
					let newarr = str.split(newstr);
					if(newarr.length > 1){
						let content = newarr[1];
						content = content.substring(1,content.length -1); //去掉前后的双引号
						this.dataportformData.andPostCon = content;
					}else{
						this.dataportformData.andPostCon = "";
					}

				}
			}else{ 
				this.dataportformData.isAndPost = "0";
				this.dataportformData.andPostCon = "";
			}



			if(json["monitor_args"]){
				if(json["monitor_args"] == 0){
					this.dataportformData.isAddPlat = false;
				}else{
					this.dataportformData.isAddPlat = true;
				}
			}else{
				this.dataportformData.isAddPlat = false;
			}

			if(json["monitoruser"]){
				if(json["monitoruser"]){
					this.dataportformData.isNeedAccount = 1;
					this.dataportformData.userSelected = json["monitoruser"];
				}else{
					this.dataportformData.isNeedAccount = 0;
				}
			}else{
				this.dataportformData.isNeedAccount = "0";
			}

			if(json["criterion"]){
				this.dataportformData.ruleselects.splice(0,this.dataportformData.ruleselects.length)
				let str = json["criterion"];
				str.replace('\"','"');
				// let strtoJson = JSON.parse(str);
				let strtoJson = eval("("+str+")"); //当json里嵌套数组就抱歉 问题有待解决
				let val = "";
				let total = 0;
				let arr = [];
				
				for(var k in strtoJson){
					if(strtoJson[k] && ((typeof strtoJson[k]) == "object")){
						total += strtoJson[k].length;
						let arr = strtoJson[k];
						if(k=="reg"){
							for(let i=0;i<arr.length;i++){
								let newrulesdata = {ruletype:"0","rulecont":arr[i].rule};
								this.dataportformData.ruleselects.push(newrulesdata);
							}
						}else if(k=="count"){
							for(let i=0;i<arr.length;i++){
								let newrulesdata = {ruletype:"1","rulecont":arr[i].rule};
								this.dataportformData.ruleselects.push(newrulesdata);
							}
						}else if(k=="notnull"){
							for(let i=0;i<arr.length;i++){
								let newrulesdata = {ruletype:"2","rulecont":arr[i].rule};
								this.dataportformData.ruleselects.push(newrulesdata);
							}
						}else if(k=="key_exist"){
							for(let i=0;i<arr.length;i++){
								let newrulesdata = {ruletype:"3","rulecont":arr[i].rule};
								this.dataportformData.ruleselects.push(newrulesdata);
							}
						}else if(k=="equal"){
							for(let i=0;i<arr.length;i++){
								let newrulesdata = {ruletype:"4","rulecont":arr[i].rule};
								this.dataportformData.ruleselects.push(newrulesdata);
							}
						}						
						
					}
				}
				if(total == 0) {
					this.dataportformData.ruleselects.push({ruletype:"0",rulecont:""});
				}

			}else{
			}

		}
	},

	methods: {
		addTestManageRules:function(){
			this.dataportformData.ruleselects.push({ruletype:"0",rulecont:""});
		},
		deleteTestManageRules:function(index){
			this.dataportformData.ruleselects.splice(index,1);
		},
		testManageRules:function(index){
			this.dataportformData.inputServerUrl = vueGetData.trim(this.dataportformData.inputServerUrl);
			this.dataportformData.referer = vueGetData.trim(this.dataportformData.referer);
			this.dataportformData.userAgent = vueGetData.trim(this.dataportformData.userAgent);
			let data = {
				"oper":6,
				"type":1,
				"url":this.dataportformData.inputServerUrl,
				"referer":this.dataportformData.referer,
				"user_agent":this.dataportformData.userAgent,
				"time_out":this.dataportformData.timeOut
			}
			this.dataportformData.ruleselects[index].rulecont = vueGetData.trim(this.dataportformData.ruleselects[index].rulecont)
			let rulecont = this.dataportformData.ruleselects[index].rulecont;
			let rule = this.dataportformData.ruleselects[index].ruletype;
			let json = {};

			if(rule == 0){
				json = {"reg":[{"rule":rulecont}]}
			}else if(rule == 1){
				json = {"count":[{"rule":rulecont}]}
			}else if(rule == 2){
				json = {"notnull":[{"rule":rulecont}]}
			}else if(rule == 3){
				json = {"key_exist":[{"rule":rulecont}]}
			}else if(rule == 4){
				json = {"equal":[{"rule":rulecont}]}
			}

			data["criterion"] = json;

			if(this.dataportformData.isAndPost == 1) {
				let str = "";
				if(!this.dataportformData.andPostCon || this.dataportformData.andPostCon == "undefined" || this.dataportformData.andPostCon == ""){
					str = "";
				}else{
					let content = this.dataportformData.andPostCon;
					str = content;
				}
				data["request_type"] = this.dataportformData.formatSelect
				if(this.dataportformData.formatSelect == 0){
					data["post_content"] = {"Json":str};
				}else if(this.dataportformData.formatSelect == 1){
					data["post_content"] = {"urlEncode":str};
				}

			}

			if(this.dataportformData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.dataportformData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}
			vueGetData.postData("itemmodify",data,function(jsondata){
				console.log(jsondata.body.error_code);
		        if(jsondata.body.error_code === 22000) {
		        	let data = jsondata.body.data;
		        	if(data.check_status == 0){
		        		vueGetData.creatTips("匹配成功");
		        	}else if(data.check_status == 1){
		        		vueGetData.creatTips("规则错误或匹配失败，原因为：" + data.msg);
		        	}
		        }else if(jsondata.body.error_code === 22001) {
		        	vueGetData.creatTips("系统错误")
		        }
	        },function(err){
		        console.log(err);		
	        })

		},
		testRules: function(){
			this.dataportformData.inputServerUrl = vueGetData.trim(this.dataportformData.inputServerUrl);
			this.dataportformData.referer = vueGetData.trim(this.dataportformData.referer);
			this.dataportformData.userAgent = vueGetData.trim(this.dataportformData.userAgent);

			let data = {
				"url":this.dataportformData.inputServerUrl,
				"referer":this.dataportformData.referer,
				"user_agent":this.dataportformData.userAgent,
				"time_out":this.dataportformData.timeOut
			}

			if(this.dataportformData.isAndPost == 1) {
				let str = "";
				this.dataportformData.andPostCon = vueGetData.trim(this.dataportformData.andPostCon);
				if(!this.dataportformData.andPostCon || this.dataportformData.andPostCon == "undefined" || this.dataportformData.andPostCon == ""){
					str = "";
				}else{
					let content = this.dataportformData.andPostCon;
					str = content;
				}
				data["request_type"] = this.dataportformData.formatSelect
				if(this.dataportformData.formatSelect == 0){
					data["post_content"] = {"Json":str};
				}else if(this.dataportformData.formatSelect == 1){
					data["post_content"] = {"urlEncode":str};
				}

			}

			if(this.dataportformData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.dataportformData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}
			let _self = this;
			vueGetData.postData("obtaindata",data,function(jsondata){
				console.log(jsondata.body);
				_self.dataportformData.eleshow = JSON.stringify(jsondata.body);
	        },function(err){
		        console.log(err);		
	        })
		},
		hideDialog: function(){
			//重置初始化数据
			for(let key in this.copyDataportformData){
				if(key == "ruleselects"){
					this.dataportformData[key] = [{ruletype:"0",rulecont:""}];
				}else {
					this.dataportformData[key] = this.copyDataportformData[key]
				}
			}
			document.getElementsByClassName("dialog")[0].style.display = "none";
		},
		addHttp: function(){
			this.dataportformData.itemName = vueGetData.trim(this.dataportformData.itemName);
			this.dataportformData.inputServerUrl = vueGetData.trim(this.dataportformData.inputServerUrl);
			this.dataportformData.referer = vueGetData.trim(this.dataportformData.referer);
			this.dataportformData.userAgent = vueGetData.trim(this.dataportformData.userAgent);
			this.dataportformData.callbackApi = vueGetData.trim(this.dataportformData.callbackApi);
			this.dataportformData.startTime = vueGetData.trim(this.dataportformData.startTime);
			this.dataportformData.endTime = vueGetData.trim(this.dataportformData.endTime);

			if(!this.dataportformData.itemName){
				vueGetData.creatTips("请填写项目名称");
				return false;
			}
			if(!this.dataportformData.inputServerUrl){
				vueGetData.creatTips("请填写服务url");
				return false;
			}
			if(this.permissionList.length<1){
				vueGetData.creatTips("请先添加监控权限组");
				return false;
			}
			if(this.alarmList.length < 1){
				vueGetData.creatTips("请先添加邮件报警组");
				return false;
			}

			let data = {};
			if(this.editinitformdata["oper"] == 5){
				data["oper"] = 5;
				data["taskid"] = this.editinitformdata["taskid"];
			}else{
				data["oper"] = 1;
				data["taskid"] = "";
			}
			data["username"] = vueGetData.username();
			data["product_line"] = this.productId;
			data["item_name"] = this.dataportformData.itemName;
			data["type"] = 1;
			data["level"] = this.dataportformData.levelRadio;
			data["url"] = this.dataportformData.inputServerUrl;
			data["referer"] = this.dataportformData.referer;
			data["user_agent"] = this.dataportformData.userAgent;
			data["time_out"] = this.dataportformData.timeOut;
			data["callback_url"] = this.dataportformData.callbackApi;
			data["mail_count"] = this.dataportformData.mailCount;
			data["message_count"] = this.dataportformData.messageCount;

			let permissionSelectedIndex = this.dataportformData.permissionSelected;
			data["cer_mem"] = this.permissionList[permissionSelectedIndex].class_id
			let alarmSelectedIndex = this.dataportformData.alarmSelected;
			data["alert_mem"] = this.alarmList[alarmSelectedIndex].class_id

			if(this.dataportformData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.dataportformData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}

			if(this.dataportformData.isAndPost == 1) {
				let str = "";
				this.dataportformData.andPostCon = vueGetData.trim(this.dataportformData.andPostCon);
				if(!this.dataportformData.andPostCon || this.dataportformData.andPostCon == "undefined" || this.dataportformData.andPostCon == ""){
					str = "";
				}else{
					let content = this.dataportformData.andPostCon;
					str = content;
				}

				if(str && !vueGetData.isJSON(str)){
					vueGetData.creatTips("post规则:请填写正确的json格式数据");
					return false;
				}

				data["request_type"] = this.dataportformData.formatSelect
				if(this.dataportformData.formatSelect == 0){
					data["post_content"] = {"Json":str};
				}else if(this.dataportformData.formatSelect == 1){
					data["post_content"] = {"urlEncode":str};
				}

			}

			if(this.dataportformData.isForever == 1){
				if(this.dataportformData.foreverFrequence >= 0){
					data["eff_status"] = this.dataportformData.isForever;
					data["frequence"] = this.dataportformData.foreverFrequence;
				}else{
					vueGetData.creatTips("请输入监控频率");
				}
			}

			let json = {"reg":[],"count":[],"notnull":[],"key_exist":[],"equal":[]};
			let arr = this.dataportformData.ruleselects;
			for(let i=0;i<arr.length;i++){
				let rule = arr[i].ruletype;
				arr[i].rulecont = vueGetData.trim(arr[i].rulecont);
				let newjson = {"rule":arr[i].rulecont}
				if(rule == 0){
					json["reg"].push(newjson);
				}else if(rule == 1){
					json["count"].push(newjson);
				}else if(rule == 2){
					json["notnull"].push(newjson);
				}else if(rule == 3){
					json["key_exist"].push(newjson);
				}else if(rule == 4){
					json["equal"].push(newjson);
				}
			}

			var reg =/^([0-1]\d|2[0-3]):[0-5]\d$/;
	        if(reg.test(this.dataportformData.startTime))
	        {
	            data["start_time"] = this.dataportformData.startTime;
	        }
	        else
	        {
	        	vueGetData.creatTips("您的有效监控开始时间输入错误，请重新输入");
				return false;
	        }

	        if(reg.test(this.dataportformData.endTime))
	        {
	            data["end_time"] = this.dataportformData.endTime;
	        }
	        else
	        {
	        	vueGetData.creatTips("您的有效监控结束时间输入错误，请重新输入");
				return false;
	        }

			data["criterion"] = json;
			console.log(json)
			let  _self = this;
			vueGetData.postData("itemmodify",data,function(jsondata){
				let error_code = jsondata.body.error_code;
		        if(error_code === 22000) {
		        	_self.$store.dispatch('getManagetableList',_self.dialogfetchdata)
		        	_self.hideDialog();
		        }else if(error_code === 22001){
		        	vueGetData.creatTips("系统错误请联系管理员查看问题");
		        	_self.hideDialog();
		        }else if(error_code === 22005){
		        	console.log(error_code)
		        	vueGetData.creatTips(jsondata.body.error_msg);
		        }else if(error_code === 22008){
		        	vueGetData.creatTips("操作非法");
		        	_self.hideDialog();
		        }else if(error_code === 22009){
		        	vueGetData.creatTips("没有权限，请联系管理员开通");
		        	_self.hideDialog();
		        }else if(error_code === 22452){
		        	vueGetData.creatTips("用户未登录");
		        	_self.hideDialog();
		        }	
	        },function(err){
		        console.log(err);		
	        })

		}
	},
	created: function(){
		//保存初始化数据
		for(let key in this.dataportformData){
			this.copyDataportformData[key] = this.dataportformData[key]
		}

	},

}
</script>
<style>
.subDivContent {
	font-size: 14px;
}
.dialog .dialogContent .dialogSubDiv input[type=text]{
	
}
.validtime{
	width:200px!important;
}
</style>