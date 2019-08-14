<template>
  <div id="app" class="cf">
<!--     <input type="button" @click="increment" value="点击我 让数据增加">
    <p>我的数据是{{count}}</p>
    <input type="text" v-model="count" @blur="increment">
    <p>我的内容是{{inputvalue}}</p>
    <input type="radio" name="test" v-model="inputvalue" @blur="increment">我是单选按钮01 
     <input type="radio" name="test" v-model="inputvalue" @blur="increment">我是单选按钮01
    <p>我的内容是{{inputvalue}}</p>
    <br> -->
    <Headers></Headers>
    <!-- <SideLeft></SideLeft> -->
    <!-- 路由 -->
    <router-view></router-view>
  </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
import Headers from './Headers.vue'
import SideLeft from './SideLeft.vue'

export default {
  name: 'app',
  data () {
    return {
      msg: 'Welcome to My Vue.js App'
    }
  },
  methods:mapActions([
    "increment"
  ]),
  components:{
    Headers,
    SideLeft
  },
  computed:mapGetters([
    'count',
    'inputvalue'
  ])
}
</script>
<style lang="less">
@import "../Css/common";
@import "../Css/dialog.less";
@import "../Js/plugins/kalendae/css/kalendae.css";
#app{
  width: 100%;
}
</style>