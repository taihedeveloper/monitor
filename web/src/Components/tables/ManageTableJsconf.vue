<template>
	<div class="alarmPolicy" title="数据接口">
		<div class="button-area">
			<button class="add-blue-button" @click="newAddCase"><span></span>新建数据接口case</button>
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
								<a href="javascript:;" @click="deleteComfirm(1,$event)" :data-id="item.id" :data-index="index">删除</a>
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
		<DialogsAddDataport :editinitformdata="editGetFormData"></DialogsAddDataport>
	</div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import DialogsAddDataport from "../dialogs/DialogsAddDataport.vue"
import vueGetData from "../../Js/vueGetData.js"

	export default {
		name: 'ManageTop',
		data () {
			return {
				topname: '基础配置',
				manageConfigSelectedId: '1',
				editGetFormData:{}	//编辑时数据
			}
		},
		// props: ["httptabledata"],
		computed:mapGetters({
			productId: "productId",
			tableHttp:"jsconftableList",
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
					_self.$store.dispatch('deleteManagetableList',paramsJson)
					vueGetData.closePop();
		        }
		        cancelbtn.onclick = function(){
		        	vueGetData.closePop();
		        }
			},
			deleteHttp: function(type,event){
				let ele = event.target;
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
				// alert("确定禁用？");
				let ele = event.target;
				let id = ele.getAttribute("data-id");
				let index = ele.getAttribute("data-index");
				let data = {
					"oper": 3,
					"taskid":id
				}

				let paramsJson = {};
				paramsJson["data"] = data;
				paramsJson["index"] = index;
				paramsJson["type"] = 1;				
				this.$store.dispatch('stopManagetableList',paramsJson)
			},
			startHttp: function(event){
				// alert("确定启用？");
				let ele = event.target;
				let id = ele.getAttribute("data-id");
				let index = ele.getAttribute("data-index");
				let data = {
					"oper": 4,
					"taskid":id
				}

				let paramsJson = {};
				paramsJson["data"] = data;
				paramsJson["index"] = index;
				paramsJson["type"] = 1;				
				this.$store.dispatch('startManagetableList',paramsJson)
			},
			editHttp: function(isEdit){
				let id = event.target.getAttribute("data-id");
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
			DialogsAddDataport
		}
	}
</script>
