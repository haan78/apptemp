import Vue from 'vue'
import MyDateVue from './lib/Vue/MyDateVue'
import IsStringVue from './lib/Vue/IsStringVue';
import Url from './lib/Vue/UrlVue';
import AxiosHttp from "./lib/Vue/AxiosHttp";
import DefaultMixin from "./lib/Vue/DefaultMixin"
import VueRouter from 'vue-router'

import main from "./main.vue";
import routes from "./routes";

import ElementUI from 'element-ui';
import locale from "element-ui/lib/locale/lang/tr-TR"
import JsDocument from "./lib/Vue/JsDocument";

import "./theme/index.css";

Vue.use(ElementUI, { locale });

Vue.use(MyDateVue);
Vue.use(IsStringVue);
Vue.use(Url);
Vue.use(AxiosHttp);
Vue.mixin(DefaultMixin);
Vue.use(VueRouter);
Vue.config.productionTip = false;

var router = new VueRouter({ routes });

new Vue({
    router,
    render: h => h(main)
}).$mount(JsDocument("SUBUTAI"));