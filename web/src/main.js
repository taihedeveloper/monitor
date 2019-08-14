import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Vuex from 'vuex'
import App from './Components/App.vue'
import routerConfig from './router.config.js'
import store from './Store/store.js'
import $ from 'jquery'

Vue.use(VueRouter)
Vue.use(VueResource)
Vue.use(Vuex)

const router = new VueRouter(routerConfig);

// require('./Js/onload.js')

new Vue({
	router,
	store,
	el: "#app",
	render: h => h(App)
});

