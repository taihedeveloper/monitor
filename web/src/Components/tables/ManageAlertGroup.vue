<template>
	<div class="alarmPolicy" title="报警组管理">
		<div class="button-area" style="display:block">
			<button class="add-blue-button" @click="addAlertGroup"><span></span>新建报警组</button>
			<button class="add-blue-button" @click="addAlertMember"><span></span>添加报警组成员</button>
		</div>
		<div class="manageTop" v-if="newAddGroup==true" v-model="newAddGroup">
			<span class="nowrap">
				<label>报警组名称</label><input type="text" class="manage-input" v-model="groupName" required="">
			</span>
			<a href="javascript:;" class="white-button" @click="saveAddGroup">保存</a>
			<a href="javascript:;" class="white-button" @click="cancelAddGroup">取消</a>
		</div>
		<div class="manageTop" v-if="newAddMember==true" v-model="newAddMember">
			<span class="nowrap">
				<label>成员名称</label><input type="text" class="manage-input" v-model="memberName" required="">
				<select name="" class="long-select" v-model="alarmSelected">
					<option v-for="(value,index) in alarmList" :value="index">{{value.class_name}}</option>
				</select>
			</span>
			<a href="javascript:;" class="white-button" @click="saveAddMember">保存</a>
			<a href="javascript:;" class="white-button" @click="cancelAddMember">取消</a>
		</div>
		<div class="button-area" style="display:block"></div>
		<div class="table-div">
			<table class="manage-table">
				<thead>
					<tr>
						<th width="10%"><input type="checkbox" name="check-all" disabled="disabled"></th>
						<th width="20%">ID</th>
						<th width="30%">报警组名称</th>
						<!--<th width="50%">报警组成员</th>-->
						<th width="40%">操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(value,index) in alarmList">
						<td><input type="checkbox" disabled="disabled"></td>
						<td>{{index}}</td>
						<td>{{value.class_name}}</td>
						<!--
						<td class="showmember" :id=value.class_id>
								<a href="javascript:;" :data-id="value.class_id" @click="seeMembers(value.class_id)">查看</a>
						</td>-->
						<td><a href="javascript:;" @click="editAlertGroup(value.class_name)" :data-id="value.class_id">编辑</a><a href="javascript:;" :data-id="value.class_id" @click="deleteComfirm($event)">删除</a></td>
					</tr>
				</tbody>
			</table>
		</div>
		<DialogsAlertGroup :editinitformdata="editGetFormData"></DialogsAlertGroup>
	</div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import vueGetData from "../../Js/vueGetData.js"
import DialogsAlertGroup from "../dialogs/DialogsAlertGroup.vue"

	export default{
		name:'ManageAlertGroup',
		data(){
			return {
				editGetFormData:{},	//编辑时数据
				newAddGroup:false,//是否新建报警组
				groupName:"",
				newAddMember:false,//是否添加报警组成员
				memberName:"",
				alarmSelected:0,//返回所选class_id
			}
		},
		computed:mapGetters({
			alarmList:"alertmanagelist",
			alertclassid:"alertclassid",
		}),
		watch:{
			alertclassid: function() {
				let id = this.alertclassid;
				for(let i=0;i<this.alarmList.length;i++){
					let json = this.alarmList[i];
					if(id == json.class_id){
						this.alarmSelected = i;
						return false;
					}
				}
			},
		},
		methods:{
			/*
			seeMembers:function(index){
				let data = {"type":4,"username":vueGetData.username,"class_id":index}
		        vueGetData.getData("alertmember",data,function(jsondata){
		        	console.log(jsondata);
			        if(jsondata.body.error_code === 22000){
		        		let memberlist = jsondata.body.data;

		        		var str="";
						for(var i=0;i<memberlist.length;i++){
							str=str+memberlist[i].username+", ";
						}
						document.getElementById(index).innerHTML = str.substring(0,str.length-2);
		        	}else{
						document.getElementById(index).innerHTML = "";
					}
		        }.bind(this),function(){

		        }.bind(this));
			},
			*/
			addAlertGroup: function(){
				this.newAddGroup=true;
				this.newAddMember=false;
			},
			saveAddGroup:function(){
				let data={};
				data={"username":vueGetData.username,"type":1};
				if(this.groupName===''){
					vueGetData.creatTips("请填写报警组名");
				}else{
					this.newAddGroup=false;
					data["class_name"]=vueGetData.trim(this.groupName);
					vueGetData.getData("alertmanage",data,function(jsondata){
						console.log(jsondata);
			        	if(jsondata.body.error_code === 22000){
			        		vueGetData.creatTips("报警组添加成功");
			        		this.$store.dispatch('getAlertemanagelist',{"username":vueGetData.username,"type":4});
			        	}else{
			        		vueGetData.creatTips("无操作权限 添加失败");
				        	console.log(jsondata.body.error_code)
				        }
				        this.groupName="";
			        }.bind(this),function(){

			        }.bind(this));
				}
			},
			cancelAddGroup:function(){
				this.newAddGroup=false;
			},

			addAlertMember:function(){
				this.newAddGroup=false;
				this.newAddMember=true;
			},

			saveAddMember:function(){
				let data={};
				data={"username":vueGetData.username,"type":1};
				data["class_id"]=this.alarmList[this.alarmSelected].class_id;
				if(this.memberName===''){
					vueGetData.creatTips("请填写用户名");
				}else{
					this.newAddMember=false;
					data["member_name"]=vueGetData.trim(this.memberName);
					console.log(data);
					vueGetData.getData("alertmember",data,function(jsondata){
						console.log(jsondata);
			        	if(jsondata.body.error_code === 22000){
			        		vueGetData.creatTips("添加成功");
			        	}else if(jsondata.body.error_code === 22005){
			        		vueGetData.creatTips("添加失败 该成员不是用户，请添加用户");
				        	console.log(jsondata.body.error_code);
				        }else{
			        		vueGetData.creatTips("无操作权限 添加失败");
				        	console.log(jsondata.body.error_code);
				        }
				        this.memberName="";
			        	this.alarmSelected=0;
			        }.bind(this),function(){

			        }.bind(this));
				}
			},
			cancelAddMember:function(){
				this.newAddMember=false;
			},

			showDialog: function(){
				document.getElementsByClassName("dialog")[0].style.display = "block";
			},

			editAlertGroup: function(name){
				let id = event.target.getAttribute("data-id");
				let data = {"username":vueGetData.username,"type":4,"class_id":id,"class_name":name,"isNew":true};
				this.$store.dispatch('getAlertmemberlist',data);
				this.showDialog();
			},

			deleteComfirm:function(event){
				let _self = this;
				let id = event.currentTarget.getAttribute("data-id");
				let data = {"username":vueGetData.username,"type":2,"class_id":id}
				let str = '<div class="popCreat" id="deleteLogBox">'
		            +'<h3>确定要删除吗？</h3>'
		            +'<div class="btns"><span class="surebtn" id="surebtn">确定</span><span class="cancelbtn" id="cancelbtn">取消</span></div></div>';
				vueGetData.creatPop(str);

				let surebtn = document.getElementById("surebtn");
				let cancelbtn = document.getElementById("cancelbtn");

				surebtn.onclick = function(){

					vueGetData.closePop();
					vueGetData.getData("alertmanage",data,function(jsondata){
						console.log(jsondata);
			        	if(jsondata.body.error_code === 22000){
			        		vueGetData.creatTips("删除成功");
			        		_self.$store.dispatch('getAlertemanagelist',{"username":vueGetData.username,"type":4});
			        	}else{
			        		vueGetData.creatTips("删除失败");
				        	console.log(jsondata.body.error_code);
				        }
			        }.bind(this),function(){

			        }.bind(this));
		        }
		        cancelbtn.onclick = function(){
		        	vueGetData.closePop();
		        }
			},

		},
		components:{
			DialogsAlertGroup
		}
	  }
</script>
<style>
.showmember{
	line-height: 25px;
	padding: 10px;
	overflow: hidden;
	vertical-align: middle;
	word-break: break-all;
	white-space: normal;
}
.requiredItem:before{
		content:"*";
		position: absolute;
		right: 0;
		top: 2px;
		color: #f00;
		font-size: 14px;
	}
</style>