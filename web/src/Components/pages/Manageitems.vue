<template>
	<div class="SideRight">
		<SideLeft></SideLeft>
		<div class="manage-main">
			<div class="topbox">
				<div class="manageTop">
					<span class="nowrap">
						<label for="monitor-type">监控类型</label>
						<select id="monitor-type" class="manage-select" v-model="formInitData.monitorTypeSelectedID" @change="getTableList(formInitData.monitorTypeSelectedID)">
							<option v-for="(option,index) in formInitData.monitorType" :value="index">{{option}}</option>
						</select>
					</span>
				</div>
				<div class="searchDetail">
					<!-- 报警策略管理 -->
					<div class="manageDetail">
						<span class="nowrap">
							<label for="item-name">策略名称</label><input type="text" id="item-name" class="manage-input"  v-model="formInitData.itemname" required="">
						</span>
						<span class="nowrap input-idbox">
							<label for="item-id">策略项ID</label><input type="number" id="item-id" class="manage-input "  v-model="formInitData.itemid">
						</span>
						<span class="nowrap">
							<label for="monitor-status">监控项状态</label>
							<select id="monitor-status" class="manage-select" v-model="formInitData.statusSelect">
								<option value="" v-for="value in formInitData.statusList" v-bind:value="value">{{value}}</option>
							</select>
						</span>
						<span class="nowrap">
							<label for="monitor-level">监控级别</label>
							<select id="monitor-level" class="manage-select" v-model="formInitData.levelSelect">
								<option value="" v-for="value in formInitData.levelList" v-bind:value="value">{{value}}</option>
							</select>
						</span>
						<a href="javascript:;" class="white-button" @click="getdatas">查询</a>
					</div>
				</div>
				<!-- 列表部分 -->
				<div class="configList">
					<keep-alive>
						<component :is="dynamics" :httptabledata.sync="datas"></component>
					</keep-alive>
				</div>
				<TurnPage :allpage="allpage" :tableTotal="tableTotal" :type="formInitData.monitorTypeSelectedID"></TurnPage>
			</div>
		</div>
	</div>

</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import vueGetData from "../../Js/vueGetData.js"
import MonitorTableHttp from "../tables/MonitorTableHttp.vue"
import ManageTableJsconf from "../tables/ManageTableJsconf.vue"
import ManageTableEles from "../tables/ManageTableEles.vue"
import TurnPage from "../TurnPage.vue"
import SideLeft from '../SideLeft.vue'

	export default {
		name: 'ManageTop',
		data () {
			return {
				formInitData:{
					monitorTypeSelectedID:0,
					monitorType:["http可用性","数据接口","页面元素"],
					statusList: ["全部","未启用","已启用"],
					levelList: ["全部","0","1","2"],
					settingList: ["全部","线上环境","线下环境"],
					itemname: "",
					itemid: "",
					statusSelect: "全部",
					levelSelect:"全部",
					settingSelect: 0

				},
				dynamics: "MonitorTableHttp",	//动态组件
				tableDataPort: {},
				tablePageElement: {},
				datas: {},
				pages: {
					pageTotal: 116,
					start: 0
				}
			}
		},
		methods: {
			getdatas: function(){
				this.datas = {};
				this.datas = {"username":vueGetData.username()};
				this.datas["type"] = this.formInitData.monitorTypeSelectedID;
				this.formInitData.itemname = vueGetData.trim(this.formInitData.itemname);
				this.formInitData.itemid = vueGetData.trim(this.formInitData.itemid);
				if(this.formInitData.itemname){
					this.datas["item_name"] = this.formInitData.itemname;
				}
				if(this.productId && (this.productId != -1)){
					this.datas["product_line"] = this.productId;
				}
				if(this.formInitData.itemid > 0){
					this.datas["taskid"] = this.formInitData.itemid;
				}
				if(this.formInitData.levelSelect != "全部"){
					this.datas["level"] = this.formInitData.levelSelect;
				}
				if(this.formInitData.statusSelect == "未启用"){
					this.datas["status"] = 1;
				}else if(this.formInitData.statusSelect == "已启用"){
					this.datas["status"] = 2;
				}
				this.datas["start"] =  this.pages.start;
				this.datas["limit"] = vueGetData.getDataLimit();

				this.$store.dispatch('getManagequerydata',this.datas)
				this.$store.dispatch('getManagetableList',this.datas)

			},
			getTableList:function(n) {
				n ? n : 0;
				this.getdatas();
				if(n==0){
					this.dynamics = "MonitorTableHttp";
				}else if(n==1){
					this.dynamics = "ManageTableJsconf";
				}else if(n==2){
					this.dynamics = "ManageTableEles";
				}
			},
          	turnPages: function(){
                let pag = [];
                if( this.current < this.showItem ){ //如果当前的激活的项 小于要显示的条数
                       //总页数和要显示的条数那个大就显示多少条
                       var i = Math.min(this.showItem,this.allpage);
                       while(i){
                           pag.unshift(i--);
                       }
                   }else{ //当前页数大于显示页数了
                       var middle = this.current - Math.floor(this.showItem / 2 ),//从哪里开始
                           i = this.showItem;
                       if( middle >  (this.allpage - this.showItem)  ){
                           middle = (this.allpage - this.showItem) + 1
                       }
                       while(i--){
                           pag.push( middle++ );
                       }
                   }
                return pag
            },
            goto:function(index){
	          if(index == this.current) return;
	            this.current = index;
	            //这里可以发送ajax请求
	        }
		},
		created(){
			console.log(this.$route.params.itemname)
			if(this.$route.params.itemname && this.$route.params.itemname != "undefined"){
				let routeparams = this.$route.params.itemname;
				let arr = routeparams.split("&");
				for(let i=0;i<arr.length;i++){
					let arr2 = arr[i].split("=");
					if(arr2[0] == "itemname"){
						this.formInitData.itemname = arr2[1];
					}
					if(arr2[0] == "type"){
						let type = arr2[1];
						this.formInitData.monitorTypeSelectedID = arr2[1];
						if(type==0){
							this.dynamics = "MonitorTableHttp";
						}else if(type==1){
							this.dynamics = "ManageTableJsconf";
						}else if(type==2){
							this.dynamics = "ManageTableEles";
						}
					}
				}
			}
			this.getdatas();
		},
		computed:mapGetters({
			productId:"productId",
			allpage:"allpage",
			tableTotal:"tableTotal"
		}),
		mounted:function(){
			// this.getdatas();
		},
		components: {
			MonitorTableHttp,
			ManageTableJsconf,
			ManageTableEles,
			SideLeft,
			TurnPage
		}
	}
</script>
<style lang="less" scoped>
@import "../../Css/rightside.less";
.SideRight {
	padding-left: 200px;
}
</style>
