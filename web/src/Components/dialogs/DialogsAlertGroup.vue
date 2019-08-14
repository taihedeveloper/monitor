<template>
	<div class="dialog">
		<div class="dialogOverlay"></div>
		<div class="dialogContent">
			<div class="dialogClose" @click="hideDialog"></div>
			<div class="dialogDetail">
				<div class="dialogTitle">{{dialogTitle}}</div>
				<div class="dialogDiv">
					<div class="dialogSubDiv">
						<div class="subDivTitle requiredItem">报警组名称</div>
						<div class="subDivContent"><input type="text" class="dataport-dialog-name" placeholder="" v-model=className><button class="blue-button" style="margin:5px" @click="rename">重命名</button></div>
					</div>
					<div class="dialogSubDiv">
						<div class="subDivTitle">报警组成员</div>
						<div style="display:inline-block;width:600px;">
							<template v-for="(item,i) in usersselects">
								<div class="subDivContent" style="margin-bottom:10px;">
									<div class="subDivCard">
										<div class="box">
											<label class="name">成员名(非空)</label>
											<input type="text" class="dataport-dialog-rulename" placeholder="" v-model="item.username">
										</div>
										<div class="box">
											<label class="name">条件操作</label>
											<div class="subbox">
												<button class="blue-button" v-show="usersselects.length >0" @click="deleteUser(i)">删除</button>
											</div>
										</div>
									</div>
								</div>
							</template>
						</div>
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
	name: "Dialogalertgroup",
	data () {
		return {
			dialogTitle: '编辑报警组',
			className: '',
			classId:'',
			usersselects:[],
		}
	},
	computed:mapGetters({
		AlertMemberlist:"alertmemberlist",
	}),
	// 获取不到数据，不知道什么原因
	// props:["editinitformdata"],
	watch: {
		AlertMemberlist:function(){
			this.className=this.AlertMemberlist.class_name;
			this.classId=this.AlertMemberlist.class_id;
			if(this.AlertMemberlist.data){
				this.usersselects=[];
				for(var i=0;i<this.AlertMemberlist.data.length;i++){
					let data={username:this.AlertMemberlist.data[i].username};
					this.usersselects.push(data);
				}
			}
		}
	},
	methods: {
		hideDialog: function(){
			//重置初始化数据
			this.className = '';
			this.classId = '';
			this.usersselects=[];
			document.getElementsByClassName("dialog")[0].style.display = "none";
		},

		rename:function(){
			let data={};
			data={"username":vueGetData.username,"type":3,"class_id":this.classId};
			if(this.className===''){
				vueGetData.creatTips("请填写报警组名");
			}else{
				data["class_name"]=vueGetData.trim(this.className);

				vueGetData.getData("alertmanage",data,function(jsondata){
					console.log(jsondata);
		        	if(jsondata.body.error_code === 22000){
		        		vueGetData.creatTips("修改报警组名成功");
		        		this.$store.dispatch('getAlertemanagelist',{"username":vueGetData.username,"type":4});
		        	}else{
		        		vueGetData.creatTips("修改报警组名失败");
			        	console.log(jsondata.body.error_code);
			        }
		        }.bind(this),function(){

		        }.bind(this));
			}
		},
		deleteUser:function(index){
			let data={};
			data={"username":vueGetData.username,"type":2,"class_id":this.classId};
			console.log(this.AlertMemberlist);
			data["member_name"]=vueGetData.trim(this.AlertMemberlist.data[index].username);

			vueGetData.getData("alertmember",data,function(jsondata){
				console.log(jsondata);
	        	if(jsondata.body.error_code === 22000){
	        		vueGetData.creatTips("删除成功");
	        		console.log(this.usersselects);
	        		this.usersselects.splice(index,1);
	        		for(var i=this.usersselects.length-1;i>=0;i--){
	        			if(this.usersselects[i].username===data["member_name"]){
	        				this.usersselects.splice(i,1);
	        			}
	        		}
	        	}else{
	        		vueGetData.creatTips("删除失败");
		        	console.log(jsondata.body.error_code);
		        }
	        }.bind(this),function(){

	        }.bind(this));
		},

	},
}
</script>
