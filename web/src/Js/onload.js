import vueGetData from "./vueGetData.js"

window.onload = function () {
    let username = vueGetData.username();
    vueGetData.getData("loginuser",{"username":username},function(jsondata){
        if(jsondata.body.error_code === 22000){
            console.log("用户有权限");
        }if(jsondata.body.error_code === 22009){
            console.log(location.host)
        }
    }.bind(this),function(err){
        console.log(err);
    }.bind(this));
}
