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
router.beforeEach(function(to, from, next) {
    if (to.meta.needLogin) {
        //页面是否登录
        if (localStorage.getItem("token")) {
            //本地存储中是否有token(uid)数据
            next(); //表示已经登录
        } else {
            //next可以传递一个路由对象作为参数 表示需要跳转到的页面
            next({
                name: "login"
            });
        }
    } else {
        //表示不需要登录
        next(); //继续往后走
    }
});


