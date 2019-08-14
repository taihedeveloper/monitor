<template>
	<div class="SideRight">
		<div class="manage-main" v-for="(value,index) in alertDeatail">
			<div class="alarminfobox" >
				<div class="infoitembox">
					<div class="itemtitle">监控名称</div>
					<div class="itemtext">{{value.item_name}}</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">监控类别</div>
					<div class="itemtext" v-if="value.item_type==0">http可用性</div>
					<div class="itemtext" v-else-if="value.item_type==1">数据接口</div>
					<div class="itemtext" v-else-if="value.item_type==2">页面元素</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">监控URL</div>
					<div class="itemtext">{{value.item_url}}</div>
				</div>
				<!-- <div class="infoitembox">
					<div class="itemtitle">报警形式</div>
					<div class="itemtext">邮件报警</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">报警邮件接收人</div>
					<div class="itemtext">litao@taihe.com,th_musician_fe@taihe.com,th_heyinliang_tbang@taihe.com,luohongcang@taihe.com,liuyemin@taihe.com,chengbiao@taihe.com,huangshuyin@taihe.com,wujianxin@taihe.com</div>
				</div> -->
				<div class="infoitembox">
					<div class="itemtitle">报警详情</div>
					<div class="itemtext">
						<textarea readonly="readonly" :value="value.alert_detail"></textarea>
					</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">报警时间</div>
					<div class="itemtext">{{value.alert_time | formatDate}}</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">报警根因反馈</div>
					<div class="itemtext">
						<select name="reasonpselect" id="reasonpselect" v-model="reasonSelected" @change="resetSecondSelected">
							<option v-for="(value,index) in reasons" v-bind:value="index">{{value}}</option>
						</select>
						<template v-if="reasonSelected == 0">
							<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReason1.length>0">
								<option v-for="(value,index) in secondReason1" v-bind:value="index">{{value}}</option>
							</select>
						</template>
						<template v-else-if="reasonSelected == 1">
							<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReason2.length>0">
								<option v-for="(value,index) in secondReason2" v-bind:value="index">{{value}}</option>
							</select>
						</template>
						<template v-else-if="reasonSelected == 2">
							<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReason3.length>0">
								<option v-for="(value,index) in secondReason3" v-bind:value="index">{{value}}</option>
							</select>
						</template>

						<template v-else-if="reasonSelected == 3">
							<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReason4.length>0">
								<option v-for="(value,index) in secondReason4" v-bind:value="index">{{value}}</option>
							</select>
						</template>
						<template v-else-if="reasonSelected == 4">
							<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReason5.length>0">
								<option v-for="(value,index) in secondReason5" v-bind:value="index">{{value}}</option>
							</select>
						</template>
						<template v-else-if="reasonSelected == 5">
							<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReason6.length>0">
								<option v-for="(value,index) in secondReason6" v-bind:value="index">{{value}}</option>
							</select>
						</template>
						<template v-else-if="reasonSelected == 6">
							<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReason7.length>0">
								<option v-for="(value,index) in secondReason7" v-bind:value="index">{{value}}</option>
							</select>
						</template>
						<!--<select name="secondreasonpselect" id="secondreasonpselect" v-model="secondReasonSelected" v-if="secondReasons.length>0">
							<option v-for="(value,index) in secondReasons" v-bind:value="index">{{value}}</option>
						</select>-->
					</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">问题严重等级</div>
					<div class="itemtext">
						<select name="problemgrade" id="problemgrade" v-model="problemgradeSelected">
							<option v-for="(value,index) in problemgrades" v-bind:value="index">{{value}}</option>
						</select>
					</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">反馈时间</div>
					<div class="itemtext">
						<p v-if="value.feedbacktime > 0">{{value.feedbacktime | formatDate}}</p>
					</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">反馈详情</div>
					<div class="itemtext">
						<textarea class="inputtextarea" v-model="feedbackdetail"></textarea>
					</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle">实际损失描述</div>
					<div class="itemtext">
						<textarea class="inputtextarea" v-model="lossdescribe"></textarea>
					</div>
				</div>
				<div class="infoitembox">
					<div class="itemtitle"></div>
					<div class="itemtext buttons">
						<a href="javascript:;" class="dialog-blue-button" @click="save" v-show="isshowSave">保存</a>
						<router-link to="/alarm" target="_blank"  class="dialog-gray-button">取消</router-link>
					</div>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
import vueGetData from "../../Js/vueGetData.js"

	export default{
		name:'album',
		data () {
			return {
				reasonSelected:0,
				reasons:["服务不稳定","监控不稳定","新上线变更(非问题)","线上问题","线上潜在风险","数据正常波动","测试联调类"],
				secondReasonSelected:0,
				secondReasons:[],
				secondReason1:["机房服务不稳定","产品线自身服务不稳定"],
				secondReason2:["Case不稳定","产品线执行环节、框架不稳定","Monitor平台不稳定"],
				secondReason3:[],
				secondReason4:["代码问题","运维问题","外部服务问题"],
				secondReason5:[],
				secondReason6:["节假日影响","突发事件","客户操作","作弊攻击"],
				secondReason7:[],
				problemgradeSelected:0,
				problemgrades: ["严重","中等","较小"],
				alertDeatail:[],
				feedbackdetail:"",
				lossdescribe:"",
				isshowSave: true

			}
		},
		created () {
			this.fetchData();
		},
		watch:{
			'$route': 'fetchData',
			reasonSelected:function(){
				if((typeof this.reasonSelected) == "undefined" || !this.reasonSelected){
					this.reasonSelected = 0;
				}
			},
			problemgradeSelected:function(){
				if((typeof this.problemgradeSelected) == "undefined" || !this.reasonSelected){
					this.problemgradeSelected = 0;
				}				
			}
		},
		mounted:function(){
		},
		methods:{
			fetchData(){
				//获取路由id
				let alertid = this.$route.params.alertid;
				let username = vueGetData.username();
				let data = {"username":username,"alert_id":alertid}
				console.log(this.$route.params);

		        vueGetData.getData("alertrecord",data,function(jsondata){
			        if(jsondata.body.error_code === 22000){

			        	let json = jsondata.body.data;
			        	console.log(json)
			        	// this.reasonSelected = json[0].alert_reason;
			        	this.problemgradeSelected = json[0].severity_level;
			        	this.feedbackdetail = json[0].feedback_detail;
			        	this.lossdescribe = json[0].loss_describe;
			        	this.alertDeatail = json;
			        	let str = json[0].alert_reason;
			        	if(str){
			        		if(str.indexOf("/") > -1){
			        			let arr = str.split("/");
			        			let newarr = [];
			        			for(let i=0;i<this.reasons.length;i++){
			        				if(this.reasons[i] == arr[0]){
			        					this.reasonSelected = i;
			        					if(i==0){
			        						newarr = this.secondReason1;
			        						console.log(this.secondReason1);
			        					}else if(i==1){
			        						newarr = this.secondReason2;
			        						console.log(this.secondReason2);
			        					}else if(i==3){
			        						newarr = this.secondReason4;
			        						console.log(this.secondReason4);
			        					}else if(i==5){
			        						newarr = this.secondReason6;
			        						console.log(this.secondReason6);
			        					}
			        					if(newarr.length>0){
			        						console.log(arr[1])
				        					for(let j=0;j<newarr.length;j++){
				        						console.log(newarr[j])
				        						console.log(arr[1])
				        						if(newarr[j] == arr[1]){
				        							this.secondReasonSelected = j;
				        						}
				        					}
			        					}else{
			        						this.secondReasonSelected = 0;
			        					}
			        				}
			        			}
			        		}else{
			        			for(let i=0;i<this.reasons.length;i++){
			        				if(str  == this.reasons[i]){
			        					this.reasonSelected = i;
			        				}
			        			}
			        		}
			        	}
			        	console.log(this.reasonSelected);

		        	}	
		        }.bind(this),function(err){
		        	console.log(err);
		        }.bind(this));
			},
			resetSecondSelected:function(){
				this.secondReasonSelected = 0;
			},
			save(){
				let alertid = this.$route.params.alertid;
				let username = vueGetData.username();
				let data = {"username":username,"alert_id":alertid}
				data["severity_level"] = this.problemgradeSelected;
				data["feedback_detail"] = this.feedbackdetail;
				data["loss_describe"] = this.lossdescribe;

				let str = "";
				let index = this.reasonSelected;
				let num = this.secondReasonSelected;
				str = this.reasons[index];
				if(index ==0 && this.secondReason1.length>0){
					str = str + "/" + this.secondReason1[num];
				}else if(index ==1 && this.secondReason2.length>0){
					str = str + "/" + this.secondReason2[num];
				}else if(index ==3 && this.secondReason4.length>0){
					str = str + "/" + this.secondReason4[num];
				}else if(index ==5 && this.secondReason6.length>0){
					str = str + "/" + this.secondReason6[num];
				}
				data["alert_reason"] = str;
				console.log(data["alert_reason"])
				if(this.alertDeatail[0].process_status == 0 || this.alertDeatail[0].process_status == 1){ //处理中和未处理--->完成
					let json = {"username":username,"alert_id":alertid,"update":2};
			        vueGetData.getData("alertrecord",json,function(jsondata){
				        if(jsondata.body.error_code === 22000){
				        	console.log("告知后台已经更新状态为2");
			        	}
			        }.bind(this),function(err){
			        	console.log(err);
			        }.bind(this));

				}else if(this.alertDeatail[0].process_status == 2){ //
					console.log("查看")
				}

				data["feedbacktime"] = new Date().getTime()/1000;
				console.log(data)
		        vueGetData.postData("alertrecorddetail",data,function(jsondata){
			        if(jsondata.body.error_code === 22000){
						// this.$router.go("-1");
						location.href = "http://" + location.host + "/#/alarm";
		        	}
		        }.bind(this),function(err){
		        	console.log(err);
		        }.bind(this));
			}
		}
	}
</script>
<style lang="less">
.alarminfobox {
	padding: 10px 0 60px;
	.infoitembox {
		padding: 20px 10px 0 50px;
		width: 1100px;
		/*font-size: 0;*/
		line-height: 30px;
		overflow: hidden;
		.itemtitle {
			float: left;
			display: inline-block;
			font-size: 13px;
			width: 120px;
		}

		.itemtext {
			float: right;
			font-size: 13px;
			word-break: break-all;
			width: 920px;

			textarea{
				width: 100%;
				height: 150px;
				line-height: 30px;
				border: 1px solid #bbb;
			}

			select{
				width: 200px;
				background: url(/dist/Image/select-icon.png) no-repeat scroll 175px transparent;
				margin-left: 0;
			}
			input{
				margin-left: 0;
			}
			.inputtextarea{
				height: 100px;
				resize: vertical;
			}
			&.buttons {
				padding-left: 140px;
				.dialog-blue-button {
					margin-right: 20px;
				}
			}
		}
	}	
}

</style>