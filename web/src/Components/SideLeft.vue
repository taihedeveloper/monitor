<template>
	<!-- sideLeft start -->
	<div class="sideLeft" id="sideLeft">
		<div id="sideLeftbox">
			<h3>{{topname}}</h3>
			<div class="searchbox" v-show="false">
			 	<input type="text" id="tree-search" placeholder="搜索" v-model="treeSearch"><span id="ztree-search-button"></span>
			</div> 
			<div class="treeWrap">
				<div class="treeBox" id="treelist">
					<div :class="{'treeName':true,'triangleR':isShowTree,'triangleD':!isShowTree}" :data-productid="-1" @click="getProductId"><i></i><a href="javascript:;">全部</a></div>
					<ul class="treelist" v-show="isShowTree">
						<li v-for="(item,index) in lineslist" :data-id="item.id" :data-index="index" :title="item.product_name" :data-productid="item.product_id" @click="getProductId"><a href="javascript:;" class="">{{item.product_name}}</a></li>
					</ul>
				</div>
			</div>
			
		</div>
	</div>
	<!-- sideLeft end -->
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import vueGetData from "../Js/vueGetData.js"

	export default {
		name: 'SideLeft',
		data () {
			return {
				treeSearch: '',
				topname: '产品线列表',
				isShowTree: true,
				product_id: -1
			}
		},
		methods: {
			getProductId:function(event){
				let ele = event.currentTarget;
				let id = ele.getAttribute("data-productid");
				let lis = document.getElementById("treelist").getElementsByTagName("li");
				for(var i=0;i<lis.length;i++){
					lis[i].setAttribute("class","");
				}
				if(id == -1){
					let flag = this.isShowTree;
					this.isShowTree = !flag;
					this.product_id = -1;
				}else{
					this.product_id = id;
					ele.setAttribute("class","cur");
				}

				this.$store.dispatch('pushProductId',{"id":this.product_id});
				let href = location.href;
				if(href.indexOf("alarm") > -1){
					this.alarmquerydata["start"] = 0;
					this.alarmquerydata["product_line"] = this.product_id;
					this.$store.dispatch('getAlarmtableList',this.alarmquerydata)
				}else if(href.indexOf("runlog") > -1){
					this.runlogquerydata["start"] = 0;
					this.runlogquerydata["product_line"] = this.product_id;
					this.$store.dispatch('getRunlogtableList',this.runlogquerydata)
				}else{
					this.managequerydata["start"] = 0;
					this.managequerydata["product_line"] = this.product_id;
					this.$store.dispatch('getManagetableList',this.managequerydata)
				}
			},
			getH: function(){
				document.getElementById("sideLeft").style.height = vueGetData.getContHeight(68) + "px";
			}
		},
		computed:mapGetters({
			productId:"productId",
			lineslist:"lineslist",
			managequerydata:"managequerydata",
			alarmquerydata:"alarmquerydata",
			runlogquerydata:"runlogquerydata"
		}),
		mounted(){
			// this.getH();
		},
		created(){
			this.$store.dispatch('getLines',{"type":4,"username":vueGetData.username});
		}
	}
</script>
<style lang="less" >
@import "../Css/mixin.less";

.sideLeft{
	position: absolute;
	top: 68px;
	left: 0;
	width: 200px;
	min-height: 500px;
	.borderColor();
	border-left-color: @colorCcc;
	border-right: none;
	background: @bgGray;
	border-top: none;
	border-bottom: none;
	overflow-y: auto;

	h3{
		@h3H: 50px;
		padding-left: 20px;
		height: @h3H;
		line-height: @h3H;
		border-bottom: 1px solid @colorBbb;
		.fontSizes(16px);
	}

	.searchbox {
		position: relative;
		@h: 40px;
		padding-right: 20px;
		height: @h;

		#tree-search {
			float: left;
			height: @h;
			line-height: @h;
			width: 160px;
			border: none;
			color: #333;
			background: none;
		}
		#ztree-search-button {
			position: absolute;
			top: 10px;
			right: 18px;
			width: 20px;
			height: 20px;
			background:url(@searchico) no-repeat;
			background-size:20px;
			cursor:pointer;
		}
	}

	.treeWrap {
		width: 100%;
		overflow: hidden;

		.treeName {
			overflow: hidden;
			padding: 10px 0;
			margin: 0 20px 0 10px;
			position: relative;
			i{
				position: absolute;
				top: 10px;
				left: 10px;
			}
			a{
				display: inline-block;
				padding-left: 30px;
				font-size: 16px;
			}
/*			i,a{
				float: left;
			}*/
			&.triangleR {
				i{
					.triangleR(@color: @hoverColor);
				}
				a{
					color: @hoverColor;
				}
			}
			&.triangleD {
				i{
					.triangleD();
				}
			}
		}

		.treelist {
			line-height: 30px;

			li{
				padding-left: 35px;
				height: 30px;

				&.cur{
					background: rgba(64,133,244,.3);
					a {
						color: @hoverColor;
				}
				}
			}
		}
	}
}

</style>
