<template>
	<div class="dialog">
		<div class="dialogOverlay"></div>
		<div class="dialogContent">
			<div class="dialogClose" @click="hideDialog"></div>
			<div class="dialogDetail">
				<div class="dialogTitle">{{elesformData.dialogTitle}}</div>
				<div class="dialogDiv">
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控项名称</div>
						<div class="subDivContent"><input type="text" class="alarm-config-dialog-name" placeholder="" v-model="elesformData.itemName"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控分级</div>
						<div class="subDivContent">
							<label><input type="radio" name="level" value="0" v-model="elesformData.levelRadio" id="level0"><span for="level0">0级</span></label>
							<label><input type="radio" name="level" value="1" v-model="elesformData.levelRadio" id="level1"><span for="level1">1级</span></label>
							<label><input type="radio" name="level" value="2" v-model="elesformData.levelRadio" id="level2"><span for="level2">2级</span></label>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">服务url</div>
						<div class="subDivContent"><input type="text" class="alarm-config-dialog-name" placeholder="" v-model="elesformData.inputServerUrl"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">referer</div>
						<div class="subDivContent"><input type="text" class="eles-dialog-referer" placeholder="" v-model="elesformData.referer"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">userAgent</div>
						<div class="subDivContent"><input type="text" class="eles-dialog-useragent" placeholder="" v-model="elesformData.userAgent"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">验证表达式</div>
						<div class="subDivContent">
							<div class="subDivCard">
								<textarea style="width: 100%; height: 100px;" placeholder="可用#增加验证规则注释,提升标记邮件可读性" id="andPostCon" v-model="elesformData.criterion"></textarea>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle" style="line-height: 30px;">测试断言</div>
						<div class="subDivContent"><button class="blue-button" @click="testRules">测试断言</button></div>
					</div>

					<div class="dialogSubDiv">
						<div class="subDivTitle">账号选择</div>
						<div class="subDivContent">
							<div class="subDivCard">
								<label><input type="radio" name="isNeedAccount" value="0" v-model="elesformData.isNeedAccount">不需要用户账号</label>
								<label><input type="radio" name="isNeedAccount" value="1" v-model="elesformData.isNeedAccount">需要用户账号</label>
								<div class="">
									<select name="" class="long-select" disabled="disabled"  v-if="elesformData.isNeedAccount == 0">
										<option></option>
									</select>
									<select name="" class="long-select" v-else="elesformData.isNeedAccount == 1" v-model="elesformData.userSelected">
										<option v-for="value in userAccounts" :value="value.acount_name">{{value.acount_name}}</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">页面加载时间</div>
						<div class="subDivContent">
							<label><input type="number" min=1 class="alarm-config-dialog-name sm-size" placeholder="" v-model="elesformData.timeOut" /></label><span class="showfont">毫秒(ms)</span>
						</div>
					</div>

					<div class="dialogSubDiv">
						<div class="subDivTitle" style="line-height:20px;">自定义报警回调API</div>
						<div class="subDivContent"><input type="text" class="alarm-config-dialog-name" placeholder="" v-model="elesformData.callbackApi"></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">执行方案</div>
						<div class="subDivContent">
							<label><input type="radio" name="alarm-level" value="1" v-model="elesformData.isForever">永久</label>
							<label><input type="radio" name="alarm-level" value="0" v-model="elesformData.isForever">一次性</label>
							<div class="subDivCard" v-if="elesformData.isForever == 1" style="min-height:50px;">
								<label class="info-label">监控频率<input type="number" class="foreverFrequence sm-size" placeholder="" v-model="elesformData.foreverFrequence" min=1>分钟(m)
									</label>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">监控权限组</div>
						<div class="subDivContent">
							<select name="" class="long-select" v-model="elesformData.permissionSelected">
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
									<select name="" class="long-select" v-model="elesformData.alarmSelected">
										<option v-for="(value,index) in alarmList" :value="index">{{value.class_name}}</option>
									</select>
								</div>
								<span class="cardContent">邮件报警频率：连续<input type="number" min="0" class="dialog-short-input email-alarm-rates" v-model="elesformData.mailCount">次失败后触发报警</span>
								<span class="cardContent">短信报警频率：连续<input type="number" min="0" class="dialog-short-input message-alarm-rates" v-model="elesformData.messageCount">次失败后触发报警</span>
							</div>
						</div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">有效监控时间</div>
						<div class="subDivContent">
							<input class="validtime" type="text" v-model="elesformData.startTime"> 至 <input class="validtime" type="text" v-model="elesformData.endTime">
						</div>
					</div>
					<!-- <div class="dialogSubDiv">
						<div class="subDivTitle">备注</div>
						<div class="subDivContent"><input type="text" class="dialog-long-input note"></div>
					</div> -->
					<div class="dialogButtonDiv">
						<a href="javascript:;" class="dialog-blue-button" @click="addHttp" v-show="this.elesformData.isShowSavebtn">保存</a>
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
	name: "DialogAddEles",
	data () {
		return {
			elesformData: {
				itemName: '',
				typeSelected: 2,
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
				timeOut:5000,
				startTime: "00:00",
				endTime:"23:59",
				//规则
				criterion: "",
				dialogTitle: '新建页面元素监控',
				referer:"",
				userAgent:"",
				verify:"",
				isShowSavebtn: true	//当查看时不需要保存按钮  true为要展示保存按钮
			},
			longSelect: ["http可用性","数据接口","页面元素"],
			type: ["页面元素"],
			// childForHttpTable:[],
			childHttptabledata:{},
			productline:"",
			copyElesformData:{}

		}
	},
	props: ["httptableLists","editinitelesformData"],
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
		// permissioclassid: function() {
		// 	this.elesformData.permissionSelected = this.permissioclassid;
		// },
		// alertclassid: function() {
		// 	this.elesformData.alarmSelected = this.alertclassid;
		// },
		permissioclassid: function() {
			let id = this.permissioclassid;
			for(let i=0;i<this.permissionList.length;i++){
				let json = this.permissionList[i];
				if(id == json.class_id){
					this.elesformData.permissionSelected = i;
					return false;
				}
				
			}
		},
		alertclassid: function() {
			let id = this.alertclassid;
			for(let i=0;i<this.alarmList.length;i++){
				let json = this.alarmList[i];
				if(id == json.class_id){
					this.elesformData.alarmSelected = i;
					return false;
				}
				
			}
		},
		robotacountid: function() {
			this.elesformData.userSelected = this.robotacountid;
		},

		httptableLists:function(){
			console.log("表格更新")
		},
		httptabledata:function(){
			console.log(this.dialogfetchdata)
		},
		editinitelesformData:function(){
			let json = this.editinitelesformData;
			this.elesformData.isShowSavebtn = json["edit"];
			this.elesformData.itemName = json["item_name"];
			this.elesformData.typeSelected = json["type"];
			this.elesformData.levelRadio = json["level"];
			this.elesformData.inputServerUrl = json["url"];
			this.elesformData.productline = json["product_line"];
			this.elesformData.callbackApi = json["callback_url"];
			this.elesformData.mailCount = json["mail_count"];
			this.elesformData.messageCount = json["message_count"];
			this.elesformData.timeOut = json["time_out"];
			this.elesformData.criterion = json["criterion"];
			this.elesformData.referer = json["referer"];
			this.elesformData.userAgent = json["user_agent"];
			this.elesformData.startTime = json["start_time"];
			this.elesformData.endTime = json["end_time"];

			let cermemId = json["cer_mem"];
			for(let i=0;i<this.permissionList.length;i++){
				let json = this.permissionList[i];
				if(cermemId == json.class_id){
					this.elesformData.permissionSelected = i;
				}
			}
			let alertmemId = json["alert_mem"];
			for(let i=0;i<this.alarmList.length;i++){
				let json = this.alarmList[i];
				if(alertmemId == json.class_id){
					this.elesformData.alarmSelected = i;
				}
			}


			if(json["frequence"]){
				this.elesformData.foreverFrequence = json["frequence"];
				this.elesformData.isForever = 1;
			}else{
				this.elesformData.isForever = 0;
			}

			if(json["monitor_args"]) {
				if(json["monitor_args"] == 0){
					this.elesformData.isAddPlat = false;
				}else{
					this.elesformData.isAddPlat = true;
				}
			}else{
					this.elesformData.isAddPlat = false;
			}

			if(json["monitoruser"]){
				this.elesformData.isNeedAccount = 1;
				this.elesformData.userSelected = json["monitoruser"];
			}else{
				this.elesformData.isNeedAccount = 0;
			}
		}
	},
	methods: {
		testRules: function(){
			this.elesformData.inputServerUrl = vueGetData.trim(this.elesformData.inputServerUrl);
			this.elesformData.referer = vueGetData.trim(this.elesformData.referer);
			this.elesformData.userAgent = vueGetData.trim(this.elesformData.userAgent);
			this.elesformData.criterion = vueGetData.trim(this.elesformData.criterion);

			let data = {
				"oper":6,
				"type":2,
				"url":this.elesformData.inputServerUrl,
				"monitoruser":"",
				"referer":this.elesformData.referer,
				"user_agent":this.elesformData.userAgent,
				// "criterion":this.elesformData.verify,
				"criterion":this.elesformData.criterion,
				"post_content": '',
				"time_out":this.elesformData.timeOut
			}
			if(this.elesformData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.elesformData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}else{
				data["monitoruser"] = ""
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
		        }
	        },function(err){
		        console.log(err);		
	        })
		},
		hideDialog: function(){
			//重置初始化数据
			for(let key in this.copyElesformData){
				this.elesformData[key] = this.copyElesformData[key]
			}
			document.getElementsByClassName("dialog")[0].style.display = "none";
		},
		addHttp: function(){
			this.elesformData.itemName = vueGetData.trim(this.elesformData.itemName);
			this.elesformData.inputServerUrl = vueGetData.trim(this.elesformData.inputServerUrl);
			this.elesformData.referer = vueGetData.trim(this.elesformData.referer);
			this.elesformData.userAgent = vueGetData.trim(this.elesformData.userAgent);
			this.elesformData.callbackApi = vueGetData.trim(this.elesformData.callbackApi);
			this.elesformData.criterion = vueGetData.trim(this.elesformData.criterion);
			this.elesformData.startTime = vueGetData.trim(this.elesformData.startTime);
			this.elesformData.endTime = vueGetData.trim(this.elesformData.endTime);

			if(!this.elesformData.itemName){
				vueGetData.creatTips("请填写项目名称");
				return false;
			}
			if(!this.elesformData.inputServerUrl){
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
			if(this.editinitelesformData["oper"] == 5){
				data["oper"] = 5;
				data["taskid"] = this.editinitelesformData["taskid"];
			}else{
				data["oper"] = 1;
				data["taskid"] = "";
			}

			data["username"] = vueGetData.username();
			data["product_line"] = this.productId;
			data["item_name"] = this.elesformData.itemName;
			data["type"] = 2;
			data["level"] = this.elesformData.levelRadio;
			data["url"] = this.elesformData.inputServerUrl;
			data["callback_url"] = this.elesformData.callbackApi;
			data["mail_count"] = this.elesformData.mailCount;
			data["message_count"] = this.elesformData.messageCount;
			data["time_out"] = this.elesformData.timeOut;
			data["criterion"] = this.elesformData.criterion;
			data["referer"] = this.elesformData.referer;
			data["user_agent"] = this.elesformData.userAgent;

			if(!this.elesformData.isAddPlat){
				data["monitor_args"] = 0;
			}else{
				data["monitor_args"] = 1;
			}

			if(this.elesformData.isNeedAccount){
				for(let key in this.userAccounts){
					let json = this.userAccounts[key];
					if(this.elesformData.userSelected == json.acount_name){
						data["monitoruser"] = json.tel_no;
					}
				}
			}else{
				data["monitoruser"] = ""
			}
			// for(let key in this.permissionList){
			// 	let json = this.permissionList[key];
			// 	if(this.elesformData.permissionSelected == json.class_name){
			// 		data["cer_mem"] = json.class_id;
			// 	}
			// }
			// for(let key in this.alarmList){
			// 	let json = this.alarmList[key];
			// 	if(this.elesformData.alarmSelected == json.class_name){
			// 		data["alert_mem"] = json.class_id;
			// 	}
			// }

			let permissionSelectedIndex = this.elesformData.permissionSelected;
			data["cer_mem"] = this.permissionList[permissionSelectedIndex].class_id
			let alarmSelectedIndex = this.elesformData.alarmSelected;
			data["alert_mem"] = this.alarmList[alarmSelectedIndex].class_id

			if(this.elesformData.isForever == 1){
				if(this.elesformData.foreverFrequence >= 0){
					data["eff_status"] = this.elesformData.isForever;
					data["frequence"] = this.elesformData.foreverFrequence;
				}else{
					vueGetData.creatTips("请输入监控频率");
				}
				
			}

			var reg =/^([0-1]\d|2[0-3]):[0-5]\d$/;
	        if(reg.test(this.elesformData.startTime))
	        {
	            data["start_time"] = this.elesformData.startTime;
	        }
	        else
	        {
	        	vueGetData.creatTips("您的有效监控开始时间输入错误，请重新输入");
				return false;
	        }

	        if(reg.test(this.elesformData.endTime))
	        {
	            data["end_time"] = this.elesformData.endTime;
	        }
	        else
	        {
	        	vueGetData.creatTips("您的有效监控结束时间输入错误，请重新输入");
				return false;
	        }

			let  _self = this;
			vueGetData.postData("itemmodify",data,function(jsondata){
				let error_code = jsondata.body.error_code;
				console.log(error_code)
		        if(error_code === 22000) {
		        	_self.$store.dispatch('getManagetableList',_self.dialogfetchdata)
		        	_self.hideDialog();
		        }else if(error_code == 22001){
		        	vueGetData.creatTips("系统错误请联系管理员查看问题");
		        	_self.hideDialog();
		        }else if(error_code == 22005){
		        	vueGetData.creatTips(jsondata.body.error_msg);
		        }else if(error_code == 22008){
		        	vueGetData.creatTips("操作非法");
		        	_self.hideDialog();
		        }else if(error_code == 22009){
		        	vueGetData.creatTips("没有权限，请联系管理员开通");
		        	_self.hideDialog();
		        }else if(error_code == 22452){
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
		for(let key in this.elesformData){
			this.copyElesformData[key] = this.elesformData[key]
		}

	},
	mounted: function(){
		//通信组件变量
		// this.childForHttpTable = this.httptableLists;
		// this.childHttptabledata = this.httptabledata;
	}

}
</script>
