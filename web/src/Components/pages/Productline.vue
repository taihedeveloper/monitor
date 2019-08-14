<template>
	<div class="productlinebox" >
		<div class="infoitembox">
			<div class="itemtitle">添加产品线</div>
			<div style="display:inline-block;width:800px;">
				<div class="itemsubtitle requiredItem">名称</div>
				<input type="text" class="dataport-dialog-name "  v-model="addData.name" placeholder="">
				<button @click="addProductline">添加</button>
			</div>
		</div>
		<div class="infoitembox">
			<div class="itemtitle">修改产品线</div>
			<div style="display:inline-block;width:800px;">
				<div class="itemsubtitle  requiredItem">ID</div>
				<input type="text" class="dataport-dialog-name"  v-model="modifyData.id" placeholder="">
				<div class="itemsubtitle requiredItem">名称</div>
				<input type="text" class="dataport-dialog-name"  v-model="modifyData.name" placeholder="">
				<button @click="modifyProductline">修改</button>
			</div>
		</div>

		<div class="infoitembox"></div>
		<!--表格数据-->
		<div class="table-div">
			<table class="manage-table">
				<thead>
					<tr>
						<th width="5%"><input type="checkbox" name="check-all" disabled="disabled"></th>
						<th width="30%">产品线ID</th>
						<th width="35%">产品线名称</th>
						<th width="25%">操作</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(value,index) in linesList">
						<td><input type="checkbox" disabled="disabled"></td>
						<td>{{value.product_id}}</td>
						<td>{{value.product_name}}</td>
						<td><a href="javascript:;" @click="deleteProductline(value.product_id,$event)">删除</a></td>
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
					name:'',
				},
				modifyData:{
					id:'',
					name:'',
				},
				deleteData:{
					id:'',
				},
			}
		},
		computed:mapGetters({
			linesList:"lineslist",
		}),
		methods:{
			addProductline:function(){
				let data={};
				data={"username":vueGetData.username};
				data["type"]=1;
				console.log(this.addData.name);
				if(this.addData.name===''){
					vueGetData.creatTips("请填写产品线名称");
				}else{
					data["product_name"]=vueGetData.trim(this.addData.name);
					vueGetData.getData("productline",data,function(jsondata){
						console.log(jsondata);
			        	if(jsondata.body.error_code === 22000){
			        		vueGetData.creatTips("添加产品线成功");
			        		this.$store.dispatch('getLines',{"username":vueGetData.username,"type":4})
			        	}else{
			        		vueGetData.creatTips("无操作权限，添加失败");
				        	console.log(jsondata.body.error_code);

				        }
				        this.addData.name="";
			        }.bind(this),function(){

			        }.bind(this));
				}
			},
			modifyProductline:function(){
				let data={};
				data={"username":vueGetData.username};
				data["type"]=3;
				console.log(this.modifyData.id);
				if(this.modifyData.id===''){
					vueGetData.creatTips("请填写产品线ID");
				}else{
					data["product_id"]=vueGetData.trim(this.modifyData.id);
					if(this.modifyData.name===''){
						vueGetData.creatTips("请填写产品线名称");
					}else{
						data["product_name"]=vueGetData.trim(this.modifyData.name);
						vueGetData.getData("productline",data,function(jsondata){
							console.log(jsondata);
				        	if(jsondata.body.error_code === 22000){
				        		vueGetData.creatTips("修改产品线名称成功");
				        		this.$store.dispatch('getLines',{"username":vueGetData.username,"type":4})
				        	}else{
				        		vueGetData.creatTips("无操作权限，修改失败");
					        	console.log(jsondata.body.error_code);
					        }
					        this.modifyData.id="";
				        	this.modifyData.name="";
				        }.bind(this),function(){

				        }.bind(this));
					}
				}
			},
			deleteProductline:function(id,event){
				let _self = this;
				let data={};
				data={"username":vueGetData.username,"type":2,"product_id":id};
				console.log(data);

				let str = '<div class="popCreat" id="deleteLogBox">'
		            +'<h3>确定要删除吗？</h3>'
		            +'<div class="btns"><span class="surebtn" id="surebtn">确定</span><span class="cancelbtn" id="cancelbtn">取消</span></div></div>';
				vueGetData.creatPop(str);

				let surebtn = document.getElementById("surebtn");
				let cancelbtn = document.getElementById("cancelbtn");

				surebtn.onclick = function(){
					vueGetData.closePop();
					vueGetData.getData("productline",data,function(jsondata){
						console.log(jsondata);
			        	if(jsondata.body.error_code === 22000){
			        		vueGetData.creatTips("删除产品线成功");
			        		_self.$store.dispatch('getLines',{"username":vueGetData.username,"type":4})
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
		created(){
			this.$store.dispatch('getLines',{"username":vueGetData.username,"type":4});
		}
	}
</script>
<style lang="less">
.productlinebox {
	padding: 10px 20px 60px;
	position: relative;
	background: #ffffff;
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
			width: 35px;
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