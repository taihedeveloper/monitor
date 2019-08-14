import * as  types from '../mutation-types.js'
import vueGetData from "../../Js/vueGetData.js"

 //定义变量
const state = {
    managequerydata:{},
    httptableList:[],
    jsconftableList:[],
    elestableList:[],
    //////////////////////////////
    userinfolist:[],

    certificatemanagelist:[],
    permissioclassid: "",
    certificatememberlist:{},
    ////////////////////////////
    alertmanagelist:[],
    alertclassid: "",
    alertmemberlist:{},
    /////////////////////////////
    robotacountlist:[],
    robotacountid:"",
    allpage: 0,
    tableTotal: null
}

//事件处理：异步请求、判断、流程控制
const actions = {
    getManagequerydata:function({commit},paramsJson){
        var data = paramsJson;
        commit(types.MANAGEQUERYDATA,data);
    },
    //获取表格数据
    getManagetableList:function({commit},paramsJson){
        let type = paramsJson.type;
        let json = {};
        for(let key in paramsJson){
            if(!(key == "product_line" && paramsJson[key] == -1)){
                json[key] = paramsJson[key];
            } 
        }
        vueGetData.getData("itemshow",json,function(jsondata){
            if(jsondata.body.error_code === 22000){
                let list = jsondata.body.data;
                let count = parseInt(jsondata.body.count);
                let total = Math.ceil(count/20);
                if(type == 0){
                    commit(types.HTTPTABLELIST,{list,total,count});
                }else if(type == 1){
                console.log("type=1");
                    commit(types.JSCONFTABLELIST,{list,total,count});
                }else if(type == 2){
                    commit(types.ELESTABLELIST,{list,total,count});
                }
            }   
        },function(err){
            console.log(err)
        })
    },
    //删除某一项
    deleteManagetableList: function({commit},paramsJson) {
        let data = paramsJson.data;
        let index = paramsJson.index;
        let type = paramsJson.type;
        vueGetData.postData("itemmodify",data,function(jsondata){
            if(jsondata.body.error_code === 22000){
                if(type == 0){
                    commit(types.HTTPDELETEINDEX,index)
                }else if(type == 1){
                    commit(types.JSCONFDELETEINDEX,index)
                }else if(type == 2){
                    commit(types.ELESDELETEINDEX,index)
                }
            }   
        },function(err){
            console.log(err);
        })
    },
    //启用某一项
    startManagetableList: function({commit},paramsJson) {
        let data = paramsJson.data;
        let index = paramsJson.index;
        let type = paramsJson.type;
        vueGetData.postData("itemmodify",data,function(jsondata){
            if(jsondata.body.error_code === 22000){
                if(type == 0){
                    commit(types.HTTPSTARTINDEX,index)
                }else if(type == 1){
                    commit(types.JSCONFSTARTINDEX,index)
                }else if(type == 2){
                    commit(types.ELESSTARTINDEX,index)
                }
                vueGetData.creatTips("启用成功");
            }else if(jsondata.body.error_code === 22001){
                vueGetData.creatTips("系统错误请联系管理员查看问题");
            }   
        },function(err){
            console.log(err);
        })
    },
    //禁用某一项
    stopManagetableList: function({commit},paramsJson) {
        let data = paramsJson.data;
        let index = paramsJson.index;
        let type = paramsJson.type;
        vueGetData.postData("itemmodify",data,function(jsondata){
            if(jsondata.body.error_code === 22000){
                if(type == 0){
                    commit(types.HTTPSTOPINDEX,index)
                }else if(type == 1){
                    commit(types.JSCONFSTOPINDEX,index)
                }else if(type == 2){
                    commit(types.ELESSTOPINDEX,index)
                }
            }   
        },function(err){
            console.log(err);
        })
    },
    ////////////////////////////////////////////////////////////////////
    getUserinfolist: function({commit},paramsJson) {
        vueGetData.getData("userinfo",paramsJson,function(jsondata){
            if(jsondata.body.error_code === 22000){
                let list = jsondata.body.data;
                commit(types.USERINFOLIST,list)
            }   
        },function(err){
            console.log(err);
        })
    },
    //获取权限组
    getCertificatemanagelist: function({commit},paramsJson) {
        vueGetData.getData("certificatemanage",paramsJson,function(jsondata){
            if(jsondata.body.error_code === 22000){
                let list = jsondata.body.data;
                commit(types.CERTIFICATEMANAGELIST,list)
            }   
        },function(err){
            console.log(err);
        })
    },
    getCertificatememberlist: function({commit},paramsJson){
        vueGetData.getData("certificatemember",paramsJson,function(jsondata){
            let data = {};
            if(jsondata.body.error_code === 22000){
                data = jsondata.body;
                data.class_name = paramsJson.class_name;
                data.class_id = paramsJson.class_id;
            }else if(jsondata.body.error_code === 22001) {
                data.class_name = paramsJson.class_name;
                data.class_id = paramsJson.class_id;
            }
            commit(types.CERTIFICATEMEMBERLIST,data)
        },function(err){
            console.log(err);
        })
    },
    //////////////////////////////////////////////////////////////////
    //获取报警组
    getAlertemanagelist: function({commit},paramsJson) {
        vueGetData.getData("alertmanage",paramsJson,function(jsondata){
            if(jsondata.body.error_code === 22000){
                let list = jsondata.body.data;
                commit(types.ALERTMANAGELIST,list)
            }
        },function(err){
            console.log(err);
        })
    },

    //获取报警组成员列表
    getAlertmemberlist: function({commit},paramsJson){
        vueGetData.getData("alertmember",paramsJson,function(jsondata){
            let data = {};
            if(jsondata.body.error_code === 22000){
                data = jsondata.body;
                data.class_name = paramsJson.class_name;
                data.class_id = paramsJson.class_id;
            }else if(jsondata.body.error_code === 22001) {
                data.class_name = paramsJson.class_name;
                data.class_id = paramsJson.class_id;
            }
            commit(types.ALERTMEMBERLIST,data)
        },function(err){
            console.log(err);
        })
    },
    ///////////////////////////////////////////////////////////////////
    //获取虚拟用户列表
    getRobotacountlist: function({commit},paramsJson) {
        vueGetData.getData("robotacount",paramsJson,function(jsondata){
            if(jsondata.body.error_code === 22000){
                let list = jsondata.body.data;
                commit(types.ROBOTACOUNTLIST,list)
            }   
        },function(err){
            console.log(err);
        })
    },
}
//处理状态、数据的变化
const mutations = {
    [types.HTTPTABLELIST](state , params){
        state.httptableList = params.list;
        state.allpage = params.total;
        state.tableTotal = params.count;
    },
    [types.JSCONFTABLELIST](state , params){
        state.jsconftableList = params.list;
        state.allpage = params.total;
        state.tableTotal = params.count;
    },
    [types.ELESTABLELIST](state , params){
        state.elestableList = params.list;
        state.allpage = params.total;
        state.tableTotal = params.count;
    },

    [types.HTTPDELETEINDEX](state , index){
        state.httptableList.splice(index,1);
    },
    [types.JSCONFDELETEINDEX](state , index){
        state.jsconftableList.splice(index,1);
    },
    [types.ELESDELETEINDEX](state , index){
        state.elestableList.splice(index,1);
    },

    [types.HTTPSTARTINDEX](state , index){
        state.httptableList[index].status = 2;
    },
    [types.JSCONFSTARTINDEX](state , index){
        state.jsconftableList[index].status = 2;
    },
    [types.ELESSTARTINDEX](state , index){
        state.elestableList[index].status = 2;
    },

    [types.HTTPSTOPINDEX](state , index){
        state.httptableList[index].status = 1;
    },
    [types.JSCONFSTOPINDEX](state , index){
        state.jsconftableList[index].status = 1;
    },
    [types.ELESSTOPINDEX](state , index){
        state.elestableList[index].status = 1;
    },
    /////////////////////////////////////////////////////
    [types.USERINFOLIST](state , list){
        state.userinfolist = list;
    },
    [types.CERTIFICATEMANAGELIST](state , list){
        state.certificatemanagelist = list;
        if(list.length>0){
            // state.permissioclassid = list[0].class_name;
            state.permissioclassid = list[0].class_id;
        }
    },
    [types.CERTIFICATEMEMBERLIST](state, data){
        state.certificatememberlist = data;
    },
    ///////////////////////////////////////////////////
    [types.ALERTMANAGELIST](state , list){
        state.alertmanagelist = list;
        if(list.length>0){
            // state.alertclassid = list[0].class_name;
            state.alertclassid = list[0].class_id;
        }
    },
    [types.ALERTMEMBERLIST](state, data){
        state.alertmemberlist = data;
    },

    /////////////////////////////////////////////////
    [types.ROBOTACOUNTLIST](state , list){
        state.robotacountlist = list;
        if(list.length>0){
            state.robotacountid = list[0].acount_name;
        }
    },

    [types.MANAGEQUERYDATA](state , data){
        state.managequerydata = data;
    }
}

//导出数据
const getters = {
    httptableList(state){
        return state.httptableList;
    },
    jsconftableList(state){
        return state.jsconftableList;
    },
    elestableList(state){
        return state.elestableList;
    },
    ///////////////////////////////////////////
    userinfolist(state){
        return state.userinfolist;
    },
    certificatemanagelist(state){
        return state.certificatemanagelist;
    },
    permissioclassid(state){
        return state.permissioclassid;
    },
    certificatememberlist(state){
        return state.certificatememberlist;
    },
    //////////////////////////////////////////
    alertmanagelist(state){
        return state.alertmanagelist;
    },
    alertclassid(state){
        return state.alertclassid;
    },

    alertmemberlist(state){
        return state.alertmemberlist;
    },

    //////////////////////////////////////////
    robotacountlist(state){
        return state.robotacountlist;
    },
    robotacountid(state){
        return state.robotacountid;
    },
    managequerydata(state){
        return state.managequerydata;
    },
    allpage(state){
        return state.allpage;
    },
    tableTotal(state){
        return state.tableTotal;
    }
}

export default{
    state,
    actions,
    mutations,
    getters
}