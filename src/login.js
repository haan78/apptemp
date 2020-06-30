import Vue from 'vue'
import DefaultMixin from "./lib/Vue/DefaultMixin"
import Url from "./lib/Vue/UrlVue";
import Login from "./login.vue";

import ElementUI from 'element-ui';
import locale from "element-ui/lib/locale/lang/tr-TR"

Vue.mixin(DefaultMixin);
Vue.use(ElementUI,{ locale });
Vue.use(Url);

new Vue({    
    render: h => h(Login)
    }).$mount('#app');