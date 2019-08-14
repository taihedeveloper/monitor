<template>
	<div class="alarminfobox" >
		<div class="infoitembox">
			<div class="itemtitle">添加用户</div>
			<div style="display:inline-block;width:800px;">
				<div class="itemsubtitle requiredItem">用户名</div>
				<input type="text" class="dataport-dialog-name "  v-model="addData.membername" placeholder="">
				<div class="itemsubtitle  requiredItem"> 电话</div>
				<input type="text" class="dataport-dialog-name"  v-model="addData.telephone" placeholder="">
				<div class="itemsubtitle  requiredItem"> 邮箱</div>
				<input type="text" class="dataport-dialog-name"  v-model="addData.email" placeholder="">
				<button @click="addUser">添加</button>
			</div>
		</div>
		<div class="infoitembox">
			<div class="itemtitle">修改用户</div>
			<div style="display:inline-block;width:800px;">
				<div class="itemsubtitle  requiredItem">用户名</div>
				<input type="text" class="dataport-dialog-name"  v-model="modifyData.membername" placeholder="">
				<div class="itemsubtitle">电话</div>
				<input type="text" class="dataport-dialog-name"  v-model="modifyData.telephone" placeholder="">
				<div class="itemsubtitle">邮箱</div>
				<input type="text" class="dataport-dialog-name"  v-model="modifyData.email" placeholder="">
				<button @click="modifyUser">修改</button>
			</div>
		</div>
		<div class="infoitembox">
			<div class="itemtitle">查询用户</div>
			<div style="display:inline-block;width:800px;">
				<div class="itemsubtitle requiredItem">用户名</div>
				<input type="text" class="dataport-dialog-name" v-model="searchData.membername" placeholder="">
				<button @click="searchUser">查询</button>
			</div>
		</div>

		<div class="infoitembox"></div>
		<!--表格数据-->
		<div class="table-div">
			<table class="manage-table">
				<thead>
					<tr>
						<th width="5%"><input type="checkbox" name="check-all" disabled="disabled"></th>
						<th width="10%">ID</th>
						<th width="20%">用户名</th>
						<th width="20%">电话</th>
						<th width="20%">邮箱</th>
						<th width="25%">操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(value,index) in userList">
						<td><input type="checkbox" disabled="disabled"></td>
						<td>{{value.id}}</td>
						<td>{{value.username}}</td>
						<td>{{value.tel_no}}</td>
						<td>{{value.email}}</td>
						<td><a href="javascript:;" @click="deleteUser(value.username,$event)">删除</a></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import vueGetData from "../../Js/vueGetData.js"

	export default{
		name:'userinfo',
		data () {
			return {
				addData:{
					membername:'',
					telephone:'',
					email:'',
				},
				modifyData:{
					membername:'',
					telephone:'',
					email:'',
				},
				searchData:{
					membername:'',
				},
				deleteData:{
					membername:'',
				},
			}
		},
		computed:mapGetters({
			userList:"userinfolist",
		}),
		methods:{
			addUser:function(){
				let data={};
				data={"username":vueGetData.username};
				data["type"]=1;
				console.log(this.addData.membername);
				if(this.addData.membername===''){
					vueGetData.creatTips("请填写用户名");
				}else{
					data["member_name"]=vueGetData.trim(this.addData.membername);
					if(this.addData.telephone===''){
					vueGetData.creatTips("请填写用户电话");
					}else{
						data["tel_no"]=vueGetData.trim(this.addData.telephone);
						if(this.addData.email===''){
							vueGetData.creatTips("请填写用户邮箱");
						}else{
							data["email"]=vueGetData.trim(this.addData.email);
							vueGetData.getData("userinfo",data,function(jsondata){
								console.log(jsondata);
					        	if(jsondata.body.error_code === 22000){
					        		vueGetData.creatTips("添加用户成功");
					        		this.$store.dispatch('getUserinfolist',{"username":vueGetData.username,"type":4})
					        	}else{
					        		vueGetData.creatTips("无操作权限，添加失败");
						        	console.log(jsondata.body.error_code);

						        }
						        this.addData.membername="";
				        		this.addData.email="";
				        		this.addData.telephone="";
					        }.bind(this),function(){

					        }.bind(this));
						}
					}
				}
			},
			modifyUser:function(){
				let data={};
				data={"username":vueGetData.username};
				data["type"]=3;
				console.log(this.modifyData.membername);
				if(this.modifyData.membername===''){
					vueGetData.creatTips("请填写用户名");
				}else{
					data["member_name"]=vueGetData.trim(this.modifyData.membername);
					if(this.modifyData.email===''&&this.modifyData.telephone===''){
						vueGetData.creatTips("请填写要修改的项");
					}else{
						if(this.modifyData.telephone!=''){
							data["tel_no"]=vueGetData.trim(this.modifyData.telephone);
						}
						if(this.modifyData.email!=''){
							data["email"]=vueGetData.trim(this.modifyData.email);
						}
						vueGetData.getData("userinfo",data,function(jsondata){
							console.log(jsondata);
				        	if(jsondata.body.error_code === 22000){
				        		vueGetData.creatTips("修改用户信息成功");
				        		this.$store.dispatch('getUserinfolist',{"username":vueGetData.username,"type":4})
				        	}else{
				        		vueGetData.creatTips("无操作权限，修改失败");
					        	console.log(jsondata.body.error_code);
					        }
					        this.modifyData.membername="";
				        	this.modifyData.email="";
				        	this.modifyData.telephone="";
				        }.bind(this),function(){

				        }.bind(this));
					}
				}
			},
			searchUser:function(){
				let data={};
				data={"username":vueGetData.username};
				data["type"]=4;
				console.log(this.searchData.membername);
				if(this.searchData.membername===''){
					vueGetData.creatTips("请填写用户名");
				}else{
					data["member_name"]=vueGetData.trim(this.searchData.membername);
				}

				vueGetData.getData("userinfo",data,function(jsondata){
					console.log(jsondata);
		        	if(jsondata.body.error_code === 22000){
		        		this.$store.dispatch('getUserinfolist',data);
		        		this.searchData.membername="";
		        	}else{
			        	console.log(jsondata.body.error_code)
			        }
		        }.bind(this),function(){

		        }.bind(this));
			},
			deleteUser:function(membername,event){
				let _self = this;
				let data={};
				data={"username":vueGetData.username,"type":2,"member_name":membername};
				console.log(data);

				let str = '<div class="popCreat" id="deleteLogBox">'
		            +'<h3>确定要删除吗？</h3>'
		            +'<div class="btns"><span class="surebtn" id="surebtn">确定</span><span class="cancelbtn" id="cancelbtn">取消</span></div></div>';
				vueGetData.creatPop(str);

				let surebtn = document.getElementById("surebtn");
				let cancelbtn = document.getElementById("cancelbtn");

				surebtn.onclick = function(){
					vueGetData.closePop();
					vueGetData.getData("userinfo",data,function(jsondata){
						console.log(jsondata);
			        	if(jsondata.body.error_code === 22000){
			        		vueGetData.creatTips("删除用户信息成功");
			        		_self.$store.dispatch('getUserinfolist',{"username":vueGetData.username,"type":4})
			        	}else{
			        		vueGetData.creatTips("无操作权限，删除失败");
				        	console.log(jsondata.body.error_code)
				        }
			        }.bind(this),function(){

			        }.bind(this));
		        }
		        cancelbtn.onclick = function(){
		        	vueGetData.closePop();
		        }
			}
		},
	}
</script>
<style lang="less">
.alarminfobox {
	padding: 0px 0 60px;
	position: relative;
	.infoitembox {
		padding: 20px 40px 0 10px;
		width: 1100px;
		/*font-size: 0;*/
		line-height: 30px;
		overflow: hidden;
		position: relative;
		.itemtitle {
			float: left;
			display: inline-block;
			font-size: 13px;
			width: 80px;
		}
		.itemsubtitle {
			width: 50px;
			font-size: 13px;
			//line-height: 40px;
			//margin-right: 10px;
			display: inline-block;
			vertical-align: top;
			position: relative;
		}

		.itemtext {
			float: left;
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
	.requiredItem:before{
		content:"*";
		position: absolute;
		right: 0;
		top: 2px;
		color: #f00;
		font-size: 14px;
	}
}

</style>