<template>
	<div class="dialog">
		<div class="dialogOverlay"></div>
		<div class="dialogContent">
			<div class="dialogClose" @click="hideDialog"></div>
			<div class="dialogDetail">
				<div class="dialogTitle">{{formData.dialogTitle}}</div>
				<div class="dialogDiv">
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控项名称</div>
						<div class="subDivContent"><input type="text" class="alarm-config-dialog-name" placeholder="" v-model="formData.itemName"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控分级</div>
						<div class="subDivContent">
							<label><input type="radio" name="level" value="0" v-model="formData.levelRadio" id="level0"><span for="level0">0级</span></label>
							<label><input type="radio" name="level" value="1" v-model="formData.levelRadio" id="level1"><span for="level1">1级</span></label>
							<label><input type="radio" name="level" value="2" v-model="formData.levelRadio" id="level2"><span for="level2">2级</span></label>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">服务url</div>
						<div class="subDivContent"><input type="text" class="alarm-config-dialog-name" placeholder="" v-model="formData.inputServerUrl"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">加参</div>
						<div class="subDivContent">
							<label style="width:100%"><input type="checkbox" v-model="formData.isAddPlat">请求时URL是否加入参数'_plat=monitor'</label>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">请求方式</div>
						<div class="subDivContent">
							<label><input type="radio" value="0" v-model="formData.isAndPost">get</label>
							<label><input type="radio" value="1" v-model="formData.isAndPost">post</label>

								<div class="subDivCard" v-if="formData.isAndPost == 1">
				 					<div class="dialogSubDiv">
										<div class="subDivTitle">postData格式</div>
										<div class="subDivContent" style="display: inline-block;width: 400px;">
											<select name="format" class="long-select" v-model="formData.formatSelect">
												<option v-for="(value,index) in formats" :value="index">{{value}}</option>
											</select>
										</div>
									</div>
									<textarea style="width: 100%; height: 100px;" placeholder='post数据格式为Json或urlEncode,例如：{"key":"value"}' id="andPostCon" v-model="formData.andPostCon"></textarea>
								</div>
								<div class="subDivCard" v-if="formData.isAndPost == 0">
				 					<div class="dialogSubDiv">
										<div class="subDivTitle" style="color:#ccc">postData格式</div>
										<div class="subDivContent" style="display: inline-block;width: 400px;">
											<select name="format" class="long-select" v-model="formData.formatSelect" disabled="disabled">
												<option v-for="(value,index) in formats" :value="index">{{value}}</option>
											</select>
										</div>
									</div>
									<textarea style="width: 100%; height: 100px;" placeholder='post数据格式为Json或urlEncode,例如：{"key":"value"}' id="andPostCon" v-model="formData.andPostCon" disabled="disabled"></textarea>
								</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">账号选择</div>
						<div class="subDivContent">
							<div class="subDivCard">
								<label><input type="radio" name="isNeedAccount" value="0" v-model="formData.isNeedAccount">不需要用户账号</label>
								<label><input type="radio" name="isNeedAccount" value="1" v-model="formData.isNeedAccount">需要用户账号</label>
								<div class="">
									<select name="" class="long-select" disabled="disabled"  v-if="formData.isNeedAccount == 0">
										<!-- <option v-for="value in userAccounts" :value="value.acount_name" >{{value.acount_name}}</option> -->
										<option></option>
									</select>
									<select name="" class="long-select" v-else="formData.isNeedAccount == 1" v-model="formData.userSelected">
										<option v-for="value in userAccounts" :value="value.acount_name">{{value.acount_name}}</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle" style="line-height: 30px;">获取页面数据</div>
						<div class="subDivContent"><button class="blue-button" @click="testgetPageData">获取数据</button></div>
					</div>
 					<div class="dialogSubDiv">
						<div class="subDivTitle">页面数据表现</div>
						<div class="subDivContent">
							<textarea placeholder="" id="eleshow" v-model="formData.eleshow" class="long-textarea"></textarea>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">referer</div>
						<div class="subDivContent"><input type="text" class="https-dialog-referer" placeholder="" v-model="formData.referer"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">userAgent</div>
						<div class="subDivContent"><input type="text" class="https-dialog-useragent" placeholder="" v-model="formData.userAgent"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控预期设置</div>
						<div class="subDivContent">
							<div class="info-label">返回码:
								<span class="lebel-sm"><input type="checkbox" id="status200" value="200" v-model="formData.criterion"><label for="status200">200</label></span>
								<span class="lebel-sm"><input type="checkbox" id="status201" value="201" v-model="formData.criterion"><label for="status201">201</label></span>
								<span class="lebel-sm"><input type="checkbox" id="status202" value="202" v-model="formData.criterion"><label for="status202">202</label></span>
								<span class="lebel-sm"><input type="checkbox" id="status206" value="206" v-model="formData.criterion"><label for="status206">206</label></span>
								<span class="lebel-sm"><input type="checkbox" id="status301" value="301" v-model="formData.criterion"><label for="status301">301</label></span>
								<span class="lebel-sm"><input type="checkbox" id="status302" value="302" v-model="formData.criterion"><label for="status302">302</label></span>
								<span class="lebel-sm"><input type="checkbox" id="status307" value="307" v-model="formData.criterion"><label for="status307">307</label></span>
								<span class="lebel-sm"><input type="checkbox" id="status400" value="400" v-model="formData.criterion"><label for="status400">400</label></span>
		
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">url请求超时</div>
						<div class="subDivContent">
							<label><input type="number" min=1 class="alarm-config-dialog-name sm-size" placeholder="" v-model="formData.timeOut" /></label><span class="showfont">毫秒(ms)</span>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle" style="line-height: 30px;">测试断言</div>
						<div class="subDivContent"><button class="blue-button" @click="testRules">测试规则</button></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle" style="line-height:20px;">自定义报警回调API</div>
						<div class="subDivContent"><input type="text" class="alarm-config-dialog-name" placeholder="" v-model="formData.callbackApi"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">执行方案</div>
						<div class="subDivContent">
							<label><input type="radio" name="alarm-level" value="1" v-model="formData.isForever">永久</label>
							<label><input type="radio" name="alarm-level" value="0" v-model="formData.isForever">一次性</label>
							<div class="subDivCard" v-if="formData.isForever == 1" style="min-height:50px;">
								<label class="info-label">监控频率<input type="number" class="foreverFrequence sm-size" placeholder="" v-model="formData.foreverFrequence" min=1>分钟(m)
									</label>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控权限组</div>
						<div class="subDivContent">
							<select name="" class="long-select" v-model="formData.permissionSelected">
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
									<select name="" class="long-select" v-model="formData.alarmSelected">
										<option v-for="(value,index) in alarmList" :value="index">{{value.class_name}}</option>
									</select>
								</div>
								<span class="cardContent">邮件报警频率：连续<input type="number" min="0" class="dialog-short-input email-alarm-rates" v-model="formData.mailCount">次失败后触发报警</span>
								<span class="cardContent">短信报警频率：连续<input type="number" min="0" class="dialog-short-input message-alarm-rates" v-model="formData.messageCount">次失败后触发报警</span>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">有效监控时间</div>
						<div class="subDivContent">
							<input class="validtime" type="text" v-model="formData.startTime"> 至 <input class="validtime" type="text" v-model="formData.endTime">
						</div>
					</div>
					<!-- <div class="dialogSubDiv">
						<div class="subDivTitle">备注</div>
						<div class="subDivContent"><input type="text" class="dialog-long-input note"></div>
					</div> -->
					<div class="dialogButtonDiv">
						<a href="javascript:;" class="dialog-blue-button" @click="addHttp" v-show="this.formData.isShowSavebtn">保存</a>
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
	name: "Dialog",
	data () {
		return {
			formData: {
				itemName: '',
				typeSelected: 0,
				levelRadio: "0",
				inputServerUrl: '',
				isAddPlat: false,
				isAndPost: "0",
				andPostCon: "",
				isNeedAccount: "0",
				userSelected: "",
				callbackApi: "",
				isForever: 1,
				foreverFrequence:1,
				permissionSelected: 0,
				alarmSelected: 0,
				mailCount: 1,
				messageCount:3,
				timeOut:2000,
				eleshow:"",
				startTime: "00:00",
				endTime:"23:59",
				//规则
				criterion: ["200"],
				dialogTitle: '新建http可用性监控',
				referer:"",
				userAgent:"",
				formatSelect:0,
				isShowSavebtn: true	//当查看时不需要保存按钮  true为要展示保存按钮
			},
			longSelect: ["http可用性","数据接口","页面元素"],
			formats: ["Json","urlEncode"],
			type: ["http可用性"],
			childForHttpTable:[],
			childHttptabledata:{},
			productline:"",
			copyformData:{}

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
		problemgradeSelected:function(){
			if((typeof this.problemgradeSelected) == "undefined"){
				this.problemgradeSelected = 0;
			}				
		},
		permissioclassid: function() {
			let id = this.permissioclassid;
			for(let i=0;i<this.permissionList.length;i++){
				let json = this.permissionList[i];
				if(id == json.class_id){
					this.formData.permissionSelected = i;
					return false;
				}
				
			}

		},
		alertclassid: function() {
			// this.formData.alarmSelected = this.alertclassid;
			let id = this.alertclassid;
			for(let i=0;i<this.alarmList.length;i++){
				let json = this.alarmList[i];
				if(id == json.class_id){
					this.formData.alarmSelected = i;
					return false;
				}
				
			}
		},
		robotacountid: function() {
			this.formData.userSelected = this.robotacountid;
		},
		editinitformdata:function(){
			let json = this.editinitformdata;
			this.formData.isShowSavebtn = json["edit"];
			this.formData.itemName = json["item_name"];
			this.formData.typeSelected = json["type"];
			this.formData.levelRadio = json["level"];
			this.formData.inputServerUrl = json["url"];
			this.formData.productline = json["product_line"];
			this.formData.callbackApi = json["callback_url"];
			this.formData.mailCount = json["mail_count"];
			this.formData.messageCount = json["message_count"];
			this.formData.timeOut = json["time_out"];
			this.formData.referer = json["referer"];
			this.formData.userAgent = json["user_agent"];
			this.formData.startTime = json["start_time"];
			this.formData.endTime = json["end_time"];

			let cermemId = json["cer_mem"];
			for(let i=0;i<this.permissionList.length;i++){
				let json = this.permissionList[i];
				if(cermemId == json.class_id){
					this.formData.permissionSelected = i;
				}
			}
			let alertmemId = json["alert_mem"];
			for(let i=0;i<this.alarmList.length;i++){
				let json = this.alarmList[i];
				if(alertmemId == json.class_id){
					this.formData.alarmSelected = i;
				}
				
			}


			if(json["frequence"]){
				this.formData.foreverFrequence = json["frequence"];
				this.formData.isForever = 1;
			}else{
				this.formData.isForever = 0;
			}

			let criterionstr = json["criterion"];
			criterionstr.replace('\"','"');
			let strtoJson = JSON.parse(criterionstr);
			if(strtoJson["http_code"]){
				this.formData.criterion = strtoJson["http_code"].split(",");
			}

			if(json["monitor_args"]) {
				if(json[key] == 0){
					this.formData.isAddPlat = false;
				}else{
					this.formData.isAddPlat = true;
				}
			}else{
					this.formData.isAddPlat = false;
			}
			if(json["monitoruser"]){
				this.formData.isNeedAccount = 1;
				this.formData.userSelected = json["monitoruser"];
			}else{
				this.formData.isNeedAccount = 0;
			}

			if(json["post_content"]){
				this.formData.isAndPost = "1";
				let str = json["post_content"];
				str.replace('\"','"');

				str = str.substring(1,str.length-1); //去掉前后的花括号

				let arr = str.split(':')
				if(arr[0].indexOf("urlEncode") > -1){
					this.formData.formatSelect = 1;

					let newstr = '"urlEncode":'
					let newarr = str.split(newstr);
					if(newarr.length > 1){
						let content = newarr[1];
						content = content.substring(1,content.length -1); //去掉前后的双引号
						this.formData.andPostCon = content;
					}else{
						this.formData.andPostCon = "";
					}

				}
				if(arr[0].indexOf("Json") > -1){
					this.formData.formatSelect = 0

					let newstr = '"Json":'
					let newarr = str.split(newstr);
					if(newarr.length > 1){
						let content = newarr[1];
						content = content.substring(1,content.length -1); //去掉前后的双引号
						this.formData.andPostCon = content;
					}else{
						this.formData.andPostCon = "";
					}

				}
			}else{ 
				this.formData.isAndPost = "0";
				this.formData.andPostCon = "";
			}

		}
	},
	methods: {
		testgetPageData: function(){
			this.formData.inputServerUrl = vueGetData.trim(this.formData.inputServerUrl);
			this.formData.referer = vueGetData.trim(this.formData.referer);
			this.formData.userAgent = vueGetData.trim(this.formData.userAgent);
			let data = {
				"url":this.formData.inputServerUrl,
				"referer":this.formData.referer,
				"user_agent":this.formData.userAgent,
				"time_out":this.formData.timeOut
			}

			if(this.formData.isAndPost == 1) {
				let str = "";
				this.formData.andPostCon = vueGetData.trim(this.formData.andPostCon);
				if(!this.formData.andPostCon || this.formData.andPostCon == "undefined" || this.formData.andPostCon == ""){
					str = "";
				}else{
					let content = this.formData.andPostCon;
					str = content;
				}
				data["request_type"] = this.formData.formatSelect
				if(this.formData.formatSelect == 0){
					data["post_content"] = {"Json":str};
				}else if(this.formData.formatSelect == 1){
					data["post_content"] = {"urlEncode":str};
				}

			}

			if(this.formData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.formData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}
			let _self = this;
			vueGetData.postData("obtaindata",data,function(jsondata){
				console.log(jsondata.body);
				_self.formData.eleshow = JSON.stringify(jsondata.body);
	        },function(err){
		        console.log(err);		
	        })

		},
		testRules: function(){
			this.formData.inputServerUrl = vueGetData.trim(this.formData.inputServerUrl);
			this.formData.referer = vueGetData.trim(this.formData.referer);
			this.formData.userAgent = vueGetData.trim(this.formData.userAgent);
			let data = {
				"oper":6,
				"type":0,
				"url":this.formData.inputServerUrl,
				"monitoruser":"",
				"referer":this.formData.referer,
				"user_agent":this.formData.userAgent,
				// "criterion":{"http_code":this.formData.criterion},
				"post_content": '',
				"time_out":this.formData.timeOut
			}
			if(!this.formData.isAddPlat){
				data["monitor_args"] = 0;
			}else{
				data["monitor_args"] = 1;
			}
			let str = "";
			for(let i in this.formData.criterion){
				str += this.formData.criterion[i] + ",";
			}
			data["criterion"] = {"http_code":str.substring(0,str.length-1)};
			console.log(data["criterion"]);

			if(this.formData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.formData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}else{
				data["monitoruser"] = ""
			}

			if(this.formData.isAndPost == 1) {
				let str = "";
				this.formData.andPostCon = vueGetData.trim(this.formData.andPostCon);
				if(!this.formData.andPostCon || this.formData.andPostCon == "undefined" || this.formData.andPostCon == ""){
					str = "";
				}else{
					let content = this.formData.andPostCon;
					str = content;
				}
				data["request_type"] = this.formData.formatSelect
				if(this.formData.formatSelect == 0){
					data["post_content"] = {"Json":str};
				}else if(this.formData.formatSelect == 1){
					data["post_content"] = {"urlEncode":str};
				}

			}


			console.log(data)
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
		        }else {
		        	vueGetData.creatTips("错误原因："+jsondata.body.error_msg)
		        }
	        },function(err){
		        console.log(err);		
	        })
		},
		hideDialog: function(){
			//重置初始化数据
			for(let key in this.copyformData){
				this.formData[key] = this.copyformData[key]
			}
			document.getElementsByClassName("dialog")[0].style.display = "none";
		},
		addHttp: function(){
			this.formData.itemName = vueGetData.trim(this.formData.itemName);
			this.formData.inputServerUrl = vueGetData.trim(this.formData.inputServerUrl);
			this.formData.referer = vueGetData.trim(this.formData.referer);
			this.formData.userAgent = vueGetData.trim(this.formData.userAgent);
			this.formData.callbackApi = vueGetData.trim(this.formData.callbackApi);
			this.formData.startTime = vueGetData.trim(this.formData.startTime);
			this.formData.endTime = vueGetData.trim(this.formData.endTime);

			if(!this.formData.itemName){
				vueGetData.creatTips("请填写项目名称");
				return false;
			}
			if(!this.formData.inputServerUrl){
				vueGetData.creatTips("请填写服务url");
				return false;
			}
			if(this.formData.criterion.length<1){
				vueGetData.creatTips("请选择监控预期设置的返回码");
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

			data["item_name"] = this.formData.itemName;
			data["type"] = 0;
			data["level"] = this.formData.levelRadio;
			data["url"] = this.formData.inputServerUrl;
			data["callback_url"] = this.formData.callbackApi;
			data["mail_count"] = this.formData.mailCount;
			data["message_count"] = this.formData.messageCount;
			data["time_out"] = this.formData.timeOut;
			data["referer"] = this.formData.referer;
			data["user_agent"] = this.formData.userAgent;

			let str = "";
			for(let i in this.formData.criterion){
				str += this.formData.criterion[i] + ",";
			}
			data["criterion"] = {"http_code":str.substring(0,str.length-1)};
			console.log(data["criterion"]);

			let permissionSelectedIndex = this.formData.permissionSelected;
			data["cer_mem"] = this.permissionList[permissionSelectedIndex].class_id
			let alarmSelectedIndex = this.formData.alarmSelected;
			data["alert_mem"] = this.alarmList[alarmSelectedIndex].class_id

			if(typeof data["cer_mem"] === "undefined"){
				vueGetData.creatTips("权限分组不能为空");
				return false;
			}

			if(typeof data["alert_mem"] === "undefined"){
				vueGetData.creatTips("监控报警分组不能为空");
				return false;
			}
			if(!this.formData.isAddPlat){
				data["monitor_args"] = 0;
			}else{
				data["monitor_args"] = 1;
			}
			if(this.formData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.formData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}

			if(this.formData.isAndPost == 1) {
				let str = "";
				this.formData.andPostCon = vueGetData.trim(this.formData.andPostCon);
				if(!this.formData.andPostCon || this.formData.andPostCon == "undefined" || this.formData.andPostCon == ""){
					str = "";
				}else{
					let content = this.formData.andPostCon;
					str = content;
				}
				if(str && !vueGetData.isJSON(str)){
					vueGetData.creatTips("post规则:请填写正确的json格式数据");
					return false;
				}
				data["request_type"] = this.formData.formatSelect
				if(this.formData.formatSelect == 0){
					data["post_content"] = {"Json":str};
				}else if(this.formData.formatSelect == 1){
					data["post_content"] = {"urlEncode":str};
				}

			}
			if(this.formData.isForever == 1){
				if(this.formData.foreverFrequence >= 0){
					data["eff_status"] = this.formData.isForever;
					data["frequence"] = this.formData.foreverFrequence;
				}else{
					vueGetData.creatTips("请输入监控频率");
				}
				
			}

			var reg =/^([0-1]\d|2[0-3]):[0-5]\d$/;
	        if(reg.test(this.formData.startTime))
	        {
	            data["start_time"] = this.formData.startTime;
	        }
	        else
	        {
	        	vueGetData.creatTips("您的有效监控开始时间输入错误，请重新输入");
				return false;
	        }

	        if(reg.test(this.formData.endTime))
	        {
	            data["end_time"] = this.formData.endTime;
	        }
	        else
	        {
	        	vueGetData.creatTips("您的有效监控结束时间输入错误，请重新输入");
				return false;
	        }

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
		for(let key in this.formData){
			this.copyformData[key] = this.formData[key]
		}

	},
	mounted: function(){
		//通信组件变量
		this.childHttptabledata = this.httptabledata;
	}

}
</script>
<style lang="less">
.dialogSubDiv {
	div{

		.info-label {
			width: 100%;
			margin: 0;
			font-size: 13px;
			line-height: 40px;

			span.lebel-sm{
				padding: 3px 6px;

				label{
					width: 30px !important;
				}
			}
		}
	}
}
</style>