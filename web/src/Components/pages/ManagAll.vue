<template>
	<div class="SideRight">
		<div class="manage-main">
			<div class="topbox">
				<div class="manageTop">
					我的图表部分----稍后完成
				</div>
				<!-- 列表部分 -->
				<div class="configList">
					我是列表部分------稍后完成
				</div>
				<div class="pageDiv">
					<ul class="pagination">
						<li class="pre disabled"><a href="javascript:;">&lt;</a></li>
						<li class="num active"><a href="javascript:;">1</a></li>
						<li class="num"><a href="javascript:;">2</a></li>
						<li class="next"><a href="javascript:;">&gt;</a></li>
					</ul>
					<span class="page-total">共2页</span>
					<span class="num-total">共13条</span>
				</div>
			</div>
			<!-- <Dialogs></Dialogs> -->
		</div>
	</div>


</template>
<script>
import vueGetData from "../../Js/vueGetData.js"
// import Dialogs from '../Dialogs.vue'
import MonitorTableHttp from "../tables/MonitorTableHttp.vue"
import ManageTableJsconf from "../tables/ManageTableJsconf.vue"

	export default {
		name: 'ManageTop',
		data () {
			return {
				topname: '监控项管理',
				monitorType:["http可用性","数据接口","页面元素"],
				monitorTypeSelectedID:0,
				dynamics: "MonitorTableHttp",	//动态组件
				pageTotal: "100",
				statusList: ["全部","未启用","已启用"],
				levelList: ["全部","0","1","2"],
				tableHttp: {},
				tableDataPort: {},
				tablePageElement: {},
				settingList: ["全部","线上环境","线下环境"]
			}
		},
		methods: {
			resetArr: function(arr,resetName){
				for(var i=0;i<arr.length;i++){
					arr[i] = resetName;
				}
				return arr;
			},
			searchDetail:function(n) {
				n ? n : 0;
				let data = {"username":vueGetData.username(),"product_line":"","type":n,"limit":vueGetData.getDataLimit()};
				console.log(data)
				if(n==0){
					this.dynamics = "MonitorTableHttp";
					data={"username":"liuyemin","type":0,"level":0,"item_name":"监控项test","id":2,"status":1,"start":0,"limit":10}
			        vueGetData.getData("itemshow",data,function(jsondata){
				        if(jsondata.body.error_code === 22000){
			        		this.tableHttp = jsondata.body.data;
			        	}	
			        }.bind(this),function(){

			        }.bind(this))

				}else if(n==1){
					this.dynamics = "ManageTableJsconf";
				}
			}
		},
		mounted(){
			console.log(vueGetData.getContHeight(68))
		},
		components: {
			// Dialogs,
			MonitorTableHttp,
			ManageTableJsconf
		}
	}
</script>
<style lang="less">
@import "../../Css/rightside.less";
</style>
