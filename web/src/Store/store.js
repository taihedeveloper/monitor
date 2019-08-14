import Vue from 'vue'
import Vuex from 'vuex'

import manage from './modules/manage.js'
import produceline from './modules/produceline.js'
import alarm from './modules/alarm.js'
import turnpage from './modules/turnpage.js'

const debug = process.env.NODE_ENV !== 'production'
Vue.use(Vuex)
Vue.config.debug = debug


//导出store对象
export default new Vuex.Store({
    //组合各个模块
    modules:{
        produceline,
        manage,
        alarm,
        turnpage   
    },
    strict: debug

})
