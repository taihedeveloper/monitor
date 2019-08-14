import Home from './Components/pages/Home.vue'
import ManagAll from './Components/pages/ManagAll.vue'
import Manageitems from './Components/pages/Manageitems.vue'
import Runlog from './Components/pages/Runlog.vue'
import Alarm from './Components/pages/Alarm.vue'
import AlarmInfo from './Components/pages/AlarmInfo.vue'

import Manageusers from './Components/pages/Manageusers.vue'
import Productline from './Components/pages/Productline.vue'
import AlarmInfoBatch from './Components/pages/AlarmInfoBatch.vue'

export default{
	routes:[
		// {path:'/index',component:Home},
		{path:'/index',component:Manageitems},
		{path:'/manageitems',component:Manageitems},
		{path:'/manageitemsname/:itemname',component:Manageitems},
		{path:'/runlog',component:Runlog},
		{path:'/alarm',component:Alarm},
		{path:'/alarm/:alertid',component:Alarm},
		{path:'/alarminfo/:alertid',component:AlarmInfo},
		{path:'/',component:Manageitems},
		{path:'*',redirect:'/manageitems'},
		{path:'/manageusers',component:Manageusers},
		{path:'/productline',component:Productline},
		{path:'/alarminfobatch',component:AlarmInfoBatch},
	]
}