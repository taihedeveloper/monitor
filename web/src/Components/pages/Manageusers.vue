<template>
	<div class="SideRight">
		<div class="manage-main">
			<div class="topbox">
				<div class="manageTop">
					<span class="nowrap">
						<label>请选择管理类别</label>
						<input type="radio" name="manage-type" value="user" @click="getTableList(1)">用户管理
						<input type="radio" name="manage-type" value="alert" @click="getTableList(2)">报警组管理
						<input type="radio" name="manage-type" value="certificate"@click="getTableList(3)">权限组管理
					</span>
				</div>
			</div>
			<!-- 列表部分 -->
			<div class="configList">
				<keep-alive>
					<component :is="dynamics"></component>
				</keep-alive>
			</div>
		</div>
	</div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import vueGetData from "../../Js/vueGetData.js"
import NoPermission from "../tables/NoPermission.vue"
import UserInfo from "../pages/UserInfo.vue"
import ManageAlertGroup from "../tables/ManageAlertGroup.vue"
import ManageCertiGroup from "../tables/ManageCertiGroup.vue"

	export default{
		name:'ManageUserTop',
		data(){
			return {
				dynamics: "",	//动态组件
			}
		},
		computed:mapGetters({
			userList:"userinfolist",
		}),
		methods: {
			getTableList:function(n){
				n ? n : 0;
				let datas = {
					"username":vueGetData.username(),
					"type": 4,
				};
				if(n==0){
					this.dynamics = "";
				}else if(n==1){
					this.$store.dispatch('getUserinfolist',datas);
					this.dynamics = "UserInfo";
				}else if(n==2){
					this.$store.dispatch('getAlertemanagelist',datas);
	    		    vueGetData.getData("alertmanage",datas,function(jsondata){
		            	if(jsondata.body.error_code === 22001){
	         				this.dynamics = "NoPermission";
	         				return false;
	         			}else{
	         				this.dynamics = "ManageAlertGroup";
	         			}
	         		}.bind(this),function(){

	         		}.bind(this));
				}else if(n==3){
					this.$store.dispatch('getCertificatemanagelist',datas);
					vueGetData.getData("certificatemanage",datas,function(jsondata){
		            	if(jsondata.body.error_code === 22001){
	         				this.dynamics = "NoPermission";
	         				return false;
	         			}else{
	         				this.dynamics = "ManageCertiGroup";
	         			}
	         		}.bind(this),function(){

	         		}.bind(this));
				}
			},

		},
		components: {
			NoPermission,
			ManageAlertGroup,
			ManageCertiGroup,
			UserInfo,
		}

	  }
</script>