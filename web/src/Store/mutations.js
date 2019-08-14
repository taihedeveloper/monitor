import * as  types from './mutation-types.js'
import {INCREMENT} from './mutation-types.js'

 //定义变量
const state = {
    count:10,
    inputvalue: "hello"
}

//事件处理：异步请求、判断、流程控制
const actions = {
    increment:function({commit}){
        // commit("increment"); //将commit中指定的名称放在mutation-types中定义
        commit(types.INCREMENT);
    }
}
//处理状态、数据的变化
const mutations = {
    [INCREMENT](state){ //INCREMENT是变量 需使用[]
        state.count++;
    }
}

//导出数据
const getters = {
    count(state){
        return state.count;
    },
    inputvalue(state){
        return state.inputvalue;
    }
}

export default{
    state,
    actions,
    mutations,
    getters
}