import Vue from 'vue'
import App from './App'
import router from './router.js'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import '../static/home/css/style.css'
import 'element-ui/lib/index.js'
import '../static/home/ueditor/ueditor.parse.js'
import axios from 'axios'
import qs from 'qs'
import CarverHeader from './public/CarverHeader'
import CarverFooter from './public/CarverFooter'

Vue.use(ElementUI)
Vue.config.productionTip = false
Vue.prototype.$http = axios
Vue.prototype.$qs = qs
Vue.component('CarverHeader',CarverHeader);
Vue.component('CarverFooter',CarverFooter);
window.vue = new Vue({
  el: '#app',
  data:{
    abc:123
  },
  router,
  template: '<App/>',
  components: { App },
})


