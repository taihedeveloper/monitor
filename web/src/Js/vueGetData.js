import Vue from 'vue'
import * as Config from './config.js'
import Common from './common.js'

export default{
	getData (portName,data,successCallback,failCallback,url) {
        let apiurl = "";
        if(url){
            apiurl = url + portName;
        }else{
             apiurl = Config.Apihost + portName;
        }
        const params = data;
		Vue.http.jsonp(apiurl,{
            params: params,
            jsonp: 'callback'
        }).then(function(res) {
            successCallback(res);
        },function(err){
            failCallback(err);
        })
	},
    postData (portName,data,successCallback,failCallback,url) {
        let apiurl = "";
        if(url){
            apiurl = url + portName;
        }else{
             apiurl = Config.Apihost + portName;
        }
        const params = data;
        Vue.http.post(apiurl,params,{emulateJSON:true}).then(function(res) {
            successCallback(res);
        },function(err){
            failCallback(err);
        })
    },
    fetchPostData(portName,data,successCallback,failCallback,url) {
        let apiurl = "";
        if(url){
            apiurl = url + portName;
        }else{
             apiurl = Config.Apihost + portName;
        }
        console.log(apiurl)
        let params = JSON.stringify(data);
        console.log(params);
        fetch(apiurl,{
            method: "POST",
            mode: "no-cors",
            body: params,
            headers: {
                // "Content-Type": "application/x-www-form-urlencoded"
                // "Content-Type": 'application/json'
                // "Content-Type": 'text/plain'
                "Content-Type": 'multipart/form-data '
            },
        }).then(function(res) {
            console.log("res:"+res)
            console.log(res)
            console.log("Response succeeded?", JSON.stringify(res.ok));
            
        }).catch(function(e) {
            console.log(e)
            console.log("fetch fail");
        });
    },
    username(){
        return Common.getCookie("personalid");
    },
    getDataLimit (n){
        // n ? n : 20;
        return 20;
    },
    getContHeight (headerH){
        headerH ? headerH : 68 
        let height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        return height - headerH;
    },
    getTimestamp:function(val){
        let start = "";
        let end = "";
        let orther = {};
        if(!val || val == ""){  //若时间为空，默认为前一天的数据

            let date = new Date(new Date()-24*60*60*1000);//取前24小时的时间
            let year = date.getFullYear();
            let month = date.getMonth() + 1;
            let day = date.getDate();
            var hour = date.getHours();
            var minute = date.getMinutes();
            var second = date.getSeconds();
            if(month < 10){month = "0" + month}
            if(day < 10){day = "0" + day}
            if(hour < 10){hour = "0" + hour}
            if(minute < 10){minute = "0" + minute}
            if(second < 10){second = "0" + second}

            let nowdate = new Date(); //此刻
            let nowyear = nowdate.getFullYear();
            let nowmonth = nowdate.getMonth() + 1;
            let nowday = nowdate.getDate();
            var nowhour = nowdate.getHours();
            var nowminute = nowdate.getMinutes();
            var nowsecond = nowdate.getSeconds();
            if(nowmonth < 10){nowmonth = "0" + nowmonth}
            if(nowday < 10){nowday = "0" + nowday}
            if(nowhour < 10){nowhour = "0" + nowhour}
            if(nowminute < 10){nowminute = "0" + nowminute}
            if(nowsecond < 10){nowsecond = "0" + nowsecond}

            let time = year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
            let nowtime = nowyear + "-" + nowmonth + "-" + nowday + " "  + nowhour + ":" + nowminute + ":" + nowsecond;
            start = time; 
            end = nowtime; 
            orther = {
                "s":year + "-" + month + "-" + day,
                "e":nowyear + "-" + nowmonth + "-" + nowday
            };
       }else{
            if(val.indexOf(" - ")>-1){ //起始和结束
                let valArr = val.split(" - ");
                let s = valArr[0];
                let e = valArr[1];
                start = s + " " + "00:00:00";
                end = e + " " + "23:59:59";
            }else{  //只有一天时间
                start = val + " " + "00:00:00";
                end = e + " " + "23:59:59";
            }
        }
        console.log(start)
        console.log(end)

        start = new Date(start).getTime();
        end = new Date(end).getTime();
        start = start/1000;
        end = end/1000;
        console.log({"start":start,"end":end})
        return {"start":start,"end":end,"orther":orther};
    },
    //获取月份时间段
    getMonthSpace:function(n){
        n = n ? n : 0;
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();

        date.setMonth(month - n);

        let yearNew = date.getFullYear();
        let monthNew = date.getMonth() + 1;
        let dayNew = date.getDate();

        if(month < 10){month = "0" + month}
        if(day < 10){day = "0" + day}
        if(monthNew < 10){monthNew = "0" + monthNew}
        if(dayNew < 10){dayNew = "0" + dayNew}

        let start_date = "";
        let end_date = "";
        if(n==0){
            start_date = "" + year + month + day;
            end_date = "" + year + month + "01";
        }else{
            start_date = "" + year + month + day;
            end_date = "" + yearNew + monthNew + "01";
        }
        console.log({"start_date":end_date,"end_date":start_date});
        return {"start_date":end_date,"end_date":start_date};
    },
    trim:function(str){
        return str.replace(/^\s+|\s+$/g,'')
    },
    isJSON: function(str) {
        try {
            JSON.parse(str);
            return true;
        } catch(e) {
            console.log(e);
            return false;
        }
    },
    creatTips: function(str,time){
        var time = time ? time : 1000;
        let div = document.createElement("div");
        div.setAttribute("class","tipsbox twocenter");
        div.innerHTML = '<span>'+str+'</span>';
        document.body.appendChild(div);

        setTimeout(function(){
            div.setAttribute("class","tipsbox twocenter hide");
        },time);
        setTimeout(function(){
            document.body.removeChild(div);
        },time+1000);

    },
    creatPop: function(str){
        let mask = document.createElement("div");
        mask.setAttribute("class","mask");
        mask.setAttribute("id","mask");
        mask.innerHTML = '';
        document.body.appendChild(mask);

        let popWrap = document.createElement("div");
        popWrap.setAttribute("class","popBox twocenter");
        popWrap.setAttribute("id","popBox");
        popWrap.innerHTML = '<div class="popWrap">' + str + '</div>';
        document.body.appendChild(popWrap);

    },
    closePop: function(){
        let mask = document.getElementById("mask");
        if(mask){
            document.body.removeChild(mask);
        }
        let popBox = document.getElementById("popBox");
        if(popBox){
            document.body.removeChild(popBox);
        }
    }
}

//timestamp => time
Vue.filter('formatDate', function(timestamp){
    if(timestamp == 0){
        return  "";
    }
    if(timestamp){
        let date = new Date(timestamp*1000);
        let year=date.getFullYear();
        let month=date.getMonth()+1;     
        let day=date.getDate();     
        let hour=date.getHours();     
        let minute=date.getMinutes();     
        let second=date.getSeconds(); 

        return  year+"-"+Common.doublenum(month)+"-"+Common.doublenum(day)+"   "+Common.doublenum(hour)+":"+Common.doublenum(minute)+":"+Common.doublenum(second); 
    }else{
        return  ""; 
    }
})