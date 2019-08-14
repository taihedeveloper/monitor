import * as  types from '../mutation-types.js'
import vueGetData from "../../Js/vueGetData.js"

 //定义变量
const state = {
    lineslist:[],
    productId:null
}

//事件处理：异步请求、判断、流程控制
const actions = {
    getLines:function({commit},paramsJson){
        vueGetData.getData("productline",paramsJson,function(jsondata){
            if(jsondata.body.error_code === 22000){
                let list = jsondata.body.data;
                commit(types.GETLINELIST,list); //将commit中指定的名称放在mutation-types中定义
            }   
        },function(err){
            console.log(err)
        })
    },
    pushProductId: function({commit},paramsJson) {
        let id = paramsJson.id;
        commit(types.PRODUCTID,id)
    }
}
//处理状态、数据的变化
const mutations = {
    [types.GETLINELIST](state , list){ //GETLINELISTT是变量 需使用[]
        state.lineslist = list;
    },
    [types.PRODUCTID](state , id){
        state.productId = id;
    }
}

//导出数据
const getters = {
    lineslist(state){
        return state.lineslist;
    },
    productId(state){
        return state.productId;
    }
}

export default{
    state,
    actions,
    mutations,
    getters
}