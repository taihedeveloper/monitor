<template>
	<div class="SideRight">
		<div class="manage-main">
			<div class="topbox">
				<div class="searchDetail">
					<span class="nowrap">
						<label for="alarm-types">监控类别</label>
						<select id="alarm-types" class="manage-select" v-model="alarmFormInitdata.alarmTypesSelect">
						<!--
						<select id="alarm-types" class="manage-select" v-model="alarmFormInitdata.alarmTypesSelect" @change="getTableList(alarmFormInitdata.alarmTypesSelect)">
						-->
							<option v-for="(value,index) in alarmTypes" :value="index-1">{{value}}</option>
						</select>
					</span>
					<span class="nowrap">
						<label for="alarm-level">监控级别</label>
						<select id="alarm-level" class="manage-select" v-model="alarmFormInitdata.alarmLevelSelect">
							<option v-for="(value,index) in alarmLeves" :value="index-1">{{value}}</option>
						</select>
					</span>
					<br>
					<span class="nowrap">
						<label for="alarm-name">监控名称</label>
						<input type="text" id="alarm-name" class="manage-input"  v-model="alarmFormInitdata.alarmName">
					</span>
					<span class="nowrap">
						<label for="alarm-id">报警序号</label>
						<input type="text" id="alarm-id" class="manage-input"  v-model="alarmFormInitdata.alarmId">
					</span>
					<span class="nowrap">
						<label for="alarm-process-status">处理状态</label>
						<select id="alarm-process-status" class="manage-select red-input" v-model="alarmFormInitdata.processStatusSelect">
							<option v-for="(value,index) in processStatus" :value="index-1">{{value}}</option>
						</select>
					</span>
					<span class="nowrap">
						<label for="alarm-time">报警时间</label>
						<input type="text" id="alarm-time" class="manage-input"  v-model="alarmTimes" @blur="getTime">
					</span>
					<a href="javascript:;" class="white-button" @click="queryList">查询</a>
				</div>
				<!-- 列表部分 -->
				<div class="configList">
					<AlarmTable :alarmquerydatas.sync="querydatas"></AlarmTable>
				</div>
				<TurnPage :type="alarmtable" :allpage="allpage" :tableTotal="tableTotal"></TurnPage>
			</div>
		</div>
	</div>

</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import Kalendae from "../../Js/plugins/kalendae/js/kalendae.standalone.js"
import vueGetData from "../../Js/vueGetData.js"
import AlarmTable from "../tables/AlarmTable.vue"
import TurnPage from "../TurnPage.vue"

export default {
		name: 'Alarm',
		data(){
			return {
				alarmFormInitdata:{
					alarmTypesSelect: "-1",
					alarmLevelSelect: "-1",
					alarmName:"",
					alarmId:"",
					processStatusSelect:"-1"
				},
				alarmTimes:"",
				alarmTypes:["请选择监控类别","http可用性","数据接口","页面元素"],
				alarmLeves:["全部","0级","1级","2级"],
				processStatus:["全部","未处理","处理中","已处理"],
				alarmTable:[],
				querydatas:{},
				alarmtable:"alarmtable",
				pages: {
					pageTotal: "100",
					start: 0,
					limit: 20
				}
			}
		},
		computed:mapGetters({
			productId:"productId",
			allpage:"alarAllpage",
			tableTotal:"alarTableTotal"
		}),
		components:{
			AlarmTable,
			TurnPage
		},
		methods: {
			getTime:function(event){
				this.alarmTimes = event.currentTarget.value;
			},
			/*
			getTableList: function(n){
				n ? n : -1;
				let datas = {
					"username":vueGetData.username()
				};
				if(n!=-1){
					datas["item_type"]=n;
					this.$store.dispatch('getAlarmquerydata',datas)
					this.$store.dispatch('getAlarmtableList',datas)
				}
			},
			*/
			getquerydatas: function(){
				this.alarmFormInitdata.alarmName = vueGetData.trim(this.alarmFormInitdata.alarmName);
				this.alarmFormInitdata.alarmId = vueGetData.trim(this.alarmFormInitdata.alarmId);
				let data = {};
				data["username"] = vueGetData.username("perdonalid");
				data["start"] = this.pages.start;
				data["limit"] = vueGetData.getDataLimit();

				let timejson = vueGetData.getTimestamp(this.alarmTimes);
				data["start_time"] = timejson.start;
				data["end_time"] = timejson.end;

				if(this.alarmFormInitdata.alarmId){
					data["alert_id"] =  this.alarmFormInitdata.alarmId;
				}
				if(this.alarmFormInitdata.alarmName){
					data["item_name"] = this.alarmFormInitdata.alarmName;
				}
				if(this.alarmFormInitdata.alarmLevelSelect > -1){
					data["item_level"] = this.alarmFormInitdata.alarmLevelSelect;
				}
				if(this.alarmFormInitdata.alarmTypesSelect > -1){
					data["item_type"] = this.alarmFormInitdata.alarmTypesSelect;
				}
				if(this.alarmFormInitdata.processStatusSelect > -1){
					data["process_status"] = this.alarmFormInitdata.processStatusSelect;
				}
				console.log(data)
				this.querydatas = data;
				this.$store.dispatch('getAlarmquerydata',data)
			},
			queryList:function(){
				this.getquerydatas();
				this.$store.dispatch('getAlarmtableList',this.querydatas)
			}
		},
		created(){
			console.log(this.$route.params.alertid)
			if(!this.alarmTimes){
				let timejson = vueGetData.getTimestamp();
				this.alarmTimes = timejson.orther.s + " - " + timejson.orther.e;
			}
			if(this.$route.params.alertid && this.$route.params.alertid != "undefined"){
				this.alarmFormInitdata.alarmId = this.$route.params.alertid;
			}
			this.queryList();
		},		
		mounted(){
			let k5 = new Kalendae.Input('alarm-time', {
		        months:2,
		        mode:'range',
		        format:"YYYY-MM-DD",
		        direction:"today-past"
		    });
		}
}
</script>