<template>
		<div class="table-div">
			<table class="manage-table">
				<thead>
					<tr>
						<th width="4%"><input type="checkbox" name="check-all" disabled="disabled"></th><th width="8%">序号</th><th width="20%">监控名称</th><th width="20%">监控类别</th><th width="18%">监控状态</th><th width="20%">运行时间</th><th width="10%">耗时(ms)</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="value in runloglist">
						<td><input type="checkbox" disabled="disabled"></td>
						<td>{{value.id}}</td>
						<td>{{value.item_name}}</td>
						<td>{{value.item_type}}</td>
						<td>{{value.run_status}}</td>
						<td>{{value.run_time | formatDate}}</td>
						<td>{{value.time_consume}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	<!-- </div> -->
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import vueGetData from "../../Js/vueGetData.js"

	export default {
		name: 'Runlogtable',
		data () {
			return {
				thildRunlogTable: {}
			}
		},
		computed:mapGetters({
			runloglist:"runloglist",
			runlogquerydata:"runlogquerydata"
		}),
		watch: {
			runloglist:function(){
			}
		},
		mounted: function(){
		}
	}
</script>
