export default{
    serializeJSON(jsondata){
        let str = "";
        for(let k in jsondata){
             str+='&'+k+'='+jsondata[k];
        }
        return str;
    },
    addCookie(name,value,iDay){
        if(iDay){
            var oDate=new Date();
            oDate.setDate(oDate.getDate()+iDay);
            document.cookie=name+'='+value+';path=/;expires='+oDate.toGMTString();
        }else{
            document.cookie=name+'='+value+';path=/';
        }
    },
    getCookie(name){
        var arr=document.cookie.split('; ');
        for(var i=0; i<arr.length; i++){
            var arr2=arr[i].split('=');
            if(arr2[0]==name){
                return arr2[1]; 
            }
        }
        return '';
    },
    removeCookie(name){
        addCookie(name,'asdfasdf',-10);
    },
    //两位数
    doublenum:function(n){
        if(n<=9){
            n = "0"+n;
        }else{
            n = ""+n;
        }
        return n;
    }
}
