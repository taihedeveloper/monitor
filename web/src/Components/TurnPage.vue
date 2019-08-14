<template>
	<div class="pageDiv">
<!-- 		<el-pagination layout="prev, pager, next" :total="50">
  		</el-pagination>
 -->
		<ul class="pagination">
			<li class="pre" v-show="current != 1" @click="current-- && goto(current)" ><a href="javascript:;">&lt;</a></li>
			<li v-for="index in pages" :class="{'num':true,'active':current == index}" :key="index"><a href="javascript:;" @click="goto(index)">{{index}}</a></li>

			<li class="next" v-show="allpage != current && allpage != 0 " @click="current++ && goto(current++)"><a href="javascript:;">&gt;</a></li>
		</ul>

		<span class="page-total">共{{allpage}}页</span>
		<span class="num-total">共{{tableTotal}}条</span>
	</div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
	export default {
		name: "TurnPage",
		data () {
			return {
	            current:1,
	            showItem:15,
	            // allpage:13,
	            pages:[],
			}
		},
		props:["allpage","tableTotal","type"],
		created: function(){
			// this.getpages();
		},
		mounted:function(){
			this.getpages();
		},
		computed:mapGetters({
			productId:"productId",
			managequerydata:"managequerydata",
			alarmquerydata:"alarmquerydata",
			runlogquerydata:"runlogquerydata",
		}),
		watch: {
			tableTotal: function(){
				this.getpages();
			},
			productId: function(){
				this.current = 1; 	//切换产品线 翻页跳到第一页
			}
		},
		methods: {
			getpages:function(){
				var pag = [];
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
				this.pages = pag;
				return pag
			},
	        goto:function(index){
	        	if(index == this.current) return;
        		this.current = index;
        		this.getpages();
        		
				let types = this.type;
				let start = (index-1)*20;
            	if(types == 0 || types == 1 || types == 2){
	            	this.managequerydata["start"] = start;
					this.$store.dispatch('getManagequerydata',this.managequerydata);
					this.$store.dispatch('getManagetableList',this.managequerydata)
            	}else if(types == "alarmtable"){
	            	this.alarmquerydata["start"] = start;
					this.$store.dispatch('getAlarmquerydata',this.alarmquerydata);
					this.$store.dispatch('getAlarmtableList',this.alarmquerydata)
            	}else if(types == "runlogtable"){
					let json = {};
					for(let key in this.runlogquerydata){
						if(key == "start"){
							json["start"] = start;
						}else{
							json[key] = this.runlogquerydata[key];
						}
					}
	            	json["start"] = start;
					this.$store.dispatch('getRunlogquerydata',json);
					this.$store.dispatch('getRunlogtableList',this.runlogquerydata)

					// let data = {"origin":this.runlogquerydata,"start":start}
					// this.$store.dispatch('getRunlogquerydata',data);
					// this.$store.dispatch('getRunlogtableList',this.runlogquerydata)

            	}
	        }

		}
	}
</script>
