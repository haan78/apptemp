import Vue from 'vue'
import DefaultMixin from "../lib/Vue/DefaultMixin"
import Login from "./components/login.vue";

Vue.mixin(DefaultMixin);

new Vue({    
    render: h => h(Login)
    }).$mount('#app')