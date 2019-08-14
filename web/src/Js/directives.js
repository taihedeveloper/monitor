import Vue from 'vue'

// 注册一个全局自定义指令 v-shodialog
Vue.directive('showdialog',{
    // obj.style.display = "block";
    bind(el,binding,vnode){
        console.log("bind"); //只调用一次
        console.log(el);
        console.log(binding);
        console.log(vnode);
    },
    update(el,binding,vnode){
        console.log(el);
        console.log(binding);
        console.log(vnode);
    }
})