<template>
	<div class="SideRight">
		<div class="manage-main">
			<div class="topbox">
				<div class="searchDetail">
					<span class="nowrap">
						<label for="runlogitem-name">监控名称</label>
						<input type="text" id="runlogitem-name" class="manage-input"  v-model="runlogFormInitdata.manageeName" required="">
					</span>
					<span class="nowrap">
						<label for="runlogmonitor-types">监控类型</label>
						<select id="runlogmonitor-types" class="manage-select" v-model="runlogFormInitdata.manageTypesSelect">
							<option v-for="(value,index) in runlogManageTypes" :value="index-1">{{value}}</option>
						</select>
					</span>
					<span class="nowrap">
						<label for="runlogmanage-status">监控状态</label>
						<select id="runlogmanage-status" class="manage-select" v-model="runlogFormInitdata.manageStatusSelect">
							<option v-for="(value,index) in runlogMmanageStatus" :value="index-1">{{value}}</option>
						</select>
					</span>
					<span class="nowrap">
						<label for="runlogitem-time">报警时间</label>
						<input type="text" id="runlogitem-time" class="manage-input" v-model="alarmTimes" @blur="getTime">
						<!-- <input type="text" id="runlogitem-time" class="manage-input" style="width: 30%;"> -->
					</span>
					<a href="javascript:;" class="white-button" @click="queryLogList">筛选</a>
				</div>
				<!-- 列表部分 -->
				<div class="configList">
					<RunlogTable :runloglist="runlogTable"  :runlogquerydatas.sync="querydatas"></RunlogTable>
				</div>
				<TurnPage :type="runlogtableType" :allpage="allpage" :tableTotal="tableTotal"></TurnPage>
			</div>
		</div>
	</div>

</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import Kalendae from "../../Js/plugins/kalendae/js/kalendae.standalone.js"
import vueGetData from "../../Js/vueGetData.js"
import RunlogTable from "../tables/RunlogTable.vue"
import TurnPage from "../TurnPage.vue"

export default {
		name: 'RunLog',
		data(){
			return {
				runlogFormInitdata:{
					manageeName:"",
					// manageTypesSelect: "http可用性",
					// manageStatusSelect: "全部状态"
					manageTypesSelect: -1,
					manageStatusSelect: -1
				},
				alarmTimes: "",
				runlogManageTypes:["请选择监控类别","http可用性","数据接口","页面元素"],
				runlogMmanageStatus:["全部状态","pass","fali","timeout"],
				runlogTable:[],
				querydatas:{},
				runlogtableType:"runlogtable",
				pages: {
					pageTotal: "100",
					start: 0,
					limit: 20
				}
			}
		},
		computed:mapGetters({
			productId:"productId",
			allpage:"runlogAllpage",
			tableTotal:"runlogTableTotal"
		}),
		components:{
			RunlogTable,
			TurnPage
		},
		methods: {
			getTime:function(event){
				this.alarmTimes = event.currentTarget.value;
			},
			getTableList:function() {
				let data = {}
				data["username"] = vueGetData.username();
				data["start"] = this.pages.start;
				data["limit"] = vueGetData.getDataLimit();

				console.log(this.alarmTimes)
				let timejson = vueGetData.getTimestamp(this.alarmTimes);
				data["start_time"] = timejson.start;
				data["end_time"] = timejson.end;
				
				if(this.runlogFormInitdata.manageStatusSelect > -1){
					data["run_status"] = this.runlogFormInitdata.manageStatusSelect;
				}

				if(this.runlogFormInitdata.manageTypesSelect > -1){
					data["item_type"] = this.runlogFormInitdata.manageTypesSelect;
				}

				this.runlogFormInitdata.manageeName = vueGetData.trim(this.runlogFormInitdata.manageeName);
				if(this.runlogFormInitdata.manageeName){
					data["item_name"] = this.runlogFormInitdata.manageeName
				}
				this.querydatas = data;
				this.$store.dispatch('getRunlogquerydata',data)
			},
			queryLogList:function(){
				this.getTableList();
				this.$store.dispatch('getRunlogtableList',this.querydatas)
			}
		},
		created(){
			if(!this.alarmTimes){
				let timejson = vueGetData.getTimestamp();
				this.alarmTimes = timejson.orther.s + " - " + timejson.orther.e;
			}
			this.queryLogList();
		},
		mounted(){
			let k4 = new Kalendae.Input('runlogitem-time', {
		        months:2,
		        mode:'range',
		        format:"YYYY-MM-DD",
		        direction:"today-past"
		    });
		}
}
</script>