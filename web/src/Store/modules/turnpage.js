import * as  types from '../mutation-types.js'
import vueGetData from "../../Js/vueGetData.js"

 //定义变量
const state = {
    pagesConfig:{
        totalpages:null,
        index:null
    }
}

//事件处理：异步请求、判断、流程控制
const actions = {
    getTotal:function({commit},paramsJson){
        commit(types.GETLINELIST,list); //将commit中指定的名称放在mutation-types中定义
    }
}
//处理状态、数据的变化
const mutations = {
    [types.GETLINELIST](state , list){ //GETLINELISTT是变量 需使用[]
        state.lineslist = list;
    }
}

//导出数据
const getters = {
    totalpages(state){
        return state.pagesConfig;
    }
}

export default{
    state,
    actions,
    mutations,
    getters
}