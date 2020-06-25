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

import "./theme/index.css";

Vue.use(ElementUI,{ locale });

Vue.use(MyDateVue);
Vue.use(IsStringVue);
Vue.use(Url);
Vue.use(AxiosHttp);
Vue.mixin(DefaultMixin);
Vue.use(VueRouter);
Vue.config.productionTip = false;

var router = new VueRouter({ routes });


var head = document.createElement("head");
var body = document.createElement("body");
var container = document.createElement("div");
var ch = document.createElement('meta');
ch.httpEquiv = "Content-Type";
ch.content = "text/html; charset=utf-8";
document.title = "SUBUTAI FW";
head.appendChild(ch);
body.appendChild(container);

document.documentElement.appendChild(head);
document.documentElement.appendChild(body);

new Vue({
    router,
    render: h => h(main)
    }).$mount(container);