<template>
	<div class="alarmPolicy" title="http可用性">
		<div class="button-area" style="display:block">
			<button class="add-blue-button" @click="newAddCase"><span></span>新建http可用性case</button>
			<!-- <button class="gray-button" disabled="disabled">批量导出</button> -->
		</div>
		<div class="table-div">
			<table class="manage-table">
				<thead>
					<tr>
						<th width="3%"><input type="checkbox" name="check-all" disabled="disabled"></th><th width="8%">序号</th><th width="17%">监控名称</th><th width="17%">监控url</th><th width="8%">监控级别</th><th width="10%">监控周期(m)</th><th width="15%">上次运行时间</th><th width="22%">操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(item,index) in tableHttp">
						<td><input type="checkbox" disabled="disabled"></td>
						<td>{{item.id}}</td>
						<td>{{item.item_name}}</td>
						<td>{{item.url}}</td>
						<td>{{item.level}}</td>
						<td>{{item.frequence}}</td>
						<td>{{item.last_runtime | formatDate}}</td>
						<td>
							<template v-if="item.status==1">
								<a href="javascript:;" @click="editHttp(true)" :data-id="item.id" :data-index="index">编辑</a>
								<a href="javascript:;" @click="startHttp"  :data-id="item.id" :data-index="index">启用</a>
								<!-- <a href="javascript:;" @click="deleteHttp(0,$event)" :data-id="item.id" :data-index="index">删除</a> -->
								<a href="javascript:;" @click="deleteComfirm(0,$event)" :data-id="item.id" :data-index="index">删除</a>
							</template>
							<template v-else-if="item.status==2">
								<a href="javascript:;" @click="editHttp(false)" :data-id="item.id" :data-index="index">查看</a>
								<a href="javascript:;" @click="stopHttp" :data-id="item.id" :data-index="index">停用</a>
							</template>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<DialogsAddHttp :editinitformdata="editGetFormData"></DialogsAddHttp>
	</div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import DialogsAddHttp from "../dialogs/DialogsAddHttp.vue"
import vueGetData from "../../Js/vueGetData.js"

	export default {
		name: 'ManageTop',
		data () {
			return {
				topname: '基础配置',
				manageConfigSelectedId: '0',
				editGetFormData:{}	//编辑时数据
			}
		},
		computed:mapGetters({
			productId: "productId",
			tableHttp:"httptableList",
			childHttptabledata:"managequerydata"
		}),
		methods: {
			newAddCase: function(){
				if(!this.productId || this.productId == -1){
					vueGetData.creatTips("请先选择左侧产品线");
					return false;
				}
				this.editGetFormData["oper"] = 1;
				this.showDialog();
			},
			showDialog: function(){
				let data = {"username":vueGetData.username,"type":4}
				this.$store.dispatch('getCertificatemanagelist',data);
				this.$store.dispatch('getAlertemanagelist',data);
				this.$store.dispatch('getRobotacountlist',data);
				document.getElementsByClassName("dialog")[0].style.display = "block";
			},
			deleteComfirm:function(type,event){
				let _self = this;
				let ele = event.currentTarget;
				let id = ele.getAttribute("data-id");
				let index = ele.getAttribute("data-index");
				let data = {
					"oper": 2,
					"taskid":id
				}
				let paramsJson = {};
				paramsJson["data"] = data;
				paramsJson["index"] = index;
				paramsJson["type"] = type;

	        	let str = '<div class="popCreat" id="deleteLogBox">'
		            +'<h3>确定要删除吗？</h3>'
		            +'<div class="btns"><span class="surebtn" id="surebtn">确定</span><span class="cancelbtn" id="cancelbtn">取消</span></div></div>';
				vueGetData.creatPop(str);

				let surebtn = document.getElementById("surebtn");
				let cancelbtn = document.getElementById("cancelbtn");

		        surebtn.onclick = function(){
		        	console.log(paramsJson)
					_self.$store.dispatch('deleteManagetableList',paramsJson);
					vueGetData.closePop();
		        }
		        cancelbtn.onclick = function(){
		        	vueGetData.closePop();
		        }
			},
			deleteHttp: function(type,event){
				let ele = event.currentTarget;
				let id = ele.getAttribute("data-id");
				let index = ele.getAttribute("data-index");
				let data = {
					"oper": 2,
					"taskid":id
				}
				let paramsJson = {};
				paramsJson["data"] = data;
				paramsJson["index"] = index;
				paramsJson["type"] = type;

				this.$store.dispatch('deleteManagetableList',paramsJson)
			},
			stopHttp: function(event){
				let ele = event.currentTarget;
				let id = ele.getAttribute("data-id");
				let index = ele.getAttribute("data-index");
				let data = {
					"oper": 3,
					"taskid":id
				}

				let paramsJson = {};
				paramsJson["data"] = data;
				paramsJson["index"] = index;
				paramsJson["type"] = 0;				
				this.$store.dispatch('stopManagetableList',paramsJson)
			},
			startHttp: function(event){
				let ele = event.currentTarget;
				let id = ele.getAttribute("data-id");
				let index = ele.getAttribute("data-index");
				let data = {
					"oper": 4,
					"taskid":id
				}

				let paramsJson = {};
				paramsJson["data"] = data;
				paramsJson["index"] = index;
				paramsJson["type"] = 0;				
				this.$store.dispatch('startManagetableList',paramsJson)
			},
			editHttp: function(isEdit){
				let id = event.currentTarget.getAttribute("data-id");
				let data = {"taskid":id,"username":vueGetData.username}
				this.showDialog();
		        vueGetData.getData("itemdetail",data,function(jsondata){
			        if(jsondata.body.error_code === 22000){
		        		this.editGetFormData = jsondata.body.data;
		        		this.editGetFormData["oper"] = 5;
		        		this.editGetFormData["taskid"] = id;
						if(isEdit){
							this.editGetFormData["edit"] = true;
						}else{
							this.editGetFormData["edit"] = false;
						}
						// document.getElementsByClassName("dialog")[0].style.display = "block";
		        	}	
		        }.bind(this),function(){

		        }.bind(this));
			}
		},
		mounted: function(){
			this.$store.dispatch('getManagetableList',this.childHttptabledata)
		},
		components: {
			DialogsAddHttp
		}
	}
</script>
